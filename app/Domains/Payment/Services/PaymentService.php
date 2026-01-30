<?php

namespace App\Domains\Payment\Services;

use App\Domains\Payment\Models\Payment;
use App\Domains\Booking\Models\Booking;
use App\Repositories\PaymentRepository;
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
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'BDT',
                'gateway' => $data['gateway'] ?? 'sslcommerz',
                'transaction_id' => $this->generateTransactionId(),
                'status' => 'pending',
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
                'payment_url' => $gatewayResponse['redirectGatewayURL'] ?? null,
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
                'status' => 'completed',
                'gateway_transaction_id' => $gatewayData['bank_tran_id'] ?? null,
                'gateway_response' => $gatewayData,
                'completed_at' => now(),
            ]);

            // Update booking payment status
            $payment->booking->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);

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
        $payment = $booking->payments()->where('status', 'completed')->first();

        if (!$payment) {
            return false;
        }

        // For now, mark as refund pending (manual process)
        $payment->update([
            'refund_status' => 'pending',
            'refund_requested_at' => now(),
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
            'tran_id' => $payment->transaction_id,
            'success_url' => route('payment.success'),
            'fail_url' => route('payment.fail'),
            'cancel_url' => route('payment.cancel'),
            'ipn_url' => route('payment.ipn'),
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
            'tran_id' => $payment->transaction_id,
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
            'transaction_id' => $payment->transaction_id,
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
            ->where('status', 'completed')
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
}
