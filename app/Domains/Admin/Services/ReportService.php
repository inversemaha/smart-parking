<?php

namespace App\Domains\Admin\Services;

use App\Domains\User\Models\User;
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Booking\Models\Booking;
use App\Domains\Payment\Models\Payment;
use App\Domains\Parking\Models\ParkingLocation;
use App\Domains\Parking\Models\ParkingSlot;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * ReportService
 *
 * Service class for generating various administrative reports
 * with optimized database queries and data aggregation.
 */
class ReportService
{
    /**
     * Generate revenue report with time-based grouping.
     */
    public function getRevenueReport(string $startDate, string $endDate, string $groupBy = 'day'): array
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Get date format based on grouping
        $dateFormat = $this->getDateFormat($groupBy);

        // Revenue data grouped by specified period
        $revenueData = Payment::select([
            DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as period"),
            DB::raw('SUM(amount) as total_amount'),
            DB::raw('COUNT(*) as transaction_count'),
            DB::raw('AVG(amount) as average_amount')
        ])
        ->where('status', 'completed')
        ->whereBetween('created_at', [$start, $end])
        ->groupBy('period')
        ->orderBy('period')
        ->get();

        // Summary statistics
        $totalRevenue = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->sum('amount');

        $totalTransactions = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->count();

        // Revenue by payment method
        $revenueByMethod = Payment::select([
            'payment_method',
            DB::raw('SUM(amount) as total_amount'),
            DB::raw('COUNT(*) as transaction_count')
        ])
        ->where('status', 'completed')
        ->whereBetween('created_at', [$start, $end])
        ->groupBy('payment_method')
        ->get();

        // Top earning days
        $topDays = Payment::select([
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(amount) as daily_amount')
        ])
        ->where('status', 'completed')
        ->whereBetween('created_at', [$start, $end])
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('daily_amount', 'desc')
        ->limit(5)
        ->get();

        return [
            'chart_data' => $revenueData->map(function ($item) {
                return [
                    'date' => $item->period,
                    'amount' => (float) $item->total_amount,
                    'count' => $item->transaction_count,
                    'average' => (float) $item->average_amount
                ];
            })->toArray(),
            'summary' => [
                'total_revenue' => (float) $totalRevenue,
                'total_transactions' => $totalTransactions,
                'average_transaction' => $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0,
                'period_days' => $start->diffInDays($end) + 1
            ],
            'revenue_by_method' => $revenueByMethod->toArray(),
            'top_days' => $topDays->toArray()
        ];
    }

    /**
     * Generate booking report with various filters.
     */
    public function getBookingReport(string $startDate, string $endDate, ?string $status = null): array
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $query = Booking::with(['user:id,name,mobile', 'vehicle:id,registration_number', 'parkingSlot:id,slot_number'])
            ->whereBetween('created_at', [$start, $end]);

        if ($status) {
            $query->where('status', $status);
        }

        $bookings = $query->get();

        // Booking status distribution
        $statusDistribution = Booking::select([
            'status',
            DB::raw('COUNT(*) as count')
        ])
        ->whereBetween('created_at', [$start, $end])
        ->groupBy('status')
        ->get();

        // Bookings by hour
        $hourlyDistribution = Booking::select([
            DB::raw('HOUR(start_time) as hour'),
            DB::raw('COUNT(*) as count')
        ])
        ->whereBetween('created_at', [$start, $end])
        ->groupBy(DB::raw('HOUR(start_time)'))
        ->orderBy('hour')
        ->get();

        // Average duration
        $avgDuration = Booking::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->whereNotNull('actual_end_time')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, start_time, actual_end_time)) as avg_minutes')
            ->value('avg_minutes');

        return [
            'bookings' => $bookings->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'user_name' => $booking->user->name,
                    'user_mobile' => $booking->user->mobile,
                    'vehicle_registration' => $booking->vehicle->registration_number,
                    'slot_number' => $booking->parkingSlot->slot_number,
                    'start_time' => $booking->start_time->format('Y-m-d H:i:s'),
                    'end_time' => $booking->end_time->format('Y-m-d H:i:s'),
                    'actual_end_time' => $booking->actual_end_time?->format('Y-m-d H:i:s'),
                    'amount' => $booking->amount,
                    'status' => $booking->status,
                    'created_at' => $booking->created_at->format('Y-m-d H:i:s')
                ];
            })->toArray(),
            'summary' => [
                'total_bookings' => $bookings->count(),
                'status_distribution' => $statusDistribution->pluck('count', 'status')->toArray(),
                'hourly_distribution' => $hourlyDistribution->pluck('count', 'hour')->toArray(),
                'average_duration_minutes' => round($avgDuration ?? 0, 2)
            ]
        ];
    }

    /**
     * Generate vehicle report with verification status.
     */
    public function getVehicleReport(string $startDate, string $endDate, ?string $verificationStatus = null, ?string $type = null): array
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $query = Vehicle::with(['user:id,name'])
            ->whereBetween('created_at', [$start, $end]);

        if ($verificationStatus) {
            $query->where('verification_status', $verificationStatus);
        }

        if ($type) {
            $query->where('type', $type);
        }

        $vehicles = $query->get();

        // Verification status distribution
        $verificationDistribution = Vehicle::select([
            'verification_status',
            DB::raw('COUNT(*) as count')
        ])
        ->whereBetween('created_at', [$start, $end])
        ->groupBy('verification_status')
        ->get();

        // Vehicle type distribution
        $typeDistribution = Vehicle::select([
            'type',
            DB::raw('COUNT(*) as count')
        ])
        ->whereBetween('created_at', [$start, $end])
        ->groupBy('type')
        ->get();

        // Popular brands
        $brandDistribution = Vehicle::select([
            'brand',
            DB::raw('COUNT(*) as count')
        ])
        ->whereBetween('created_at', [$start, $end])
        ->groupBy('brand')
        ->orderBy('count', 'desc')
        ->limit(10)
        ->get();

        return [
            'vehicles' => $vehicles->map(function ($vehicle) {
                return [
                    'id' => $vehicle->id,
                    'registration_number' => $vehicle->registration_number,
                    'owner_name' => $vehicle->user->name,
                    'type' => $vehicle->type,
                    'brand' => $vehicle->brand,
                    'model' => $vehicle->model,
                    'verification_status' => $vehicle->verification_status,
                    'created_at' => $vehicle->created_at->format('Y-m-d H:i:s')
                ];
            })->toArray(),
            'summary' => [
                'total_vehicles' => $vehicles->count(),
                'verification_distribution' => $verificationDistribution->pluck('count', 'verification_status')->toArray(),
                'type_distribution' => $typeDistribution->pluck('count', 'type')->toArray(),
                'brand_distribution' => $brandDistribution->pluck('count', 'brand')->toArray()
            ]
        ];
    }

    /**
     * Generate user report with activity data.
     */
    public function getUserReport(string $startDate, string $endDate, ?string $status = null): array
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $query = User::withCount(['vehicles', 'bookings', 'payments'])
            ->with(['userSessions' => function ($q) {
                $q->where('is_active', true);
            }])
            ->whereBetween('created_at', [$start, $end]);

        if ($status) {
            $query->where('status', $status);
        }

        $users = $query->get();

        // User status distribution
        $statusDistribution = User::select([
            'status',
            DB::raw('COUNT(*) as count')
        ])
        ->whereBetween('created_at', [$start, $end])
        ->groupBy('status')
        ->get();

        // Registration trend
        $registrationTrend = User::select([
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        ])
        ->whereBetween('created_at', [$start, $end])
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('date')
        ->get();

        return [
            'users' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'mobile' => $user->mobile,
                    'status' => $user->status,
                    'vehicles_count' => $user->vehicles_count,
                    'bookings_count' => $user->bookings_count,
                    'payments_count' => $user->payments_count,
                    'total_spent' => $user->payments()->where('status', 'completed')->sum('amount'),
                    'last_login_at' => $user->last_login_at?->format('Y-m-d H:i:s'),
                    'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                    'is_active' => $user->userSessions->count() > 0
                ];
            })->toArray(),
            'summary' => [
                'total_users' => $users->count(),
                'status_distribution' => $statusDistribution->pluck('count', 'status')->toArray(),
                'registration_trend' => $registrationTrend->pluck('count', 'date')->toArray(),
                'active_users' => $users->filter(function ($user) {
                    return $user->userSessions->count() > 0;
                })->count()
            ]
        ];
    }

    /**
     * Generate occupancy report for parking slots.
     */
    public function getOccupancyReport(string $startDate, string $endDate, ?int $locationId = null, ?string $slotType = null): array
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Occupancy data by location and date
        $query = DB::table('parking_slots as ps')
            ->join('parking_locations as pl', 'ps.parking_location_id', '=', 'pl.id')
            ->leftJoin('bookings as b', function ($join) use ($start, $end) {
                $join->on('ps.id', '=', 'b.parking_slot_id')
                     ->whereBetween('b.start_time', [$start, $end])
                     ->where('b.status', '!=', 'cancelled');
            })
            ->select([
                'pl.id as location_id',
                'pl.name as location_name',
                'ps.type as slot_type',
                DB::raw('COUNT(DISTINCT ps.id) as total_slots'),
                DB::raw('COUNT(DISTINCT b.id) as occupied_bookings'),
                DB::raw('DATE(b.start_time) as date')
            ]);

        if ($locationId) {
            $query->where('pl.id', $locationId);
        }

        if ($slotType) {
            $query->where('ps.type', $slotType);
        }

        $occupancyData = $query->groupBy(['pl.id', 'pl.name', 'ps.type', DB::raw('DATE(b.start_time)')])
            ->get();

        // Calculate occupancy rates
        $processedData = $occupancyData->map(function ($item) {
            $occupancyRate = $item->total_slots > 0
                ? ($item->occupied_bookings / $item->total_slots) * 100
                : 0;

            return [
                'location_id' => $item->location_id,
                'location_name' => $item->location_name,
                'slot_type' => $item->slot_type,
                'date' => $item->date,
                'total_slots' => $item->total_slots,
                'occupied_slots' => $item->occupied_bookings,
                'occupancy_rate' => round($occupancyRate, 2)
            ];
        });

        // Peak hours analysis
        $peakHours = DB::table('bookings')
            ->select([
                DB::raw('HOUR(start_time) as hour'),
                DB::raw('COUNT(*) as booking_count')
            ])
            ->whereBetween('start_time', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->groupBy(DB::raw('HOUR(start_time)'))
            ->orderBy('booking_count', 'desc')
            ->limit(5)
            ->get();

        // Overall occupancy statistics
        $totalSlots = ParkingSlot::count();
        $averageOccupancy = $processedData->avg('occupancy_rate');

        return [
            'occupancy_data' => $processedData->toArray(),
            'summary' => [
                'total_slots' => $totalSlots,
                'average_occupancy' => round($averageOccupancy ?? 0, 2),
                'peak_hours' => $peakHours->pluck('booking_count', 'hour')->toArray(),
                'period_days' => $start->diffInDays($end) + 1
            ]
        ];
    }

    /**
     * Get date format for SQL grouping based on period.
     */
    protected function getDateFormat(string $groupBy): string
    {
        return match ($groupBy) {
            'hour' => '%Y-%m-%d %H',
            'day' => '%Y-%m-%d',
            'week' => '%Y-%u',
            'month' => '%Y-%m',
            'year' => '%Y',
            default => '%Y-%m-%d'
        };
    }
}
