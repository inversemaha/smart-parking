<?php

namespace App\Domains\Vehicle\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Booking\Models\Booking;
use App\Domains\Parking\Models\ParkingLocation;
use App\Domains\Booking\Services\BookingService;
use App\Domains\Booking\Repositories\BookingRepository;
use App\Domains\Vehicle\Repositories\VehicleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class BookingController extends Controller
{
    protected $bookingService;
    protected $bookingRepository;
    protected $vehicleRepository;

    public function __construct(
        BookingService $bookingService,
        BookingRepository $bookingRepository,
        VehicleRepository $vehicleRepository
    ) {
        $this->middleware('auth:sanctum');
        $this->bookingService = $bookingService;
        $this->bookingRepository = $bookingRepository;
        $this->vehicleRepository = $vehicleRepository;
    }

    /**
     * Display a listing of user's bookings.
     */
    public function index(Request $request)
    {
        try {
            $filters = $request->only([
                'status',
                'payment_status',
                'vehicle_id',
                'date_from',
                'date_to',
                'per_page'
            ]);

            $bookings = $this->bookingRepository->getUserBookings(auth()->id(), $filters);

            return response()->json([
                'success' => true,
                'message' => 'Bookings retrieved successfully',
                'data' => $bookings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve bookings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created booking.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'parking_location_id' => 'required|exists:parking_locations,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Verify vehicle ownership
            $vehicle = $this->vehicleRepository->find($request->vehicle_id);

            if (!$vehicle || $vehicle->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid vehicle selection'
                ], 422);
            }

            if ($vehicle->verification_status !== 'verified') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only verified vehicles can be used for booking'
                ], 422);
            }

            $bookingData = array_merge($validator->validated(), [
                'user_id' => auth()->id(),
            ]);

            $booking = $this->bookingService->createBooking($bookingData);

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'data' => $booking->load(['vehicle', 'parkingSlot.parkingLocation'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        try {
            Gate::authorize('view', $booking);

            $booking->load([
                'vehicle',
                'parkingSlot.parkingLocation',
                'payments.sslcommerzLogs',
                'slotHistories',
                'vehicleEntries',
                'vehicleExits',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking details retrieved successfully',
                'data' => $booking
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve booking: ' . $e->getMessage()
            ], 403);
        }
    }

    /**
     * Update the specified booking.
     */
    public function update(Request $request, Booking $booking)
    {
        try {
            Gate::authorize('update', $booking);

            if (!$booking->canBeModified()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This booking cannot be modified'
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'start_time' => 'required|date|after:now',
                'end_time' => 'required|date|after:start_time',
                'notes' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Calculate new duration and amount
            $startTime = \Carbon\Carbon::parse($request->start_time);
            $endTime = \Carbon\Carbon::parse($request->end_time);
            $durationHours = $startTime->diffInHours($endTime, false);

            $data = array_merge($validator->validated(), [
                'duration_hours' => $durationHours,
                'total_amount' => $durationHours * $booking->hourly_rate,
            ]);

            $updatedBooking = $this->bookingRepository->update($booking, $data);

            return response()->json([
                'success' => true,
                'message' => 'Booking updated successfully',
                'data' => $updatedBooking
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update booking: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel the specified booking.
     */
    public function cancel(Request $request, Booking $booking)
    {
        try {
            Gate::authorize('delete', $booking);

            $validator = Validator::make($request->all(), [
                'reason' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $result = $this->bookingService->cancelBooking(
                $booking,
                $request->reason ?? 'Cancelled by user'
            );

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Booking cancelled successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to cancel booking'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cancellation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirm booking and proceed to payment.
     */
    public function confirm(Booking $booking)
    {
        try {
            Gate::authorize('update', $booking);

            if ($booking->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending bookings can be confirmed'
                ], 422);
            }

            $result = $this->bookingService->confirmBooking($booking);

            if ($result) {
                $payment = $booking->payments()->latest()->first();

                return response()->json([
                    'success' => true,
                    'message' => 'Booking confirmed successfully',
                    'data' => [
                        'booking' => $booking->fresh(),
                        'payment' => $payment,
                        'payment_url' => $payment->payment_url,
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to confirm booking'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Confirmation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available slots for a location and time.
     */
    public function checkAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parking_location_id' => 'required|exists:parking_locations,id',
            'vehicle_type' => 'required|in:car,motorcycle,bus,truck',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $startTime = \Carbon\Carbon::parse($request->start_time);
            $endTime = \Carbon\Carbon::parse($request->end_time);

            $availableSlot = app(\App\Domains\Parking\Repositories\ParkingSlotRepository::class)
                ->findAvailableSlot(
                    $request->parking_location_id,
                    $request->vehicle_type,
                    $startTime,
                    $endTime
                );

            $availableCount = $availableSlot ? 1 : 0;
            $location = ParkingLocation::find($request->parking_location_id);
            $hourlyRate = $location->hourly_rate;
            $durationHours = $startTime->diffInHours($endTime, false);
            $totalAmount = $durationHours * $hourlyRate;

            return response()->json([
                'success' => true,
                'message' => 'Availability checked successfully',
                'data' => [
                    'available' => $availableSlot !== null,
                    'available_count' => $availableCount,
                    'location' => $location,
                    'hourly_rate' => $hourlyRate,
                    'duration_hours' => $durationHours,
                    'total_amount' => $totalAmount,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check availability: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get parking locations.
     */
    public function locations()
    {
        try {
            $locations = ParkingLocation::active()
                ->with(['parkingSlots' => function ($query) {
                    $query->where('status', 'available');
                }])
                ->get();

            // Add availability info for each location
            $locations->each(function ($location) {
                $availableSlots = app(\App\Domains\Parking\Repositories\ParkingSlotRepository::class)
                    ->getAvailableSlotsByType($location->id);

                $location->available_slots = $availableSlots;
            });

            return response()->json([
                'success' => true,
                'message' => 'Parking locations retrieved successfully',
                'data' => $locations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve locations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's active bookings.
     */
    public function activeBookings()
    {
        try {
            $activeBookings = $this->bookingRepository->getUserBookings(auth()->id(), [
                'status' => 'checked_in',
                'per_page' => 100
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Active bookings retrieved successfully',
                'data' => $activeBookings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve active bookings: ' . $e->getMessage()
            ], 500);
        }
    }
}
