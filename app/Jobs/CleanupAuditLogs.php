<?php

namespace App\Jobs;

use App\Domains\Admin\Models\AuditLog;
use App\Domains\User\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * CleanupAuditLogs Job
 *
 * Periodically cleans up old audit logs based on retention policy.
 */
class CleanupAuditLogs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300; // 5 minutes
    public int $tries = 3;

    protected int $retentionDays;
    protected bool $keepCriticalLogs;

    public function __construct(int $retentionDays = 90, bool $keepCriticalLogs = true)
    {
        $this->retentionDays = $retentionDays;
        $this->keepCriticalLogs = $keepCriticalLogs;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Starting audit logs cleanup', [
                'retention_days' => $this->retentionDays,
                'keep_critical' => $this->keepCriticalLogs
            ]);

            $cutoffDate = Carbon::now()->subDays($this->retentionDays);

            $query = AuditLog::where('created_at', '<', $cutoffDate);

            // If keeping critical logs, exclude certain actions
            if ($this->keepCriticalLogs) {
                $criticalActions = [
                    'delete',
                    'suspend',
                    'verify',
                    'reject',
                    'login',
                    'logout'
                ];

                $query->whereNotIn('action', $criticalActions);
            }

            $deletedCount = $query->count();

            // Delete in chunks to avoid memory issues
            $query->chunk(1000, function ($logs) {
                $logs->each->delete();
            });

            // Update cache with cleanup statistics
            Cache::put('audit_logs_last_cleanup', [
                'date' => Carbon::now(),
                'deleted_count' => $deletedCount,
                'retention_days' => $this->retentionDays
            ], 86400); // Cache for 24 hours

            // Create audit log for this cleanup
            AuditLog::createLog(
                action: 'cleanup',
                resourceType: 'audit_logs',
                additionalData: [
                    'deleted_count' => $deletedCount,
                    'retention_days' => $this->retentionDays,
                    'keep_critical' => $this->keepCriticalLogs
                ]
            );

            Log::info('Audit logs cleanup completed', [
                'deleted_count' => $deletedCount,
                'cutoff_date' => $cutoffDate->toDateTimeString()
            ]);

        } catch (\Exception $e) {
            Log::error('Audit logs cleanup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Handle job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('CleanupAuditLogs job failed', [
            'error' => $exception->getMessage(),
            'retention_days' => $this->retentionDays
        ]);

        // Optionally notify administrators
        // NotifyAdminJob::dispatch('Audit logs cleanup failed', $exception->getMessage());
    }
}
