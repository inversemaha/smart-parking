<?php

namespace App\Policies;

use App\Domains\Booking\Models\Booking;
use App\Domains\User\Models\User;
use Illuminate\Auth\Access\Response;

class BookingPolicy
{
    /**
     * Determine whether the user can view any bookings.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('bookings.view') ||
               $user->hasRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can view the booking.
     */
    public function view(User $user, Booking $booking): bool
    {
        // Users can view their own bookings
        if ($user->id === $booking->user_id) {
            return true;
        }

        // Admin/managers can view all bookings
        return $user->hasPermission('bookings.view') ||
               $user->hasRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can create bookings.
     */
    public function create(User $user): bool
    {
        // User must have verified vehicles to create bookings
        $hasVerifiedVehicles = $user->vehicles()
            ->where('verification_status', 'verified')
            ->exists();

        if (!$hasVerifiedVehicles) {
            return false;
        }

        return $user->hasPermission('bookings.create') ||
               $user->hasRole(['admin', 'manager', 'user']);
    }

    /**
     * Determine whether the user can update the booking.
     */
    public function update(User $user, Booking $booking): bool
    {
        // Users can update their own pending/confirmed bookings
        if ($user->id === $booking->user_id &&
            in_array($booking->status, ['pending', 'confirmed'])) {
            return true;
        }

        // Admin/managers can update any booking
        return $user->hasPermission('bookings.update') ||
               $user->hasRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can cancel the booking.
     */
    public function cancel(User $user, Booking $booking): bool
    {
        // Users can cancel their own bookings if cancellable
        if ($user->id === $booking->user_id && $booking->canBeCancelled()) {
            return true;
        }

        // Admin/managers can cancel any booking
        return $user->hasPermission('bookings.cancel') ||
               $user->hasRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can delete the booking.
     */
    public function delete(User $user, Booking $booking): bool
    {
        // Only admin can delete bookings
        return $user->hasPermission('bookings.delete') ||
               $user->hasRole('admin');
    }
}
