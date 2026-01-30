<?php

namespace App\Jobs;

use App\Domains\Admin\Models\AuditLog;
use App\Domains\User\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use App\Shared\Notifications\EmergencyNotification;

/**
 * SendEmergencyBroadcast Job
 *
 * Sends emergency broadcasts to all users or specific groups.
 */
class SendEmergencyBroadcast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600; // 10 minutes
    public int $tries = 3;

    protected string $message;
    protected string $type;
    protected array $targetRoles;
    protected array $additionalData;
    protected User $admin;

    public function __construct(
        string $message,
        User $admin,
        string $type = 'emergency',
        array $targetRoles = ['user'],
        array $additionalData = []
    ) {
        $this->message = $message;
        $this->type = $type;
        $this->targetRoles = $targetRoles;
        $this->additionalData = $additionalData;
        $this->admin = $admin;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Starting emergency broadcast', [
                'type' => $this->type,
                'admin_id' => $this->admin->id,
                'target_roles' => $this->targetRoles
            ]);

            $totalSent = 0;
            $batchSize = 100;

            // Get target users based on roles
            $query = User::where('status', 'active');

            if (!empty($this->targetRoles)) {
                $query->whereHas('roles', function ($roleQuery) {
                    $roleQuery->whereIn('name', $this->targetRoles);
                });
            }

            $totalUsers = $query->count();

            // Process users in batches
            $query->chunk($batchSize, function ($users) use (&$totalSent) {
                $notificationData = [
                    'title' => $this->getNotificationTitle(),
                    'message' => $this->message,
                    'type' => $this->type,
                    'urgency' => 'high',
                    'additional_data' => $this->additionalData,
                    'broadcast_by' => [
                        'id' => $this->admin->id,
                        'name' => $this->admin->name
                    ],
                    'broadcast_at' => now()->toISOString()
                ];

                try {
                    // Send notification to batch
                    Notification::send($users, new EmergencyNotification($notificationData));

                    $totalSent += $users->count();

                    // Optional: Store in cache for immediate retrieval
                    foreach ($users as $user) {
                        $cacheKey = "emergency_notification_{$user->id}";
                        Cache::put($cacheKey, $notificationData, 86400); // 24 hours
                    }

                } catch (\Exception $e) {
                    Log::warning('Failed to send emergency notification to batch', [
                        'error' => $e->getMessage(),
                        'users_count' => $users->count()
                    ]);
                }

                // Small delay between batches to prevent overwhelming
                usleep(100000); // 0.1 seconds
            });

            // Store broadcast statistics
            $broadcastStats = [
                'message' => $this->message,
                'type' => $this->type,
                'total_targets' => $totalUsers,
                'total_sent' => $totalSent,
                'target_roles' => $this->targetRoles,
                'broadcast_by' => $this->admin->id,
                'broadcast_at' => now(),
                'additional_data' => $this->additionalData
            ];

            Cache::put('last_emergency_broadcast', $broadcastStats, 86400);

            // Create audit log
            AuditLog::createLog(
                action: 'broadcast',
                resourceType: 'emergency_notification',
                additionalData: $broadcastStats
            );

            Log::info('Emergency broadcast completed', [
                'total_users' => $totalUsers,
                'total_sent' => $totalSent,
                'type' => $this->type
            ]);

        } catch (\Exception $e) {
            Log::error('Emergency broadcast failed', [
                'error' => $e->getMessage(),
                'type' => $this->type,
                'admin_id' => $this->admin->id
            ]);

            throw $e;
        }
    }

    /**
     * Get notification title based on type.
     */
    protected function getNotificationTitle(): string
    {
        return match ($this->type) {
            'emergency' => __('notifications.emergency.title'),
            'maintenance' => __('notifications.maintenance.title'),
            'security' => __('notifications.security.title'),
            'system' => __('notifications.system.title'),
            default => __('notifications.general.title')
        };
    }

    /**
     * Handle job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SendEmergencyBroadcast job failed', [
            'error' => $exception->getMessage(),
            'type' => $this->type,
            'admin_id' => $this->admin->id,
            'message' => $this->message
        ]);

        // Create audit log for failed broadcast
        AuditLog::createLog(
            action: 'broadcast_failed',
            resourceType: 'emergency_notification',
            additionalData: [
                'error' => $exception->getMessage(),
                'type' => $this->type,
                'message' => $this->message,
                'admin_id' => $this->admin->id
            ]
        );
    }

    /**
     * Calculate job retry delay.
     */
    public function backoff(): int
    {
        return 30; // 30 seconds between retries
    }
}
