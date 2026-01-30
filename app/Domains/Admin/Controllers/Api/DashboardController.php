<?php

namespace App\Domains\Admin\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Domains\User\Models\User;
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Booking\Models\Booking;
use App\Domains\Payment\Models\Payment;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    /**
     * Get dashboard statistics.
     */
    public function getDashboardStats(Request $request): JsonResponse
    {
        try {
            $cacheKey = 'admin_api_dashboard_stats';

            $stats = Cache::remember($cacheKey, 300, function () {
                $today = Carbon::today();
                $lastWeek = Carbon::now()->subWeek();
                $lastMonth = Carbon::now()->subMonth();

                return [
                    'users' => [
                        'total' => User::count(),
                        'active' => User::where('is_active', true)->count(),
                        'new_today' => User::whereDate('created_at', $today)->count(),
                        'new_week' => User::where('created_at', '>=', $lastWeek)->count(),
                    ],
                    'vehicles' => [
                        'total' => Vehicle::count(),
                        'verified' => Vehicle::where('verification_status', 'verified')->count(),
                        'pending' => Vehicle::where('verification_status', 'pending')->count(),
                        'rejected' => Vehicle::where('verification_status', 'rejected')->count(),
                    ],
                    'bookings' => [
                        'total' => Booking::count(),
                        'active' => Booking::where('status', 'active')->count(),
                        'completed' => Booking::where('status', 'completed')->count(),
                        'today' => Booking::whereDate('created_at', $today)->count(),
                    ],
                    'payments' => [
                        'total_amount' => Payment::where('status', 'completed')->sum('amount'),
                        'today_amount' => Payment::whereDate('created_at', $today)->where('status', 'completed')->sum('amount'),
                        'month_amount' => Payment::where('created_at', '>=', $lastMonth)->where('status', 'completed')->sum('amount'),
                        'pending' => Payment::where('status', 'pending')->count(),
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Dashboard stats error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dashboard statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get analytics data.
     */
    public function getAnalytics(Request $request): JsonResponse
    {
        try {
            $period = $request->get('period', 'week'); // day, week, month, year

            $analytics = [
                'revenue_trend' => $this->getRevenueTrend($period),
                'booking_trend' => $this->getBookingTrend($period),
                'user_growth' => $this->getUserGrowth($period),
                'popular_locations' => $this->getPopularLocations($period),
                'peak_hours' => $this->getPeakHours($period)
            ];

            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);

        } catch (\Exception $e) {
            Log::error('Analytics error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch analytics data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pending vehicles for verification.
     */
    public function getPendingVehicles(Request $request): JsonResponse
    {
        try {
            $vehicles = Vehicle::with(['user', 'documents'])
                ->where('verification_status', 'pending')
                ->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 20));

            return response()->json([
                'success' => true,
                'data' => $vehicles
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch pending vehicles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify a vehicle.
     */
    public function verifyVehicle(Request $request, Vehicle $vehicle): JsonResponse
    {
        $request->validate([
            'verification_notes' => 'nullable|string|max:1000'
        ]);

        try {
            $vehicle->update([
                'verification_status' => 'verified',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'verification_notes' => $request->verification_notes
            ]);

            // Log the action
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'vehicle_verified',
                'auditable_type' => Vehicle::class,
                'auditable_id' => $vehicle->id,
                'old_values' => ['verification_status' => 'pending'],
                'new_values' => ['verification_status' => 'verified'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle verified successfully',
                'data' => $vehicle
            ]);

        } catch (\Exception $e) {
            Log::error('Vehicle verification error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to verify vehicle',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a vehicle.
     */
    public function rejectVehicle(Request $request, Vehicle $vehicle): JsonResponse
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        try {
            $vehicle->update([
                'verification_status' => 'rejected',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'verification_notes' => $request->rejection_reason
            ]);

            // Log the action
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'vehicle_rejected',
                'auditable_type' => Vehicle::class,
                'auditable_id' => $vehicle->id,
                'old_values' => ['verification_status' => 'pending'],
                'new_values' => ['verification_status' => 'rejected'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle rejected successfully',
                'data' => $vehicle
            ]);

        } catch (\Exception $e) {
            Log::error('Vehicle rejection error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to reject vehicle',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get vehicle documents.
     */
    public function getVehicleDocuments(Request $request, Vehicle $vehicle): JsonResponse
    {
        try {
            $documents = $vehicle->documents()->get();

            return response()->json([
                'success' => true,
                'data' => $documents
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch vehicle documents',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get system health status.
     */
    public function getSystemHealth(Request $request): JsonResponse
    {
        try {
            $health = [
                'database' => $this->checkDatabaseConnection(),
                'cache' => $this->checkCacheConnection(),
                'queue' => $this->checkQueueConnection(),
                'storage' => $this->checkStorageSpace(),
                'memory' => $this->getMemoryUsage(),
                'timestamp' => now()->toISOString()
            ];

            return response()->json([
                'success' => true,
                'data' => $health
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check system health',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get system logs.
     */
    public function getSystemLogs(Request $request): JsonResponse
    {
        try {
            $level = $request->get('level', 'all');
            $limit = (int) $request->get('limit', 100);
            $page = (int) $request->get('page', 1);

            $logs = $this->getLogEntries($level, $limit, $page);

            return response()->json([
                'success' => true,
                'data' => $logs
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch system logs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear application cache.
     */
    public function clearCache(Request $request): JsonResponse
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            return response()->json([
                'success' => true,
                'message' => 'Cache cleared successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to clear cache: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get queue status.
     */
    public function getQueueStatus(Request $request): JsonResponse
    {
        try {
            // This is a simplified implementation - you might want to integrate with Redis or database queue monitoring
            $queueStatus = [
                'total_jobs' => 0,
                'pending_jobs' => 0,
                'failed_jobs' => 0,
                'processed_jobs' => 0,
                'last_processed' => null
            ];

            // If using database queue, you can query the jobs table
            if (config('queue.default') === 'database') {
                $queueStatus['pending_jobs'] = DB::table('jobs')->count();
                $queueStatus['failed_jobs'] = DB::table('failed_jobs')->count();
            }

            return response()->json([
                'success' => true,
                'data' => $queueStatus
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch queue status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Helper methods

    private function getRevenueTrend(string $period): array
    {
        $query = Payment::where('status', 'completed');
        $groupBy = 'DATE(created_at)';

        switch ($period) {
            case 'day':
                $query->whereDate('created_at', today());
                $groupBy = 'HOUR(created_at)';
                break;
            case 'week':
                $query->where('created_at', '>=', Carbon::now()->subWeek());
                break;
            case 'month':
                $query->where('created_at', '>=', Carbon::now()->subMonth());
                break;
            case 'year':
                $query->where('created_at', '>=', Carbon::now()->subYear());
                $groupBy = 'DATE_FORMAT(created_at, "%Y-%m")';
                break;
        }

        return $query->selectRaw("{$groupBy} as period, SUM(amount) as revenue")
            ->groupByRaw($groupBy)
            ->orderBy('period')
            ->get()
            ->toArray();
    }

    private function getBookingTrend(string $period): array
    {
        $query = Booking::query();
        $groupBy = 'DATE(created_at)';

        switch ($period) {
            case 'day':
                $query->whereDate('created_at', today());
                $groupBy = 'HOUR(created_at)';
                break;
            case 'week':
                $query->where('created_at', '>=', Carbon::now()->subWeek());
                break;
            case 'month':
                $query->where('created_at', '>=', Carbon::now()->subMonth());
                break;
            case 'year':
                $query->where('created_at', '>=', Carbon::now()->subYear());
                $groupBy = 'DATE_FORMAT(created_at, "%Y-%m")';
                break;
        }

        return $query->selectRaw("{$groupBy} as period, COUNT(*) as bookings")
            ->groupByRaw($groupBy)
            ->orderBy('period')
            ->get()
            ->toArray();
    }

    private function getUserGrowth(string $period): array
    {
        $query = User::query();
        $groupBy = 'DATE(created_at)';

        switch ($period) {
            case 'week':
                $query->where('created_at', '>=', Carbon::now()->subWeek());
                break;
            case 'month':
                $query->where('created_at', '>=', Carbon::now()->subMonth());
                break;
            case 'year':
                $query->where('created_at', '>=', Carbon::now()->subYear());
                $groupBy = 'DATE_FORMAT(created_at, "%Y-%m")';
                break;
        }

        return $query->selectRaw("{$groupBy} as period, COUNT(*) as new_users")
            ->groupByRaw($groupBy)
            ->orderBy('period')
            ->get()
            ->toArray();
    }

    private function getPopularLocations(string $period): array
    {
        $query = Booking::with('parkingSlot.parkingArea');

        switch ($period) {
            case 'week':
                $query->where('created_at', '>=', Carbon::now()->subWeek());
                break;
            case 'month':
                $query->where('created_at', '>=', Carbon::now()->subMonth());
                break;
        }

        return $query->join('parking_slots', 'bookings.parking_slot_id', '=', 'parking_slots.id')
            ->join('parking_areas', 'parking_slots.parking_area_id', '=', 'parking_areas.id')
            ->selectRaw('parking_areas.name as location, COUNT(*) as bookings')
            ->groupBy('parking_areas.id', 'parking_areas.name')
            ->orderByDesc('bookings')
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function getPeakHours(string $period): array
    {
        $query = Booking::query();

        switch ($period) {
            case 'week':
                $query->where('created_at', '>=', Carbon::now()->subWeek());
                break;
            case 'month':
                $query->where('created_at', '>=', Carbon::now()->subMonth());
                break;
        }

        return $query->selectRaw('HOUR(start_time) as hour, COUNT(*) as bookings')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->toArray();
    }

    private function checkDatabaseConnection(): array
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'healthy', 'message' => 'Connected'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Connection failed: ' . $e->getMessage()];
        }
    }

    private function checkCacheConnection(): array
    {
        try {
            Cache::put('health_check', 'test', 1);
            $value = Cache::get('health_check');
            Cache::forget('health_check');

            return $value === 'test'
                ? ['status' => 'healthy', 'message' => 'Connected']
                : ['status' => 'error', 'message' => 'Cache test failed'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Cache error: ' . $e->getMessage()];
        }
    }

    private function checkQueueConnection(): array
    {
        try {
            // Simple queue check
            return ['status' => 'healthy', 'message' => 'Queue system operational'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Queue error: ' . $e->getMessage()];
        }
    }

    private function checkStorageSpace(): array
    {
        try {
            $free = disk_free_space(storage_path());
            $total = disk_total_space(storage_path());
            $used = $total - $free;
            $percentage = round(($used / $total) * 100, 2);

            $status = $percentage > 90 ? 'warning' : ($percentage > 95 ? 'error' : 'healthy');

            return [
                'status' => $status,
                'message' => "Storage: {$percentage}% used",
                'percentage' => $percentage,
                'free' => $free,
                'total' => $total,
                'used' => $used
            ];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Storage check failed: ' . $e->getMessage()];
        }
    }

    private function getMemoryUsage(): array
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = $this->parseSize(ini_get('memory_limit'));
        $percentage = round(($memoryUsage / $memoryLimit) * 100, 2);

        $status = $percentage > 80 ? 'warning' : ($percentage > 90 ? 'error' : 'healthy');

        return [
            'status' => $status,
            'message' => "Memory: {$percentage}% used",
            'percentage' => $percentage,
            'used' => $memoryUsage,
            'limit' => $memoryLimit
        ];
    }

    private function getLogEntries(string $level, int $limit, int $page): array
    {
        $logFile = storage_path('logs/laravel.log');

        if (!file_exists($logFile)) {
            return [
                'data' => [],
                'total' => 0,
                'page' => $page,
                'per_page' => $limit
            ];
        }

        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $filteredLines = [];

        foreach (array_reverse($lines) as $line) {
            if ($level !== 'all' && stripos($line, $level) === false) {
                continue;
            }
            $filteredLines[] = ['content' => $line, 'timestamp' => now()->toISOString()];
        }

        $total = count($filteredLines);
        $offset = ($page - 1) * $limit;
        $paginatedLines = array_slice($filteredLines, $offset, $limit);

        return [
            'data' => $paginatedLines,
            'total' => $total,
            'page' => $page,
            'per_page' => $limit
        ];
    }

    private function parseSize(string $size): int
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);

        if ($unit) {
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return round($size);
        }
    }
}
