<?php

namespace App\Domains\Booking\Services;

use App\Domains\Booking\Models\Booking;
use App\Domains\Parking\Models\ParkingSlot;
use App\Domains\Vehicle\Models\Vehicle;
use App\Repositories\BookingRepository;
use App\Repositories\ParkingSlotRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BookingService
{
    protected $bookingRepository;
    protected $parkingSlotRepository;
    protected $paymentService;

    public function __construct(
        BookingRepository $bookingRepository,
        ParkingSlotRepository $parkingSlotRepository,
        PaymentService $paymentService
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->parkingSlotRepository = $parkingSlotRepository;
        $this->paymentService = $paymentService;
    }

    /**
     * Create a new booking.
     */
    public function createBooking(array $data): Booking
    {
        DB::beginTransaction();

        try {
            // Validate booking data
            $this->validateBookingData($data);

            // Find available slot
            $slot = $this->findAvailableSlot($data);

            if (!$slot) {
                throw new \Exception(__('parking.no_available_slots'));
            }

            // Calculate duration and amount
            $startTime = Carbon::parse($data['start_time']);
            $endTime = Carbon::parse($data['end_time']);
            $durationHours = $startTime->diffInHours($endTime, false);

            if ($durationHours <= 0) {
                throw new \Exception('Invalid booking duration');
            }

            $hourlyRate = $slot->parkingLocation->hourly_rate;
            $totalAmount = $durationHours * $hourlyRate;

            // Create booking
            $bookingData = array_merge($data, [
                'parking_slot_id' => $slot->id,
                'duration_hours' => $durationHours,
                'hourly_rate' => $hourlyRate,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            $booking = $this->bookingRepository->create($bookingData);

            // Reserve the slot
            $slot->reserve();

            // Log slot assignment
            $booking->slotHistories()->create([
                'parking_slot_id' => $slot->id,
                'action' => 'assigned',
                'action_at' => now(),
                'reason' => 'Initial booking assignment',
            ]);

            DB::commit();

            return $booking;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create booking: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Confirm booking and process payment.
     */
    public function confirmBooking(Booking $booking): bool
    {
        DB::beginTransaction();

        try {
            // Initiate payment
            $payment = $this->paymentService->initiatePayment($booking, [
                'amount' => $booking->total_amount,
                'currency' => 'BDT',
                'gateway' => 'sslcommerz',
            ]);

            if (!$payment) {
                throw new \Exception('Failed to initiate payment');
            }

            $booking->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to confirm booking: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Cancel booking.
     */
    public function cancelBooking(Booking $booking, string $reason = null, int $cancelledBy = null): bool
    {
        if (!$booking->canBeCancelled()) {
            throw new \Exception('This booking cannot be cancelled');
        }

        DB::beginTransaction();

        try {
            $booking->cancel($reason, $cancelledBy);

            // Process refund if payment was made
            if ($booking->payment_status === 'paid') {
                $this->paymentService->processRefund($booking);
            }

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to cancel booking: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Find available parking slot.
     */
    private function findAvailableSlot(array $data): ?ParkingSlot
    {
        $vehicle = Vehicle::findOrFail($data['vehicle_id']);
        $startTime = Carbon::parse($data['start_time']);
        $endTime = Carbon::parse($data['end_time']);

        return $this->parkingSlotRepository->findAvailableSlot(
            $data['parking_location_id'],
            $vehicle->vehicle_type,
            $startTime,
            $endTime
        );
    }

    /**
     * Validate booking data.
     */
    private function validateBookingData(array $data): void
    {
        $startTime = Carbon::parse($data['start_time']);
        $endTime = Carbon::parse($data['end_time']);

        // Check if start time is in the future
        if ($startTime->isPast()) {
            throw new \Exception('Booking start time must be in the future');
        }

        // Check if end time is after start time
        if ($endTime->isBefore($startTime)) {
            throw new \Exception('End time must be after start time');
        }

        // Check if user has an active booking for the same vehicle
        $hasActiveBooking = $this->bookingRepository->hasActiveBooking(
            $data['user_id'],
            $data['vehicle_id']
        );

        if ($hasActiveBooking) {
            throw new \Exception('Vehicle already has an active booking');
        }
    }

    /**
     * Get active booking count for user.
     */
    public function getActiveBookingCount(int $userId): int
    {
        return Booking::where('user_id', $userId)
            ->where('status', 'active')
            ->count();
    }

    /**
     * Get total booking count for user.
     */
    public function getUserBookingCount(int $userId): int
    {
        return Booking::where('user_id', $userId)->count();
    }

    /**
     * Get user's recent bookings.
     */
    public function getUserRecentBookings(int $userId, int $limit = 5)
    {
        return Booking::with(['parking_slot.area'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get user's total parking hours.
     */
    public function getUserTotalParkingHours(int $userId): float
    {
        $bookings = Booking::where('user_id', $userId)
            ->where('status', 'completed')
            ->get(['start_time', 'end_time']);

        $totalMinutes = 0;
        foreach ($bookings as $booking) {
            $startTime = Carbon::parse($booking->start_time);
            $endTime = Carbon::parse($booking->end_time);
            $totalMinutes += $startTime->diffInMinutes($endTime);
        }

        return round($totalMinutes / 60, 1);
    }
}
