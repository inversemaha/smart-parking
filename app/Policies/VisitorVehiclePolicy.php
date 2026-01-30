<?php

namespace App\Policies;

use App\Domains\User\Models\User;
use App\Domains\Vehicle\Models\Vehicle;
use Illuminate\Auth\Access\HandlesAuthorization;

class VisitorVehiclePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given vehicle can be viewed by the user.
     */
    public function view(User $user, Vehicle $vehicle): bool
    {
        return $user->id === $vehicle->user_id;
    }

    /**
     * Determine if the user can create vehicles.
     */
    public function create(User $user): bool
    {
        // Visitors can create vehicles if they don't exceed the limit
        $maxVehicles = config('parking.max_vehicles_per_user', 5);
        return $user->vehicles()->count() < $maxVehicles;
    }

    /**
     * Determine if the given vehicle can be updated by the user.
     */
    public function update(User $user, Vehicle $vehicle): bool
    {
        return $user->id === $vehicle->user_id;
    }

    /**
     * Determine if the given vehicle can be deleted by the user.
     */
    public function delete(User $user, Vehicle $vehicle): bool
    {
        // Can't delete if vehicle has active bookings
        if ($vehicle->hasActiveBookings()) {
            return false;
        }

        return $user->id === $vehicle->user_id;
    }
}
