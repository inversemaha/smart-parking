<?php

namespace App\Domains\Admin\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Domains\Admin\Models\AuditLog;
use Carbon\Carbon;

/**
 * AdminCacheService
 *
 * Service for managing admin-specific caching operations and Redis management.
 */
class AdminCacheService
{
    protected string $prefix = 'admin:';
    protected int $defaultTtl = 3600; // 1 hour

    /**
     * Get admin dashboard statistics with caching.
     */
    public function getDashboardStats(bool $forceRefresh = false): array
    {
        $cacheKey = $this->prefix . 'dashboard_stats';

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, 300, function () {
            return $this->calculateDashboardStats();
        });
    }

    /**
     * Calculate real-time dashboard statistics.
     */
    protected function calculateDashboardStats(): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'users' => [
                'total' => \App\Domains\User\Models\User::count(),
                'active' => \App\Domains\User\Models\User::where('status', 'active')->count(),
                'suspended' => \App\Domains\User\Models\User::where('status', 'suspended')->count(),
                'new_today' => \App\Domains\User\Models\User::whereDate('created_at', $today)->count(),
                'new_this_month' => \App\Domains\User\Models\User::whereDate('created_at', '>=', $thisMonth)->count()
            ],
            'vehicles' => [
                'total' => \App\Domains\Vehicle\Models\Vehicle::count(),
                'pending_verification' => \App\Domains\Vehicle\Models\Vehicle::where('verification_status', 'pending')->count(),
                'verified' => \App\Domains\Vehicle\Models\Vehicle::where('verification_status', 'verified')->count(),
                'rejected' => \App\Domains\Vehicle\Models\Vehicle::where('verification_status', 'rejected')->count()
            ],
            'bookings' => [
                'active' => \App\Domains\Booking\Models\Booking::where('status', 'active')->count(),
                'completed_today' => \App\Domains\Booking\Models\Booking::where('status', 'completed')
                    ->whereDate('created_at', $today)->count(),
                'total_this_month' => \App\Domains\Booking\Models\Booking::whereDate('created_at', '>=', $thisMonth)->count(),
                'expired' => \App\Domains\Booking\Models\Booking::where('status', 'expired')->count()
            ],
            'payments' => [
                'today_amount' => \App\Domains\Payment\Models\Payment::where('status', 'completed')
                    ->whereDate('created_at', $today)->sum('amount'),
                'month_amount' => \App\Domains\Payment\Models\Payment::where('status', 'completed')
                    ->whereDate('created_at', '>=', $thisMonth)->sum('amount'),
                'pending_count' => \App\Domains\Payment\Models\Payment::where('status', 'pending')->count(),
                'failed_today' => \App\Domains\Payment\Models\Payment::where('status', 'failed')
                    ->whereDate('created_at', $today)->count()
            ],
            'system' => [
                'total_audit_logs' => AuditLog::count(),
                'failed_jobs' => \Illuminate\Support\Facades\DB::table('failed_jobs')->count(),
                'cache_hits' => $this->getCacheStatistics()['hits'] ?? 0,
                'last_updated' => Carbon::now()->toISOString()
            ]
        ];
    }

    /**
     * Cache recent admin activities.
     */
    public function cacheRecentActivities(int $limit = 20): array
    {
        $cacheKey = $this->prefix . 'recent_activities';

        return Cache::remember($cacheKey, 300, function () use ($limit) {
            return AuditLog::with('user:id,name')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'user_name' => $log->user?->name ?? 'System',
                        'action' => $log->action_description,
                        'resource_type' => $log->resource_type,
                        'created_at' => $log->created_at->diffForHumans(),
                        'ip_address' => $log->ip_address
                    ];
                })->toArray();
        });
    }

    /**
     * Get system performance metrics.
     */
    public function getSystemMetrics(bool $forceRefresh = false): array
    {
        $cacheKey = $this->prefix . 'system_metrics';

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, 600, function () {
            return [
                'memory_usage' => $this->getMemoryUsage(),
                'database_performance' => $this->getDatabasePerformance(),
                'cache_performance' => $this->getCacheStatistics(),
                'queue_status' => $this->getQueueStatus(),
                'storage_usage' => $this->getStorageUsage()
            ];
        });
    }

    /**
     * Clear specific admin caches.
     */
    public function clearAdminCaches(array $types = []): array
    {
        $clearedCaches = [];

        $cacheTypes = empty($types) ? [
            'dashboard_stats',
            'recent_activities',
            'system_metrics',
            'user_statistics',
            'revenue_charts',
            'parking_occupancy'
        ] : $types;

        foreach ($cacheTypes as $type) {
            $cacheKey = $this->prefix . $type;
            if (Cache::forget($cacheKey)) {
                $clearedCaches[] = $type;
            }
        }

        // Log cache clearing action
        AuditLog::createLog(
            action: 'cache_clear',
            resourceType: 'system',
            additionalData: [
                'cleared_caches' => $clearedCaches,
                'requested_types' => $types
            ]
        );

        return $clearedCaches;
    }

    /**
     * Clear all admin-related caches.
     */
    public function clearAllAdminCaches(): int
    {
        $pattern = $this->prefix . '*';
        $keys = Cache::getRedis()->keys($pattern);

        $deletedCount = 0;
        foreach ($keys as $key) {
            if (Cache::forget(str_replace('laravel_database_', '', $key))) {
                $deletedCount++;
            }
        }

        AuditLog::createLog(
            action: 'cache_flush',
            resourceType: 'system',
            additionalData: [
                'deleted_keys' => $deletedCount,
                'pattern' => $pattern
            ]
        );

        return $deletedCount;
    }

    /**
     * Get Redis connection status and statistics.
     */
    public function getRedisStatus(): array
    {
        try {
            $redis = Cache::getRedis();
            $info = $redis->info();

            return [
                'status' => 'connected',
                'version' => $info['redis_version'] ?? 'unknown',
                'used_memory' => $info['used_memory_human'] ?? 'unknown',
                'connected_clients' => $info['connected_clients'] ?? 0,
                'total_commands_processed' => $info['total_commands_processed'] ?? 0,
                'keyspace_hits' => $info['keyspace_hits'] ?? 0,
                'keyspace_misses' => $info['keyspace_misses'] ?? 0,
                'hit_rate' => $this->calculateHitRate($info)
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'disconnected',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Set cache with admin prefix and audit logging.
     */
    public function set(string $key, $value, int $ttl = null): bool
    {
        $ttl = $ttl ?? $this->defaultTtl;
        $fullKey = $this->prefix . $key;

        $result = Cache::put($fullKey, $value, $ttl);

        if ($result) {
            AuditLog::createLog(
                action: 'cache_set',
                resourceType: 'cache',
                additionalData: [
                    'key' => $key,
                    'ttl' => $ttl,
                    'data_size' => strlen(serialize($value))
                ]
            );
        }

        return $result;
    }

    /**
     * Get cache value with admin prefix.
     */
    public function get(string $key, $default = null)
    {
        $fullKey = $this->prefix . $key;
        return Cache::get($fullKey, $default);
    }

    /**
     * Delete cache key with admin prefix.
     */
    public function forget(string $key): bool
    {
        $fullKey = $this->prefix . $key;
        $result = Cache::forget($fullKey);

        if ($result) {
            AuditLog::createLog(
                action: 'cache_delete',
                resourceType: 'cache',
                additionalData: ['key' => $key]
            );
        }

        return $result;
    }

    /**
     * Get memory usage information.
     */
    protected function getMemoryUsage(): array
    {
        return [
            'current_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
            'peak_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
            'limit' => ini_get('memory_limit')
        ];
    }

    /**
     * Get database performance metrics.
     */
    protected function getDatabasePerformance(): array
    {
        $start = microtime(true);

        try {
            \Illuminate\Support\Facades\DB::select('SELECT 1');
            $responseTime = round((microtime(true) - $start) * 1000, 2);

            return [
                'status' => 'connected',
                'response_time_ms' => $responseTime,
                'connection' => \Illuminate\Support\Facades\DB::connection()->getName()
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get cache statistics.
     */
    protected function getCacheStatistics(): array
    {
        try {
            $redis = Cache::getRedis();
            $info = $redis->info();

            $hits = $info['keyspace_hits'] ?? 0;
            $misses = $info['keyspace_misses'] ?? 0;

            return [
                'hits' => $hits,
                'misses' => $misses,
                'hit_rate' => $this->calculateHitRate($info),
                'memory_usage' => $info['used_memory_human'] ?? 'unknown'
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get queue status information.
     */
    protected function getQueueStatus(): array
    {
        try {
            return [
                'failed_jobs' => \Illuminate\Support\Facades\DB::table('failed_jobs')->count(),
                'pending_jobs' => \Illuminate\Support\Facades\DB::table('jobs')->count(),
                'connection' => config('queue.default')
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get storage usage information.
     */
    protected function getStorageUsage(): array
    {
        try {
            $storagePath = storage_path();
            $totalSpace = disk_total_space($storagePath);
            $freeSpace = disk_free_space($storagePath);
            $usedSpace = $totalSpace - $freeSpace;

            return [
                'total_gb' => round($totalSpace / (1024**3), 2),
                'used_gb' => round($usedSpace / (1024**3), 2),
                'free_gb' => round($freeSpace / (1024**3), 2),
                'usage_percentage' => round(($usedSpace / $totalSpace) * 100, 2)
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Calculate cache hit rate.
     */
    protected function calculateHitRate(array $info): float
    {
        $hits = $info['keyspace_hits'] ?? 0;
        $misses = $info['keyspace_misses'] ?? 0;
        $total = $hits + $misses;

        return $total > 0 ? round(($hits / $total) * 100, 2) : 0.0;
    }
}
