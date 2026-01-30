<?php

namespace App\Policies;

use App\Domains\User\Models\User;
use App\Domains\Booking\Models\Booking;
use Illuminate\Auth\Access\HandlesAuthorization;

class VisitorBookingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given booking can be viewed by the user.
     */
    public function view(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id;
    }

    /**
     * Determine if the user can create bookings.
     */
    public function create(User $user): bool
    {
        // User must have at least one vehicle
        if ($user->vehicles()->count() === 0) {
            return false;
        }

        // Check if user has reached active booking limit
        $maxActiveBookings = config('parking.max_active_bookings_per_user', 3);
        $activeBookingsCount = $user->bookings()
                                   ->whereIn('status', ['pending', 'confirmed', 'active'])
                                   ->count();

        return $activeBookingsCount < $maxActiveBookings;
    }

    /**
     * Determine if the given booking can be updated by the user.
     */
    public function update(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id &&
               in_array($booking->status, ['pending', 'confirmed']);
    }

    /**
     * Determine if the given booking can be cancelled by the user.
     */
    public function cancel(User $user, Booking $booking): bool
    {
        // Can only cancel own bookings
        if ($user->id !== $booking->user_id) {
            return false;
        }

        // Can only cancel pending or confirmed bookings
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return false;
        }

        // Check cancellation deadline
        $cancellationDeadline = $booking->start_datetime->subHours(1);
        return now() < $cancellationDeadline;
    }

    /**
     * Determine if the given booking can be extended by the user.
     */
    public function extend(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id &&
               $booking->status === 'active' &&
               $booking->end_datetime > now();
    }

    /**
     * Determine if the user can pay for the booking.
     */
    public function pay(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id &&
               in_array($booking->status, ['pending', 'confirmed']) &&
               !$booking->isPaid();
    }
}
