<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Auth\Access\Response;

class VehiclePolicy
{
    /**
     * Determine whether the user can view any vehicles.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('vehicles.view') ||
               $user->hasRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can view the vehicle.
     */
    public function view(User $user, Vehicle $vehicle): bool
    {
        // Users can view their own vehicles
        if ($user->id === $vehicle->user_id) {
            return true;
        }

        // Admin/managers can view all vehicles
        return $user->hasPermission('vehicles.view') ||
               $user->hasRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can create vehicles.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('vehicles.create') ||
               $user->hasRole(['admin', 'manager', 'user']);
    }

    /**
     * Determine whether the user can update the vehicle.
     */
    public function update(User $user, Vehicle $vehicle): bool
    {
        // Users can update their own vehicles if not verified
        if ($user->id === $vehicle->user_id && !$vehicle->isVerified()) {
            return true;
        }

        // Admin/managers can update any vehicle
        return $user->hasPermission('vehicles.update') ||
               $user->hasRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can delete the vehicle.
     */
    public function delete(User $user, Vehicle $vehicle): bool
    {
        // Users can delete their own vehicles if not in active booking
        if ($user->id === $vehicle->user_id) {
            $hasActiveBooking = $vehicle->bookings()
                ->whereIn('status', ['confirmed', 'active'])
                ->exists();

            return !$hasActiveBooking;
        }

        // Admin can delete any vehicle
        return $user->hasPermission('vehicles.delete') ||
               $user->hasRole('admin');
    }

    /**
     * Determine whether the user can verify vehicles.
     */
    public function verify(User $user): bool
    {
        return $user->hasPermission('vehicles.verify') ||
               $user->hasRole(['admin', 'manager']);
    }
}
