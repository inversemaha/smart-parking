<?php

namespace App\Domains\User\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ParkingArea;
use App\Domains\Booking\Services\BookingService;
use App\Domains\Vehicle\Services\VehicleService;
use App\Domains\Parking\Services\ParkingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class BookingController extends Controller
{
    protected $bookingService;
    protected $vehicleService;
    protected $parkingService;

    public function __construct(
        BookingService $bookingService,
        VehicleService $vehicleService,
        ParkingService $parkingService
    ) {
        $this->middleware('auth');
        $this->bookingService = $bookingService;
        $this->vehicleService = $vehicleService;
        $this->parkingService = $parkingService;
    }

    /**
     * Display a listing of user's bookings.
     */
    public function index(Request $request)
    {
        $filters = $request->only([
            'status',
            'payment_status',
            'vehicle_id',
            'date_from',
            'date_to',
            'per_page'
        ]);

        $bookings = $this->bookingService->getUserBookings(auth()->id(), $filters);

        // Get user vehicles for filter
        $userVehicles = $this->vehicleService->getUserVehicles(auth()->id(), [
            'status' => 'active',
            'per_page' => 100
        ]);

        return view('user.bookings.index', compact('bookings', 'userVehicles', 'filters'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create(Request $request)
    {
        // Get verified vehicles for user
        $userVehicles = $this->vehicleService->getUserVehicles(auth()->id(), [
            'verification_status' => 'verified',
            'status' => 'active',
            'per_page' => 100
        ]);

        if ($userVehicles->isEmpty()) {
            return redirect()->route('user.vehicles.create')
                           ->with('error', __('You need at least one verified vehicle to make a booking.'));
        }

        // Get available parking locations
        $parkingLocations = ParkingLocation::active()
            ->with(['parkingSlots' => function ($query) {
                $query->where('status', 'available');
            }])
            ->get();

        $selectedLocation = $request->get('location_id');
        $selectedVehicle = $request->get('vehicle_id');

        return view('user.bookings.create', compact(
            'userVehicles',
            'parkingLocations',
            'selectedLocation',
            'selectedVehicle'
        ));
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
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        // Verify vehicle ownership
        $vehicle = $this->vehicleService->findVehicle($request->vehicle_id);

        if (!$vehicle || $vehicle->user_id !== auth()->id()) {
            return redirect()->back()
                           ->with('error', __('Invalid vehicle selection.'))
                           ->withInput();
        }

        if ($vehicle->verification_status !== 'verified') {
            return redirect()->back()
                           ->with('error', __('Only verified vehicles can be used for booking.'))
                           ->withInput();
        }

        try {
            $bookingData = array_merge($validator->validated(), [
                'user_id' => auth()->id(),
            ]);

            $booking = $this->bookingService->createBooking($bookingData);

            return redirect()->route('user.bookings.show', $booking)
                           ->with('success', __('Booking created successfully. Please proceed with payment.'));
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', __('Failed to create booking: :message', ['message' => $e->getMessage()]))
                           ->withInput();
        }
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        Gate::authorize('view', $booking);

        $booking->load([
            'vehicle',
            'parkingSlot.parkingLocation',
            'payments.sslcommerzLogs',
            'slotHistories',
            'vehicleEntries',
            'vehicleExits',
        ]);

        return view('user.bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified booking.
     */
    public function edit(Booking $booking)
    {
        Gate::authorize('update', $booking);

        if (!$booking->canBeModified()) {
            return redirect()->route('user.bookings.show', $booking)
                           ->with('error', __('This booking cannot be modified.'));
        }

        return view('user.bookings.edit', compact('booking'));
    }

    /**
     * Update the specified booking.
     */
    public function update(Request $request, Booking $booking)
    {
        Gate::authorize('update', $booking);

        if (!$booking->canBeModified()) {
            return redirect()->back()
                           ->with('error', __('This booking cannot be modified.'));
        }

        $validator = Validator::make($request->all(), [
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            // Calculate new duration and amount
            $startTime = \Carbon\Carbon::parse($request->start_time);
            $endTime = \Carbon\Carbon::parse($request->end_time);
            $durationHours = $startTime->diffInHours($endTime, false);

            $data = array_merge($validator->validated(), [
                'duration_hours' => $durationHours,
                'total_amount' => $durationHours * $booking->hourly_rate,
            ]);

            $this->bookingService->updateBooking($booking, $data);

            return redirect()->route('user.bookings.show', $booking)
                           ->with('success', __('Booking updated successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', __('Failed to update booking: :message', ['message' => $e->getMessage()]))
                           ->withInput();
        }
    }

    /**
     * Cancel the specified booking.
     */
    public function cancel(Request $request, Booking $booking)
    {
        Gate::authorize('delete', $booking);

        $validator = Validator::make($request->all(), [
            'reason' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        try {
            $result = $this->bookingService->cancelBooking(
                $booking,
                $request->reason ?? 'Cancelled by user'
            );

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => __('Booking cancelled successfully.'),
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('Failed to cancel booking.'),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Cancellation failed: :message', ['message' => $e->getMessage()]),
            ]);
        }
    }

    /**
     * Confirm booking and proceed to payment.
     */
    public function confirm(Booking $booking)
    {
        Gate::authorize('update', $booking);

        if ($booking->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => __('Only pending bookings can be confirmed.'),
            ]);
        }

        try {
            $result = $this->bookingService->confirmBooking($booking);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => __('Booking confirmed. Redirecting to payment...'),
                    'redirect' => route('user.payments.process', $booking->payments()->latest()->first()),
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('Failed to confirm booking.'),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Confirmation failed: :message', ['message' => $e->getMessage()]),
            ]);
        }
    }

    /**
     * Extend booking duration.
     */
    public function extend(Request $request, Booking $booking)
    {
        Gate::authorize('update', $booking);

        if (!$booking->canBeExtended()) {
            return response()->json([
                'success' => false,
                'message' => __('This booking cannot be extended.'),
            ]);
        }

        $validator = Validator::make($request->all(), [
            'additional_hours' => 'required|integer|min:1|max:24',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        try {
            $result = $this->bookingService->extendBooking($booking, $request->additional_hours);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => __('Booking extended successfully.'),
                    'new_end_time' => $booking->fresh()->end_time->format('Y-m-d H:i:s'),
                    'additional_cost' => $request->additional_hours * $booking->hourly_rate,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('Failed to extend booking.'),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Extension failed: :message', ['message' => $e->getMessage()]),
            ]);
        }
    }

    /**
     * Calculate booking cost.
     */
    public function calculateCost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parking_location_id' => 'required|exists:parking_locations,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        try {
            $costData = $this->bookingService->calculateBookingCost(
                $request->parking_location_id,
                $request->start_time,
                $request->end_time
            );

            return response()->json([
                'success' => true,
                'data' => $costData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Failed to calculate cost: :message', ['message' => $e->getMessage()]),
            ]);
        }
    }

    /**
     * Get available slots for a location and time.
     */
    public function getAvailableSlots(Request $request)
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
                'message' => $validator->errors()->first(),
            ]);
        }

        try {
            $startTime = \Carbon\Carbon::parse($request->start_time);
            $endTime = \Carbon\Carbon::parse($request->end_time);

            $availableSlot = $this->parkingService->findAvailableSlot(
                $request->parking_location_id,
                $request->vehicle_type,
                $startTime,
                $endTime
            );

            $availableCount = $availableSlot ? 1 : 0;
            $hourlyRate = ParkingLocation::find($request->parking_location_id)->hourly_rate;
            $durationHours = $startTime->diffInHours($endTime, false);
            $totalAmount = $durationHours * $hourlyRate;

            return response()->json([
                'success' => true,
                'available' => $availableSlot !== null,
                'available_count' => $availableCount,
                'hourly_rate' => $hourlyRate,
                'duration_hours' => $durationHours,
                'total_amount' => $totalAmount,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Failed to check availability: :message', ['message' => $e->getMessage()]),
            ]);
        }
    }
}
