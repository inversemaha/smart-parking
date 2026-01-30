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
 * Job to send password changed notification
 */
class SendPasswordChangedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected User $user;
    protected array $metadata;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, array $metadata = [])
    {
        $this->user = $user;
        $this->metadata = $metadata;
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationService $notificationService, AuditLogService $auditLogService): void
    {
        try {
            // Send password changed notification
            $notificationService->sendPasswordChangedNotification($this->user);

            // Log the notification
            $auditLogService->log(
                'password_changed_notification_sent',
                'Password changed notification sent to user',
                null,
                $this->user->id,
                array_merge([
                    'email' => $this->user->email,
                    'notification_type' => 'password_changed'
                ], $this->metadata)
            );

            Log::info('Password changed notification sent successfully', [
                'user_id' => $this->user->id,
                'email' => $this->user->email
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send password changed notification', [
                'user_id' => $this->user->id,
                'email' => $this->user->email,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Password changed notification job failed', [
            'user_id' => $this->user->id,
            'email' => $this->user->email,
            'error' => $exception->getMessage()
        ]);
    }
}
