<?php

namespace App\Shared\Contracts;

use App\Models\Booking;

interface BookingServiceInterface extends ServiceInterface
{
    /**
     * Create a new booking.
     */
    public function createBooking(array $data): Booking;

    /**
     * Confirm booking and process payment.
     */
    public function confirmBooking(Booking $booking): bool;

    /**
     * Cancel booking.
     */
    public function cancelBooking(Booking $booking, string $reason = null, int $cancelledBy = null): bool;

    /**
     * Check slot availability.
     */
    public function checkAvailability(array $criteria): array;
}
