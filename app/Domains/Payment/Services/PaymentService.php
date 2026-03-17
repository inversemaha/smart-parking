<?php

namespace App\Domains\Payment\Services;

use App\Domains\Payment\Models\Payment;
use App\Domains\Booking\Models\Booking;
use App\Domains\Payment\Repositories\PaymentRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentService
{
    protected $paymentRepository;
    protected $sslcommerzConfig;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
        $this->sslcommerzConfig = config('services.sslcommerz');
    }

    /**
     * Initiate payment.
     */
    public function initiatePayment(Booking $booking, array $data): ?Payment
    {
        DB::beginTransaction();

        try {
            $payment = $this->paymentRepository->create([
                'user_id' => $booking->user_id,
                'payable_type' => Booking::class,
                'payable_id' => $booking->id,
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'BDT',
                'payment_method' => $data['gateway'] ?? 'sslcommerz',
                'gateway' => $data['gateway'] ?? 'sslcommerz',
                'payment_id' => $this->generateTransactionId(),
                'status' => 'initiated',
                'initiated_at' => now(),
                'gateway_response' => null,
            ]);

            // Initiate gateway payment
            $gatewayResponse = $this->initiateGatewayPayment($payment, $booking);

            if (!$gatewayResponse || !isset($gatewayResponse['status']) || $gatewayResponse['status'] !== 'SUCCESS') {
                throw new \Exception('Payment gateway initiation failed');
            }

            $payment->update([
                'gateway_transaction_id' => $gatewayResponse['sessionkey'] ?? null,
                'gateway_response' => $gatewayResponse,
                'status' => 'processing',
            ]);

            DB::commit();

            return $payment;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment initiation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Process payment success callback.
     */
    public function processPaymentSuccess(array $gatewayData): bool
    {
        $transactionId = $gatewayData['tran_id'] ?? null;

        if (!$transactionId) {
            Log::error('Transaction ID missing in payment success callback');
            return false;
        }

        $payment = $this->paymentRepository->findByTransactionId($transactionId);

        if (!$payment) {
            Log::error('Payment not found for transaction ID: ' . $transactionId);
            return false;
        }

        DB::beginTransaction();

        try {
            // Verify payment with gateway
            $isValid = $this->verifyPaymentWithGateway($payment, $gatewayData);

            if (!$isValid) {
                throw new \Exception('Payment verification failed');
            }

            $payment->update([
                'status' => 'paid',
                'gateway_transaction_id' => $gatewayData['bank_tran_id'] ?? null,
                'gateway_response' => $gatewayData,
                'paid_at' => now(),
            ]);

            // Update booking payment status
            if ($payment->booking) {
                $payment->booking->update([
                    'payment_status' => 'paid',
                ]);
            }

            // Log successful payment
            $this->logSslcommerzTransaction($payment, 'success', $gatewayData);

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment success processing failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Process payment failure.
     */
    public function processPaymentFailure(array $gatewayData): bool
    {
        $transactionId = $gatewayData['tran_id'] ?? null;

        if (!$transactionId) {
            return false;
        }

        $payment = $this->paymentRepository->findByTransactionId($transactionId);

        if (!$payment) {
            return false;
        }

        $payment->update([
            'status' => 'failed',
            'gateway_response' => $gatewayData,
            'failed_at' => now(),
            'failure_reason' => $gatewayData['error'] ?? 'Payment failed',
        ]);

        // Log failed payment
        $this->logSslcommerzTransaction($payment, 'failed', $gatewayData);

        return true;
    }

    /**
     * Process refund.
     */
    public function processRefund(Booking $booking): bool
    {
        $payment = $booking->payments()->where('status', 'paid')->first();

        if (!$payment) {
            return false;
        }

        // Refund workflow placeholder
        $payment->update([
            'notes' => trim(($payment->notes ?? '') . ' | Refund requested at ' . now()->toDateTimeString()),
        ]);

        return true;
    }

    /**
     * Initiate gateway payment.
     */
    private function initiateGatewayPayment(Payment $payment, Booking $booking): array
    {
        $postData = [
            'store_id' => $this->sslcommerzConfig['store_id'],
            'store_passwd' => $this->sslcommerzConfig['store_password'],
            'total_amount' => $payment->amount,
            'currency' => $payment->currency,
            'tran_id' => $payment->payment_id,
            'success_url' => route('payments.gateway.success'),
            'fail_url' => route('payments.gateway.failure'),
            'cancel_url' => route('payments.gateway.cancel'),
            'ipn_url' => url('/payments/ipn'),
            'cus_name' => $booking->user->name,
            'cus_email' => $booking->user->email,
            'cus_phone' => $booking->user->phone,
            'product_name' => 'Parking Slot Booking',
            'product_category' => 'Parking',
            'product_profile' => 'general',
        ];

        $response = Http::post($this->sslcommerzConfig['api_url'], $postData);

        return $response->json();
    }

    /**
     * Verify payment with gateway.
     */
    private function verifyPaymentWithGateway(Payment $payment, array $gatewayData): bool
    {
        $postData = [
            'store_id' => $this->sslcommerzConfig['store_id'],
            'store_passwd' => $this->sslcommerzConfig['store_password'],
            'tran_id' => $payment->payment_id,
            'format' => 'json',
        ];

        $response = Http::post($this->sslcommerzConfig['validation_url'], $postData);
        $data = $response->json();

        return isset($data['status']) && $data['status'] === 'VALID';
    }

    /**
     * Log SSLCommerz transaction.
     */
    private function logSslcommerzTransaction(Payment $payment, string $status, array $response): void
    {
        $payment->sslcommerzLogs()->create([
            'transaction_id' => $payment->payment_id,
            'status' => $status,
            'request_data' => null,
            'response_data' => $response,
        ]);
    }

    /**
     * Generate unique transaction ID.
     */
    private function generateTransactionId(): string
    {
        return 'TXN' . date('YmdHis') . Str::random(6);
    }

    /**
     * Get user's total spent amount.
     */
    public function getUserTotalSpent(int $userId): float
    {
        return Payment::where('user_id', $userId)
            ->where('status', 'paid')
            ->sum('amount') ?? 0;
    }

    /**
     * Get user's pending payment count.
     */
    public function getUserPendingPaymentCount(int $userId): int
    {
        return Payment::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();
    }

    /**
     * Get user payments with optional filters.
     */
    public function getUserPayments(int $userId, array $filters = [])
    {
        $query = Payment::with(['booking'])
            ->where('user_id', $userId)
            ->orderByDesc('created_at');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        if (!empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        return $query->paginate(10);
    }
}
