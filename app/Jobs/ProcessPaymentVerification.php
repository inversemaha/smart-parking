<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

/**
 * Job to verify payment status with gateway
 */
class ProcessPaymentVerification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Payment $payment;

    /**
     * Create a new job instance.
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            if ($this->payment->status !== 'pending') {
                return; // Skip if already processed
            }

            // Verify payment with SSLCommerz
            if ($this->payment->gateway === 'sslcommerz') {
                $this->verifySSLCommerzPayment();
            }

            // Add more payment gateways as needed

        } catch (\Exception $e) {
            Log::error('Error verifying payment: ' . $e->getMessage(), [
                'payment_id' => $this->payment->id
            ]);
            throw $e;
        }
    }

    /**
     * Verify payment with SSLCommerz
     */
    protected function verifySSLCommerzPayment(): void
    {
        $storeId = config('services.sslcommerz.store_id');
        $storePassword = config('services.sslcommerz.store_password');
        $isSandbox = config('services.sslcommerz.sandbox', true);

        $baseUrl = $isSandbox
            ? 'https://sandbox.sslcommerz.com'
            : 'https://securepay.sslcommerz.com';

        $response = Http::post("{$baseUrl}/validator/api/validationserverAPI.php", [
            'val_id' => $this->payment->gateway_transaction_id,
            'store_id' => $storeId,
            'store_passwd' => $storePassword,
            'format' => 'json'
        ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to verify payment with SSLCommerz');
        }

        $data = $response->json();

        // Log the verification response
        $this->payment->sslcommerzLogs()->create([
            'request_payload' => json_encode([
                'val_id' => $this->payment->gateway_transaction_id,
                'store_id' => $storeId,
            ]),
            'response_payload' => $response->body(),
            'status' => $data['status'] ?? 'FAILED',
        ]);

        // Update payment status based on response
        if (isset($data['status']) && $data['status'] === 'VALID') {
            $this->payment->update([
                'status' => 'completed',
                'verified_at' => now(),
                'gateway_response' => $data,
            ]);

            // Update related booking
            if ($this->payment->booking) {
                $this->payment->booking->update([
                    'payment_status' => 'paid',
                ]);
            }

            Log::info('Payment verified successfully', [
                'payment_id' => $this->payment->id,
                'amount' => $this->payment->amount
            ]);
        } else {
            $this->payment->update([
                'status' => 'failed',
                'verified_at' => now(),
                'gateway_response' => $data,
            ]);

            Log::warning('Payment verification failed', [
                'payment_id' => $this->payment->id,
                'response' => $data
            ]);
        }
    }

    /**
     * Handle job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('ProcessPaymentVerification job failed: ' . $exception->getMessage(), [
            'payment_id' => $this->payment->id
        ]);

        // Mark payment as failed
        $this->payment->update([
            'status' => 'failed',
            'verified_at' => now(),
        ]);
    }
}
