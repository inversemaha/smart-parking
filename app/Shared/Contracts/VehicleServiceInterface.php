<?php

namespace App\Shared\Contracts;

use App\Domains\Vehicle\Models\Vehicle;

interface VehicleServiceInterface extends ServiceInterface
{
    /**
     * Create a new vehicle.
     */
    public function createVehicle(array $data): Vehicle;

    /**
     * Update vehicle information.
     */
    public function updateVehicle(Vehicle $vehicle, array $data): Vehicle;

    /**
     * Initiate BRTA verification.
     */
    public function initiateVerification(Vehicle $vehicle): bool;

    /**
     * Manual verification.
     */
    public function manualVerification(Vehicle $vehicle, int $verifiedBy, string $status, string $reason, array $documents = null): bool;

    /**
     * Get vehicles for user.
     */
    public function getUserVehicles(int $userId, array $filters = []);
}
