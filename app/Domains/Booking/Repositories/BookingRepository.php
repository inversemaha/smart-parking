<?php

namespace App\Domains\Booking\Repositories;

use App\Domains\Booking\Models\Booking;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class BookingRepository
{
    protected $model;

    public function __construct(Booking $model)
    {
        $this->model = $model;
    }

    /**
     * Create a new booking.
     */
    public function create(array $data): Booking
    {
        return $this->model->create($data);
    }

    /**
     * Update booking.
     */
    public function update($model, array $data)
    {
        $model->update($data);
        return $model->fresh();
    }

    /**
     * Find booking by ID.
     */
    public function find(int $id): ?Booking
    {
        return $this->model->with(['user', 'vehicle', 'parkingSlot.parkingLocation'])->find($id);
    }

    /**
     * Check if user has active booking for vehicle.
     */
    public function hasActiveBooking(int $userId, int $vehicleId): bool
    {
        return $this->model->where('user_id', $userId)
                          ->where('vehicle_id', $vehicleId)
                          ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
                          ->exists();
    }

    /**
     * Get user bookings.
     */
    public function getUserBookings(int $userId, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->where('user_id', $userId)
                            ->with(['vehicle', 'parkingSlot.parkingLocation', 'payments']);

        // Apply filters
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (isset($filters['vehicle_id'])) {
            $query->where('vehicle_id', $filters['vehicle_id']);
        }

        if (isset($filters['date_from'])) {
            $query->whereDate('start_time', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('start_time', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')
                    ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get all bookings with filters.
     */
    public function getAllBookings(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->with([
            'user',
            'vehicle',
            'parkingSlot.parkingLocation',
            'payments',
            'vehicleEntries',
            'vehicleExits'
        ]);

        // Apply filters
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (isset($filters['parking_location_id'])) {
            $query->whereHas('parkingSlot', function ($q) use ($filters) {
                $q->where('parking_location_id', $filters['parking_location_id']);
            });
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->whereHas('user', function ($userQuery) use ($filters) {
                    $userQuery->where('name', 'like', '%' . $filters['search'] . '%')
                             ->orWhere('email', 'like', '%' . $filters['search'] . '%');
                })
                ->orWhereHas('vehicle', function ($vehicleQuery) use ($filters) {
                    $vehicleQuery->where('registration_number', 'like', '%' . $filters['search'] . '%');
                });
            });
        }

        if (isset($filters['date_from'])) {
            $query->whereDate('start_time', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('start_time', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')
                    ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get active bookings for a parking location.
     */
    public function getActiveBookingsForLocation(int $parkingLocationId): Collection
    {
        return $this->model->whereHas('parkingSlot', function ($q) use ($parkingLocationId) {
            $q->where('parking_location_id', $parkingLocationId);
        })
        ->whereIn('status', ['confirmed', 'checked_in'])
        ->with(['user', 'vehicle', 'parkingSlot'])
        ->get();
    }

    /**
     * Get bookings expiring soon.
     */
    public function getExpiringSoon(int $minutesBefore = 30): Collection
    {
        $expiryTime = Carbon::now()->addMinutes($minutesBefore);

        return $this->model->where('status', 'checked_in')
                          ->where('end_time', '<=', $expiryTime)
                          ->where('end_time', '>', Carbon::now())
                          ->with(['user', 'vehicle', 'parkingSlot.parkingLocation'])
                          ->get();
    }

    /**
     * Get overdue bookings.
     */
    public function getOverdueBookings(): Collection
    {
        return $this->model->where('status', 'checked_in')
                          ->where('end_time', '<', Carbon::now())
                          ->with(['user', 'vehicle', 'parkingSlot.parkingLocation'])
                          ->get();
    }

    /**
     * Get booking statistics.
     */
    public function getStatistics(array $filters = []): array
    {
        $query = $this->model->query();

        // Apply date filters
        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $baseQuery = clone $query;

        return [
            'total' => $baseQuery->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'confirmed' => (clone $query)->where('status', 'confirmed')->count(),
            'checked_in' => (clone $query)->where('status', 'checked_in')->count(),
            'completed' => (clone $query)->where('status', 'completed')->count(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
            'expired' => (clone $query)->where('status', 'expired')->count(),
            'payment_pending' => (clone $query)->where('payment_status', 'pending')->count(),
            'payment_paid' => (clone $query)->where('payment_status', 'paid')->count(),
            'payment_failed' => (clone $query)->where('payment_status', 'failed')->count(),
            'total_revenue' => (clone $query)->where('payment_status', 'paid')->sum('total_amount'),
            'average_duration' => (clone $query)->avg('duration_hours'),
        ];
    }

    /**
     * Get revenue statistics.
     */
    public function getRevenueStatistics(array $filters = []): array
    {
        $query = $this->model->where('payment_status', 'paid');

        // Apply date filters
        if (isset($filters['date_from'])) {
            $query->whereDate('paid_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('paid_at', '<=', $filters['date_to']);
        }

        return [
            'total_revenue' => $query->sum('total_amount'),
            'total_bookings' => $query->count(),
            'average_booking_value' => $query->avg('total_amount'),
            'daily_revenue' => $query->selectRaw('DATE(paid_at) as date, SUM(total_amount) as revenue')
                                   ->groupBy('date')
                                   ->orderBy('date')
                                   ->pluck('revenue', 'date')
                                   ->toArray(),
        ];
    }
}
