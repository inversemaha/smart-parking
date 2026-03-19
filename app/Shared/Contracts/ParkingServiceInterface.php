<?php

namespace App\Shared\Contracts;

use App\Domains\Parking\Models\ParkingSlot;
use Carbon\Carbon;

interface ParkingServiceInterface extends ServiceInterface
{
    /**
     * Find an available slot for a time window.
     */
    public function findAvailableSlot(
        int $parkingLocationId,
        string $vehicleType,
        Carbon $startTime,
        Carbon $endTime
    ): ?ParkingSlot;
}
