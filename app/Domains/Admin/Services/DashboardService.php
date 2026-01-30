<?php

namespace App\Domains\Admin\Services;

use App\Domains\Booking\Repositories\BookingRepository;
use App\Domains\Vehicle\Repositories\VehicleRepository;
use App\Domains\Payment\Repositories\PaymentRepository;
use App\Domains\Parking\Repositories\ParkingSlotRepository;
use App\Shared\Contracts\ServiceInterface;
use Carbon\Carbon;

class DashboardService implements ServiceInterface
{
    protected $bookingRepository;
    protected $vehicleRepository;
    protected $paymentRepository;
    protected $parkingSlotRepository;

    public function __construct(
        BookingRepository $bookingRepository,
        VehicleRepository $vehicleRepository,
        PaymentRepository $paymentRepository,
        ParkingSlotRepository $parkingSlotRepository
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->vehicleRepository = $vehicleRepository;
        $this->paymentRepository = $paymentRepository;
        $this->parkingSlotRepository = $parkingSlotRepository;
    }

    /**
     * Process business logic.
     */
    public function process(array $data)
    {
        return $this->getDashboardData($data);
    }

    /**
     * Validate business rules.
     */
    public function validate(array $data): array
    {
        return [
            'period' => 'nullable|in:today,week,month,custom',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ];
    }

    /**
     * Handle domain events.
     */
    public function handleEvent(string $event, array $data): bool
    {
        // Handle dashboard-related events
        return true;
    }

    /**
     * Get complete dashboard data.
     */
    public function getDashboardData(array $dateRange): array
    {
        return [
            'stats' => $this->getStatistics($dateRange),
            'recent_activities' => $this->getRecentActivities(),
            'alerts' => $this->getSystemAlerts(),
        ];
    }

    /**
     * Get dashboard statistics.
     */
    public function getStatistics(array $dateRange): array
    {
        return [
            'bookings' => $this->bookingRepository->getStatistics($dateRange),
            'vehicles' => $this->vehicleRepository->getStatistics(),
            'payments' => $this->paymentRepository->getStatistics($dateRange),
            'revenue' => $this->paymentRepository->getRevenueStatistics($dateRange),
        ];
    }

    /**
     * Get recent activities.
     */
    public function getRecentActivities(): array
    {
        return [
            'bookings' => $this->bookingRepository->paginate(['per_page' => 10]),
            'payments' => $this->paymentRepository->paginate(['per_page' => 10]),
        ];
    }

    /**
     * Get system alerts.
     */
    public function getSystemAlerts(): array
    {
        return [
            'expiring_bookings' => $this->bookingRepository->getExpiringSoon(30),
            'overdue_bookings' => $this->bookingRepository->getOverdueBookings(),
            'pending_verifications' => $this->vehicleRepository->getPendingVerification(),
        ];
    }

    /**
     * Get date range based on period.
     */
    public function getDateRange(string $period, $request = null): array
    {
        switch ($period) {
            case 'today':
                return [
                    'date_from' => Carbon::today(),
                    'date_to' => Carbon::today(),
                ];

            case 'week':
                return [
                    'date_from' => Carbon::now()->startOfWeek(),
                    'date_to' => Carbon::now()->endOfWeek(),
                ];

            case 'month':
                return [
                    'date_from' => Carbon::now()->startOfMonth(),
                    'date_to' => Carbon::now()->endOfMonth(),
                ];

            case 'custom':
                return [
                    'date_from' => $request?->get('date_from', Carbon::now()->startOfWeek()),
                    'date_to' => $request?->get('date_to', Carbon::now()->endOfWeek()),
                ];

            default:
                return [
                    'date_from' => Carbon::now()->startOfWeek(),
                    'date_to' => Carbon::now()->endOfWeek(),
                ];
        }
    }
}
