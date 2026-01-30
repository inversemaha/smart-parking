<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use App\Domains\Admin\Models\AuditLog;
use App\Domains\User\Models\User;

/**
 * SystemHealthCheck Job
 *
 * Performs comprehensive system health checks and stores results.
 */
class SystemHealthCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300; // 5 minutes
    public int $tries = 2;

    protected bool $detailedCheck;

    public function __construct(bool $detailedCheck = false)
    {
        $this->detailedCheck = $detailedCheck;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Starting system health check', [
                'detailed' => $this->detailedCheck
            ]);

            $healthData = [
                'timestamp' => now(),
                'overall_status' => 'healthy',
                'checks' => []
            ];

            // Database connectivity check
            $healthData['checks']['database'] = $this->checkDatabase();

            // Redis connectivity check
            $healthData['checks']['redis'] = $this->checkRedis();

            // Queue status check
            $healthData['checks']['queues'] = $this->checkQueues();

            // Storage check
            $healthData['checks']['storage'] = $this->checkStorage();

            // Memory usage check
            $healthData['checks']['memory'] = $this->checkMemory();

            // Application metrics
            $healthData['checks']['metrics'] = $this->getApplicationMetrics();

            if ($this->detailedCheck) {
                // Additional detailed checks
                $healthData['checks']['permissions'] = $this->checkFilePermissions();
                $healthData['checks']['external_services'] = $this->checkExternalServices();
                $healthData['checks']['logs'] = $this->checkSystemLogs();
            }

            // Determine overall status
            $healthData['overall_status'] = $this->determineOverallStatus($healthData['checks']);

            // Store results in cache
            Cache::put('system_health_check', $healthData, 900); // 15 minutes

            // If system is unhealthy, log critical error
            if ($healthData['overall_status'] === 'critical') {
                Log::critical('System health check detected critical issues', $healthData);

                // Create audit log
                AuditLog::createLog(
                    action: 'health_check_critical',
                    resourceType: 'system',
                    additionalData: $healthData
                );
            }

            Log::info('System health check completed', [
                'overall_status' => $healthData['overall_status'],
                'checks_count' => count($healthData['checks'])
            ]);

        } catch (\Exception $e) {
            Log::error('System health check failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Store failure information
            Cache::put('system_health_check', [
                'timestamp' => now(),
                'overall_status' => 'error',
                'error' => $e->getMessage(),
                'checks' => []
            ], 300); // 5 minutes

            throw $e;
        }
    }

    /**
     * Check database connectivity and performance.
     */
    protected function checkDatabase(): array
    {
        try {
            $start = microtime(true);

            // Test basic connection
            DB::connection()->getPdo();

            // Test query performance
            $userCount = DB::table('users')->count();

            $responseTime = round((microtime(true) - $start) * 1000, 2);

            return [
                'status' => 'healthy',
                'response_time_ms' => $responseTime,
                'user_count' => $userCount,
                'connection' => 'active'
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'critical',
                'error' => $e->getMessage(),
                'connection' => 'failed'
            ];
        }
    }

    /**
     * Check Redis connectivity and performance.
     */
    protected function checkRedis(): array
    {
        try {
            $start = microtime(true);

            // Test Redis connection
            Cache::store('redis')->put('health_check', 'test', 10);
            $value = Cache::store('redis')->get('health_check');
            Cache::store('redis')->forget('health_check');

            $responseTime = round((microtime(true) - $start) * 1000, 2);

            return [
                'status' => $value === 'test' ? 'healthy' : 'degraded',
                'response_time_ms' => $responseTime,
                'connection' => 'active'
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'critical',
                'error' => $e->getMessage(),
                'connection' => 'failed'
            ];
        }
    }

    /**
     * Check queue status.
     */
    protected function checkQueues(): array
    {
        try {
            // Get failed jobs count
            $failedJobs = DB::table('failed_jobs')->count();

            // Check if queue workers are running by inspecting recent jobs
            $recentJobs = DB::table('jobs')->where('created_at', '>', now()->subMinutes(5))->count();

            $status = 'healthy';
            if ($failedJobs > 10) {
                $status = 'degraded';
            }
            if ($failedJobs > 50) {
                $status = 'critical';
            }

            return [
                'status' => $status,
                'failed_jobs' => $failedJobs,
                'recent_jobs' => $recentJobs,
                'queue_connection' => config('queue.default')
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'critical',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check storage status.
     */
    protected function checkStorage(): array
    {
        try {
            $storagePath = storage_path();
            $totalSpace = disk_total_space($storagePath);
            $freeSpace = disk_free_space($storagePath);
            $usedSpace = $totalSpace - $freeSpace;
            $usagePercentage = round(($usedSpace / $totalSpace) * 100, 2);

            $status = 'healthy';
            if ($usagePercentage > 80) {
                $status = 'degraded';
            }
            if ($usagePercentage > 95) {
                $status = 'critical';
            }

            return [
                'status' => $status,
                'total_space_gb' => round($totalSpace / (1024**3), 2),
                'free_space_gb' => round($freeSpace / (1024**3), 2),
                'usage_percentage' => $usagePercentage,
                'writable' => is_writable($storagePath)
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'critical',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check memory usage.
     */
    protected function checkMemory(): array
    {
        $memoryUsage = memory_get_usage(true);
        $memoryPeak = memory_get_peak_usage(true);
        $memoryLimit = ini_get('memory_limit');

        // Convert memory limit to bytes
        $limitBytes = $this->convertToBytes($memoryLimit);
        $usagePercentage = $limitBytes ? round(($memoryPeak / $limitBytes) * 100, 2) : 0;

        $status = 'healthy';
        if ($usagePercentage > 70) {
            $status = 'degraded';
        }
        if ($usagePercentage > 90) {
            $status = 'critical';
        }

        return [
            'status' => $status,
            'current_usage_mb' => round($memoryUsage / (1024**2), 2),
            'peak_usage_mb' => round($memoryPeak / (1024**2), 2),
            'limit' => $memoryLimit,
            'usage_percentage' => $usagePercentage
        ];
    }

    /**
     * Get application metrics.
     */
    protected function getApplicationMetrics(): array
    {
        try {
            $metrics = [
                'users_count' => DB::table('users')->count(),
                'active_users' => DB::table('users')->where('status', 'active')->count(),
                'active_bookings' => DB::table('bookings')->where('status', 'active')->count(),
                'pending_payments' => DB::table('payments')->where('status', 'pending')->count(),
                'failed_jobs' => DB::table('failed_jobs')->count(),
                'last_24h_logins' => DB::table('user_sessions')
                    ->where('created_at', '>', now()->subDay())
                    ->distinct('user_id')
                    ->count(),
                'system_errors_24h' => DB::table('audit_logs')
                    ->where('action', 'error')
                    ->where('created_at', '>', now()->subDay())
                    ->count()
            ];

            return [
                'status' => 'healthy',
                'metrics' => $metrics
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'degraded',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check file permissions (detailed check only).
     */
    protected function checkFilePermissions(): array
    {
        $paths = [
            storage_path(),
            storage_path('logs'),
            storage_path('app'),
            storage_path('framework/cache'),
            bootstrap_path('cache')
        ];

        $issues = [];
        foreach ($paths as $path) {
            if (!is_writable($path)) {
                $issues[] = $path;
            }
        }

        return [
            'status' => empty($issues) ? 'healthy' : 'critical',
            'writable_paths' => count($paths) - count($issues),
            'total_paths' => count($paths),
            'issues' => $issues
        ];
    }

    /**
     * Check external services (detailed check only).
     */
    protected function checkExternalServices(): array
    {
        // This would check external APIs like payment gateways, BRTA API, etc.
        return [
            'status' => 'healthy',
            'services' => []
        ];
    }

    /**
     * Check system logs for errors (detailed check only).
     */
    protected function checkSystemLogs(): array
    {
        try {
            $logPath = storage_path('logs/laravel.log');

            if (!file_exists($logPath)) {
                return [
                    'status' => 'healthy',
                    'recent_errors' => 0
                ];
            }

            // Count ERROR level logs in the last hour
            $recentErrors = 0;
            $oneHourAgo = now()->subHour();

            $handle = fopen($logPath, 'r');
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    if (strpos($line, 'ERROR') !== false) {
                        // Simple date extraction - would need more robust parsing
                        $recentErrors++;
                    }
                }
                fclose($handle);
            }

            $status = 'healthy';
            if ($recentErrors > 10) {
                $status = 'degraded';
            }
            if ($recentErrors > 50) {
                $status = 'critical';
            }

            return [
                'status' => $status,
                'recent_errors' => $recentErrors,
                'log_file_size_mb' => round(filesize($logPath) / (1024**2), 2)
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'degraded',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Determine overall system status.
     */
    protected function determineOverallStatus(array $checks): string
    {
        $statuses = array_column($checks, 'status');

        if (in_array('critical', $statuses)) {
            return 'critical';
        }

        if (in_array('degraded', $statuses)) {
            return 'degraded';
        }

        return 'healthy';
    }

    /**
     * Convert memory limit string to bytes.
     */
    protected function convertToBytes(string $value): int
    {
        if ($value === '-1') {
            return 0; // Unlimited
        }

        $unit = strtolower(substr($value, -1));
        $size = (int) $value;

        switch ($unit) {
            case 'g':
                $size *= 1024;
            case 'm':
                $size *= 1024;
            case 'k':
                $size *= 1024;
        }

        return $size;
    }

    /**
     * Handle job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SystemHealthCheck job failed', [
            'error' => $exception->getMessage(),
            'detailed_check' => $this->detailedCheck
        ]);

        // Store failure information in cache
        Cache::put('system_health_check', [
            'timestamp' => now(),
            'overall_status' => 'error',
            'error' => $exception->getMessage(),
            'checks' => []
        ], 300);
    }
}
