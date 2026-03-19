<?php

namespace App\Domains\Parking\Services;

use App\Domains\Parking\Models\ParkingSlot;
use App\Domains\Parking\Repositories\ParkingSlotRepository;
use App\Shared\Contracts\ParkingServiceInterface;
use Carbon\Carbon;

class ParkingService implements ParkingServiceInterface
{
    public function __construct(
        protected ParkingSlotRepository $parkingSlotRepository
    ) {
    }

    /**
     * Process business logic.
     */
    public function process(array $data)
    {
        return $this->findAvailableSlot(
            (int) ($data['parking_location_id'] ?? 0),
            (string) ($data['vehicle_type'] ?? ''),
            Carbon::parse($data['start_time'] ?? now()),
            Carbon::parse($data['end_time'] ?? now()->addHour())
        );
    }

    /**
     * Validate business rules.
     */
    public function validate(array $data): array
    {
        return [
            'parking_location_id' => 'required|exists:parking_locations,id',
            'vehicle_type' => 'required|in:car,motorcycle,bus,truck,bicycle',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ];
    }

    /**
     * Handle domain events.
     */
    public function handleEvent(string $event, array $data): bool
    {
        return in_array($event, ['parking.slot.occupied', 'parking.slot.released'], true);
    }

    /**
     * Find an available slot for booking.
     */
    public function findAvailableSlot(
        int $parkingLocationId,
        string $vehicleType,
        Carbon $startTime,
        Carbon $endTime
    ): ?ParkingSlot {
        return $this->parkingSlotRepository->findAvailableSlot(
            $parkingLocationId,
            $vehicleType,
            $startTime,
            $endTime
        );
    }
}
