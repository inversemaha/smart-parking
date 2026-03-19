<?php

namespace App\Domains\User\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Domains\Booking\Models\Booking;
use App\Domains\Parking\Models\ParkingLocation;
use App\Domains\Parking\Models\ParkingSlot;
use App\Domains\Vehicle\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Get user's bookings.
     */
    public function index(Request $request): JsonResponse
    {
        $query = $request->user()->bookings()
            ->with(['vehicle', 'parkingSlot.parkingLocation', 'payment']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('start_time', [$request->start_date, $request->end_date]);
        }

        $bookings = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }

    /**
     * Create a new booking.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'parking_slot_id' => 'required|exists:parking_slots,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'duration_type' => 'sometimes|in:hourly,daily,monthly'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Validate vehicle ownership
            $vehicle = Vehicle::where('id', $request->vehicle_id)
                ->where('user_id', $request->user()->id)
                ->first();

            if (!$vehicle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle not found or not owned by user'
                ], 404);
            }

            if ($vehicle->verification_status !== 'verified') {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle must be verified before booking'
                ], 400);
            }

            // Check slot availability
            $slot = ParkingSlot::with('parkingLocation')->find($request->parking_slot_id);

            if (!$slot || !$slot->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parking slot not available'
                ], 400);
            }

            if ($this->isSlotOccupied($slot->id, $request->start_time, $request->end_time)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parking slot is not available for the selected time'
                ], 400);
            }

            // Calculate cost
            $cost = $this->calculateBookingCost(
                $slot,
                $request->start_time,
                $request->end_time,
                $request->input('duration_type')
            );

            // Create booking
            $booking = Booking::create([
                'user_id' => $request->user()->id,
                'vehicle_id' => $request->vehicle_id,
                'parking_location_id' => $slot->parking_location_id,
                'parking_slot_id' => $request->parking_slot_id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'duration_hours' => max(1, Carbon::parse($request->start_time)->diffInHours(Carbon::parse($request->end_time))),
                'hourly_rate' => $slot->parkingLocation?->hourly_rate ?? 0,
                'total_amount' => $cost,
                'status' => 'pending',
                'booking_number' => $this->generateBookingReference(),
                'payment_status' => 'pending',
            ]);

            $booking->load(['vehicle', 'parkingSlot.parkingLocation']);

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'data' => $booking
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific booking.
     */
    public function show(Request $request, Booking $booking): JsonResponse
    {
        // Check ownership
        if ($booking->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $booking->load(['vehicle', 'parkingSlot.parkingLocation', 'payment']);

        return response()->json([
            'success' => true,
            'data' => $booking
        ]);
    }

    /**
     * Cancel booking.
     */
    public function cancel(Request $request, Booking $booking): JsonResponse
    {
        // Check ownership
        if ($booking->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel this booking'
            ], 400);
        }

        // Check if booking can be cancelled (e.g., 1 hour before start time)
        $startTime = Carbon::parse($booking->start_time);
        if ($startTime->diffInHours(now()) < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel booking less than 1 hour before start time'
            ], 400);
        }

        try {
            $booking->update(['status' => 'cancelled']);

            // Process refund if payment was made
            if ($booking->payment && $booking->payment->status === 'paid') {
                // Implement refund logic here
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking cancellation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Extend booking.
     */
    public function extend(Request $request, Booking $booking): JsonResponse
    {
        // Check ownership
        if ($booking->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($booking->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Can only extend active bookings'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'new_end_time' => 'required|date|after:' . $booking->end_time
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check if slot is available for extension
            if ($this->isSlotOccupied($booking->parking_slot_id, $booking->end_time, $request->new_end_time, $booking->id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parking slot is not available for extension'
                ], 400);
            }

            // Calculate additional cost
            $additionalCost = $this->calculateBookingCost(
                $booking->parkingSlot,
                $booking->end_time,
                $request->new_end_time
            );

            $booking->update([
                'end_time' => $request->new_end_time,
                'total_amount' => $booking->total_amount + $additionalCost,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking extended successfully',
                'data' => [
                    'booking' => $booking,
                    'additional_cost' => $additionalCost
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking extension failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available parking locations.
     */
    public function getAvailableLocations(Request $request): JsonResponse
    {
        $locations = ParkingLocation::with(['parkingSlots' => function ($query) {
                $query->where('is_active', true);
            }])
            ->where('is_active', true)
            ->get()
            ->map(function ($area) {
                return [
                    'id' => $area->id,
                    'name' => $area->name,
                    'location' => $area->address,
                    'total_slots' => $area->parkingSlots->count(),
                    'available_slots' => $area->parkingSlots->where('status', 'available')->count(),
                    'hourly_rate' => $area->hourly_rate,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $locations
        ]);
    }

    /**
     * Get available parking slots.
     */
    public function getAvailableSlots(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'parking_location_id' => 'sometimes|exists:parking_locations,id',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time',
            'vehicle_type' => 'sometimes|in:car,motorcycle,bicycle'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = ParkingSlot::with('parkingLocation')
            ->where('is_active', true);

        if ($request->has('parking_location_id')) {
            $query->where('parking_location_id', $request->parking_location_id);
        }

        if ($request->has('vehicle_type')) {
            $query->whereJsonContains('vehicle_types', $request->vehicle_type);
        }

        $slots = $query->get();

        // Filter by availability if time range provided
        if ($request->has('start_time') && $request->has('end_time')) {
            $slots = $slots->filter(function ($slot) use ($request) {
                return !$this->isSlotOccupied($slot->id, $request->start_time, $request->end_time);
            });
        }

        return response()->json([
            'success' => true,
            'data' => $slots->values()
        ]);
    }

    /**
     * Get parking rates.
     */
    public function getParkingRates(Request $request): JsonResponse
    {
        $areas = ParkingLocation::select('id', 'name', 'hourly_rate')
            ->where('is_active', true)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $areas
        ]);
    }

    /**
     * Calculate booking cost.
     */
    public function calculateCost(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'parking_slot_id' => 'required|exists:parking_slots,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'duration_type' => 'sometimes|in:hourly,daily,monthly'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $slot = ParkingSlot::with('parkingLocation')->find($request->parking_slot_id);
            $durationType = $request->input('duration_type') ?: $this->inferDurationType($request->start_time, $request->end_time);
            $cost = $this->calculateBookingCost($slot, $request->start_time, $request->end_time, $durationType);

            return response()->json([
                'success' => true,
                'data' => [
                    'total_amount' => $cost,
                    'rate_type' => $durationType,
                    'parking_location' => $slot->parkingLocation?->name
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cost calculation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update booking details.
     */
    public function update(Request $request, Booking $booking): JsonResponse
    {
        if ($booking->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'start_time' => ['sometimes', 'date', 'after:now'],
            'end_time' => ['sometimes', 'date', 'after:start_time'],
            'notes' => ['sometimes', 'nullable', 'string', 'max:1000'],
        ]);

        $booking->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Booking updated successfully',
            'data' => $booking->fresh(),
        ]);
    }

    /**
     * Confirm a booking.
     */
    public function confirm(Request $request, Booking $booking): JsonResponse
    {
        if ($booking->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if (!in_array($booking->status, ['pending', 'confirmed'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Booking cannot be confirmed'
            ], 422);
        }

        $booking->update([
            'status' => 'confirmed',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking confirmed successfully',
            'data' => $booking->fresh(),
        ]);
    }

    /**
     * Get booking history for authenticated user.
     */
    public function getBookingHistory(Request $request): JsonResponse
    {
        $history = $request->user()->bookings()
            ->with(['vehicle', 'parkingSlot'])
            ->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $history,
        ]);
    }

    // Helper methods

    private function isSlotOccupied(int $slotId, string $startTime, string $endTime, ?int $excludeBookingId = null): bool
    {
        $query = Booking::where('parking_slot_id', $slotId)
            ->whereIn('status', ['confirmed', 'active'])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q2) use ($startTime, $endTime) {
                        $q2->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            });

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->exists();
    }

    private function calculateBookingCost(ParkingSlot $slot, string $startTime, string $endTime, ?string $durationType = null): float
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        $area = $slot->parkingLocation;
        $durationType = $durationType ?: $this->inferDurationType($startTime, $endTime);

        switch ($durationType) {
            case 'hourly':
                $hours = $start->diffInHours($end);
                return $hours * $area->hourly_rate;

            case 'daily':
                $days = $start->diffInDays($end);
                if ($days < 1) $days = 1;
                return $days * $area->hourly_rate * 24;

            case 'monthly':
                $months = $start->diffInMonths($end);
                if ($months < 1) $months = 1;
                return $months * $area->hourly_rate * 24 * 30;

            default:
                throw new \InvalidArgumentException('Invalid duration type');
        }
    }

    private function generateBookingReference(): string
    {
        return 'BK' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    private function inferDurationType(string $startTime, string $endTime): string
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        if ($start->diffInMonths($end) >= 1) {
            return 'monthly';
        }

        if ($start->diffInDays($end) >= 1) {
            return 'daily';
        }

        return 'hourly';
    }
}
