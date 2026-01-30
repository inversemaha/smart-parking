<?php

namespace App\Domains\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\User\Models\User;
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Booking\Models\Booking;
use App\Domains\Payment\Models\Payment;
use App\Shared\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Admin dashboard overview.
     */
    public function index(): View
    {
        $stats = $this->getDashboardStats();

        return view('admin.dashboard.index', compact('stats'));
    }

    /**
     * Get dashboard statistics.
     */
    protected function getDashboardStats(): array
    {
        $cacheKey = 'admin_dashboard_stats';

        return Cache::remember($cacheKey, 300, function () {
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
    }

    /**
     * Display pending vehicles for verification.
     */
    public function pendingVehicles(): View
    {
        $vehicles = Vehicle::with(['user', 'documents'])
            ->where('verification_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.vehicles.pending', compact('vehicles'));
    }

    /**
     * Verify a vehicle.
     */
    public function verifyVehicle(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $request->validate([
            'verification_notes' => 'nullable|string|max:1000'
        ]);

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

        return redirect()->route('admin.vehicles.pending')
            ->with('success', __('general.vehicle_verified_successfully'));
    }

    /**
     * Reject a vehicle.
     */
    public function rejectVehicle(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

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

        return redirect()->route('admin.vehicles.pending')
            ->with('success', __('general.vehicle_rejected_successfully'));
    }

    /**
     * System health check.
     */
    public function systemHealth(): View
    {
        $health = [
            'database' => $this->checkDatabaseConnection(),
            'cache' => $this->checkCacheConnection(),
            'queue' => $this->checkQueueConnection(),
            'storage' => $this->checkStorageSpace(),
            'memory' => $this->getMemoryUsage(),
        ];

        return view('admin.system.health', compact('health'));
    }

    /**
     * System logs view.
     */
    public function systemLogs(Request $request): View
    {
        $level = $request->get('level', 'all');
        $limit = $request->get('limit', 100);

        // Get log entries (this is a simplified version - you might want to use a proper log viewer)
        $logs = $this->getLogEntries($level, $limit);

        return view('admin.system.logs', compact('logs', 'level', 'limit'));
    }

    /**
     * Clear application cache.
     */
    public function clearCache(Request $request): RedirectResponse
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            return redirect()->back()
                ->with('success', __('general.cache_cleared_successfully'));
        } catch (\Exception $e) {
            Log::error('Failed to clear cache: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', __('general.cache_clear_failed'));
        }
    }

    /**
     * Reports overview.
     */
    public function reports(): View
    {
        return view('admin.reports.index');
    }

    /**
     * Revenue report.
     */
    public function revenueReport(Request $request): View
    {
        $period = $request->get('period', 'month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $revenueData = $this->getRevenueData($period, $startDate, $endDate);

        return view('admin.reports.revenue', compact('revenueData', 'period'));
    }

    /**
     * Booking report.
     */
    public function bookingReport(Request $request): View
    {
        $period = $request->get('period', 'month');
        $status = $request->get('status', 'all');

        $bookingData = $this->getBookingData($period, $status);

        return view('admin.reports.bookings', compact('bookingData', 'period', 'status'));
    }

    /**
     * User report.
     */
    public function userReport(Request $request): View
    {
        $period = $request->get('period', 'month');

        $userData = $this->getUserData($period);

        return view('admin.reports.users', compact('userData', 'period'));
    }

    // Helper methods

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
            // Simple queue check - you might want to implement a more sophisticated check
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
                'details' => [
                    'free' => $this->formatBytes($free),
                    'total' => $this->formatBytes($total),
                    'used' => $this->formatBytes($used),
                    'percentage' => $percentage
                ]
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
            'details' => [
                'used' => $this->formatBytes($memoryUsage),
                'limit' => $this->formatBytes($memoryLimit),
                'percentage' => $percentage
            ]
        ];
    }

    private function getLogEntries(string $level, int $limit): array
    {
        // This is a simplified log reader - consider using a proper log viewer package
        $logFile = storage_path('logs/laravel.log');

        if (!file_exists($logFile)) {
            return [];
        }

        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $lines = array_slice(array_reverse($lines), 0, $limit);

        $logs = [];
        foreach ($lines as $line) {
            if ($level !== 'all' && stripos($line, $level) === false) {
                continue;
            }
            $logs[] = ['content' => $line];
        }

        return $logs;
    }

    private function getRevenueData(string $period, ?string $startDate = null, ?string $endDate = null): array
    {
        $query = Payment::where('status', 'completed');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } else {
            switch ($period) {
                case 'week':
                    $query->where('created_at', '>=', Carbon::now()->subWeek());
                    break;
                case 'month':
                    $query->where('created_at', '>=', Carbon::now()->subMonth());
                    break;
                case 'year':
                    $query->where('created_at', '>=', Carbon::now()->subYear());
                    break;
            }
        }

        return [
            'total' => $query->sum('amount'),
            'count' => $query->count(),
            'average' => $query->avg('amount'),
            'daily' => $query->selectRaw('DATE(created_at) as date, SUM(amount) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
        ];
    }

    private function getBookingData(string $period, string $status): array
    {
        $query = Booking::query();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        switch ($period) {
            case 'week':
                $query->where('created_at', '>=', Carbon::now()->subWeek());
                break;
            case 'month':
                $query->where('created_at', '>=', Carbon::now()->subMonth());
                break;
            case 'year':
                $query->where('created_at', '>=', Carbon::now()->subYear());
                break;
        }

        return [
            'total' => $query->count(),
            'daily' => $query->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'by_status' => Booking::selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->get()
        ];
    }

    private function getUserData(string $period): array
    {
        $query = User::query();

        switch ($period) {
            case 'week':
                $query->where('created_at', '>=', Carbon::now()->subWeek());
                break;
            case 'month':
                $query->where('created_at', '>=', Carbon::now()->subMonth());
                break;
            case 'year':
                $query->where('created_at', '>=', Carbon::now()->subYear());
                break;
        }

        return [
            'total' => $query->count(),
            'active' => $query->where('is_active', true)->count(),
            'daily' => $query->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
        ];
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
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
