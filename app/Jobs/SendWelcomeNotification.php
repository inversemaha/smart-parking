<?php

namespace App\Jobs;

use App\Domains\User\Models\User;
use App\Shared\Services\AuditLogService;
use App\Shared\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job to send user welcome notification after registration
 */
class SendWelcomeNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected User $user;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationService $notificationService, AuditLogService $auditLogService): void
    {
        try {
            // Send welcome notification
            $notificationService->sendWelcomeNotification($this->user);

            // Log the successful notification
            $auditLogService->log(
                'welcome_notification_sent',
                'Welcome notification sent to user',
                null,
                $this->user->id,
                ['email' => $this->user->email]
            );

            Log::info('Welcome notification sent successfully', [
                'user_id' => $this->user->id,
                'email' => $this->user->email
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send welcome notification', [
                'user_id' => $this->user->id,
                'email' => $this->user->email,
                'error' => $e->getMessage()
            ]);

            throw $e; // Re-throw to trigger retry
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Welcome notification job failed after all retries', [
            'user_id' => $this->user->id,
            'email' => $this->user->email,
            'error' => $exception->getMessage()
        ]);
    }
}
