<?php

namespace App\Shared\Jobs;

use App\Domains\User\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEmailNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;
    public string $template;
    public array $data;
    public int $tries = 3;
    public int $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, string $template, array $data = [])
    {
        $this->user = $user;
        $this->template = $template;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            // Create mailable based on template
            $mailable = $this->createMailable();

            if ($mailable) {
                Mail::to($this->user->email)->send($mailable);

                Log::info('Email notification sent successfully', [
                    'user_id' => $this->user->id,
                    'email' => $this->user->email,
                    'template' => $this->template,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Email notification failed', [
                'user_id' => $this->user->id,
                'template' => $this->template,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Create mailable instance based on template.
     */
    protected function createMailable(): ?Mailable
    {
        // Factory pattern for creating mailables
        switch ($this->template) {
            case 'vehicle.created':
                return new \App\Mail\VehicleCreatedMail($this->user, $this->data);
            case 'vehicle.verified':
                return new \App\Mail\VehicleVerifiedMail($this->user, $this->data);
            case 'booking.created':
                return new \App\Mail\BookingCreatedMail($this->user, $this->data);
            case 'payment.completed':
                return new \App\Mail\PaymentCompletedMail($this->user, $this->data);
            default:
                Log::warning('Unknown email template', ['template' => $this->template]);
                return null;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception)
    {
        Log::error('Email notification job failed permanently', [
            'user_id' => $this->user->id,
            'template' => $this->template,
            'error' => $exception->getMessage(),
        ]);
    }
}
