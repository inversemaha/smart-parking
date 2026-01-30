<?php

namespace App\Shared\Jobs;

use App\Domains\Payment\Models\Payment;
use App\Domains\Payment\Services\PaymentService;
use App\Shared\Events\PaymentCompleted;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Payment $payment;
    public array $gatewayData;
    public int $tries = 3;
    public int $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(Payment $payment, array $gatewayData)
    {
        $this->payment = $payment;
        $this->gatewayData = $gatewayData;
    }

    /**
     * Execute the job.
     */
    public function handle(PaymentService $paymentService)
    {
        try {
            $result = $paymentService->processPaymentSuccess($this->gatewayData);

            if ($result) {
                // Fire payment completed event
                event(new PaymentCompleted($this->payment->fresh()));

                Log::info('Payment processed successfully', [
                    'payment_id' => $this->payment->id,
                    'amount' => $this->payment->amount,
                    'gateway' => $this->payment->gateway,
                ]);
            } else {
                Log::warning('Payment processing failed', [
                    'payment_id' => $this->payment->id,
                    'gateway_data' => $this->gatewayData,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Payment processing job failed', [
                'payment_id' => $this->payment->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception)
    {
        Log::error('Payment processing job failed permanently', [
            'payment_id' => $this->payment->id,
            'error' => $exception->getMessage(),
        ]);

        // Mark payment as failed
        $this->payment->update([
            'status' => 'failed',
            'failure_reason' => 'Payment processing failed: ' . $exception->getMessage(),
        ]);
    }
}
