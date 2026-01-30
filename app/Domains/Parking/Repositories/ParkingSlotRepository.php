<?php

namespace App\Repositories;

use App\Models\ParkingSlot;
use App\Models\ParkingLocation;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class ParkingSlotRepository
{
    protected $model;

    public function __construct(ParkingSlot $model)
    {
        $this->model = $model;
    }

    /**
     * Create a new parking slot.
     */
    public function create(array $data): ParkingSlot
    {
        return $this->model->create($data);
    }

    /**
     * Update parking slot.
     */
    public function update(ParkingSlot $slot, array $data): ParkingSlot
    {
        $slot->update($data);
        return $slot->fresh();
    }

    /**
     * Find available slot for booking.
     */
    public function findAvailableSlot(
        int $parkingLocationId,
        string $vehicleType,
        Carbon $startTime,
        Carbon $endTime
    ): ?ParkingSlot {
        return $this->model->where('parking_location_id', $parkingLocationId)
                          ->where('vehicle_type', $vehicleType)
                          ->where('status', 'available')
                          ->whereDoesntHave('bookings', function ($query) use ($startTime, $endTime) {
                              $query->where(function ($q) use ($startTime, $endTime) {
                                  $q->whereBetween('start_time', [$startTime, $endTime])
                                    ->orWhereBetween('end_time', [$startTime, $endTime])
                                    ->orWhere(function ($subQ) use ($startTime, $endTime) {
                                        $subQ->where('start_time', '<=', $startTime)
                                             ->where('end_time', '>=', $endTime);
                                    });
                              })
                              ->whereIn('status', ['confirmed', 'checked_in']);
                          })
                          ->first();
    }

    /**
     * Get all slots for a parking location.
     */
    public function getLocationSlots(int $parkingLocationId): Collection
    {
        return $this->model->where('parking_location_id', $parkingLocationId)
                          ->with(['currentBooking'])
                          ->orderBy('slot_number')
                          ->get();
    }

    /**
     * Get available slots count by vehicle type.
     */
    public function getAvailableSlotsByType(int $parkingLocationId): array
    {
        return $this->model->where('parking_location_id', $parkingLocationId)
                          ->where('status', 'available')
                          ->whereDoesntHave('bookings', function ($query) {
                              $query->whereIn('status', ['confirmed', 'checked_in'])
                                   ->where('start_time', '<=', now())
                                   ->where('end_time', '>=', now());
                          })
                          ->selectRaw('vehicle_type, COUNT(*) as count')
                          ->groupBy('vehicle_type')
                          ->pluck('count', 'vehicle_type')
                          ->toArray();
    }

    /**
     * Get slot occupancy statistics.
     */
    public function getOccupancyStatistics(int $parkingLocationId): array
    {
        $totalSlots = $this->model->where('parking_location_id', $parkingLocationId)->count();

        $occupiedSlots = $this->model->where('parking_location_id', $parkingLocationId)
                                   ->whereHas('bookings', function ($query) {
                                       $query->whereIn('status', ['confirmed', 'checked_in'])
                                            ->where('start_time', '<=', now())
                                            ->where('end_time', '>=', now());
                                   })
                                   ->count();

        $availableSlots = $totalSlots - $occupiedSlots;

        $occupancyRate = $totalSlots > 0 ? round(($occupiedSlots / $totalSlots) * 100, 2) : 0;

        return [
            'total_slots' => $totalSlots,
            'occupied_slots' => $occupiedSlots,
            'available_slots' => $availableSlots,
            'occupancy_rate' => $occupancyRate,
            'by_type' => $this->getSlotStatsByType($parkingLocationId),
        ];
    }

    /**
     * Get slot statistics by vehicle type.
     */
    private function getSlotStatsByType(int $parkingLocationId): array
    {
        $stats = [];

        $vehicleTypes = $this->model->where('parking_location_id', $parkingLocationId)
                                  ->distinct()
                                  ->pluck('vehicle_type');

        foreach ($vehicleTypes as $type) {
            $total = $this->model->where('parking_location_id', $parkingLocationId)
                               ->where('vehicle_type', $type)
                               ->count();

            $occupied = $this->model->where('parking_location_id', $parkingLocationId)
                                  ->where('vehicle_type', $type)
                                  ->whereHas('bookings', function ($query) {
                                      $query->whereIn('status', ['confirmed', 'checked_in'])
                                           ->where('start_time', '<=', now())
                                           ->where('end_time', '>=', now());
                                  })
                                  ->count();

            $stats[$type] = [
                'total' => $total,
                'occupied' => $occupied,
                'available' => $total - $occupied,
                'occupancy_rate' => $total > 0 ? round(($occupied / $total) * 100, 2) : 0,
            ];
        }

        return $stats;
    }

    /**
     * Bulk create slots.
     */
    public function bulkCreate(array $slotsData): bool
    {
        try {
            $this->model->insert($slotsData);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Bulk update slot status.
     */
    public function bulkUpdateStatus(array $slotIds, string $status): int
    {
        return $this->model->whereIn('id', $slotIds)->update(['status' => $status]);
    }
}
