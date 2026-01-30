<?php

namespace App\Domains\Booking\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Booking\Services\BookingService;
use App\Domains\Booking\Models\Booking;
use App\Domains\Parking\Services\ParkingLocationService;
use App\Domains\Vehicle\Services\VehicleService;
use App\Domains\Payment\Services\PaymentService;
use App\Shared\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VisitorBookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
        protected ParkingLocationService $parkingLocationService,
        protected VehicleService $vehicleService,
        protected PaymentService $paymentService,
        protected NotificationService $notificationService
    ) {
        $this->middleware(['auth', 'verified']);
        $this->middleware('throttle:bookings')->only(['store', 'apiStore']);
    }

    /**
     * List visitor bookings
     */
    public function index(Request $request): View
    {
        $filters = [
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location_id' => $request->location_id,
        ];

        $bookings = $this->bookingService->getUserBookings(auth()->id(), $filters);
        $locations = $this->parkingLocationService->getAllActiveLocations(['simple' => true]);

        return view('visitor.bookings.index', compact('bookings', 'locations', 'filters'));
    }

    /**
     * Show create booking form
     */
    public function create(Request $request): View
    {
        $vehicles = $this->vehicleService->getUserVehicles(auth()->id());
        $locations = $this->parkingLocationService->getAllActiveLocations(['simple' => true]);

        // Pre-fill from query params if coming from location page
        $preselected = [
            'location_id' => $request->location_id,
            'start_date' => $request->start_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'vehicle_type' => $request->vehicle_type,
        ];

        return view('visitor.bookings.create', compact('vehicles', 'locations', 'preselected'));
    }

    /**
     * Store new booking
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'location_id' => ['required', 'exists:parking_locations,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'slot_type_id' => ['required', 'exists:slot_types,id'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'special_requests' => ['nullable', 'string', 'max:500'],
        ]);

        // Verify vehicle belongs to user
        $vehicle = $this->vehicleService->getUserVehicle(auth()->id(), $request->vehicle_id);
        if (!$vehicle) {
            return back()->withErrors(['vehicle_id' => __('vehicles.not_found')]);
        }

        $startDateTime = Carbon::parse($request->start_date . ' ' . $request->start_time);
        $endDateTime = Carbon::parse($request->start_date . ' ' . $request->end_time);

        DB::beginTransaction();
        try {
            // Validate booking
            $validationResult = $this->bookingService->validateBooking([
                'location_id' => $request->location_id,
                'vehicle_id' => $request->vehicle_id,
                'slot_type_id' => $request->slot_type_id,
                'start_datetime' => $startDateTime,
                'end_datetime' => $endDateTime,
                'user_id' => auth()->id(),
            ]);

            if (!$validationResult['is_valid']) {
                return back()->withErrors(['booking' => $validationResult['message']])
                            ->withInput();
            }

            // Create booking
            $booking = $this->bookingService->createBooking([
                'user_id' => auth()->id(),
                'location_id' => $request->location_id,
                'vehicle_id' => $request->vehicle_id,
                'slot_type_id' => $request->slot_type_id,
                'start_datetime' => $startDateTime,
                'end_datetime' => $endDateTime,
                'special_requests' => $request->special_requests,
                'booking_cost' => $validationResult['estimated_cost'],
                'status' => 'pending',
            ]);

            DB::commit();

            // Send booking confirmation notification
            $this->notificationService->sendBookingConfirmation($booking);

            return redirect()->route('visitor.payments.create', $booking)
                           ->with('success', __('bookings.created_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['booking' => __('bookings.creation_failed')])
                        ->withInput();
        }
    }

    /**
     * Show booking details
     */
    public function show(Booking $booking): View
    {
        $this->authorize('view', $booking);

        $booking->load(['location', 'vehicle', 'slotType', 'payments', 'slot']);

        return view('visitor.bookings.show', compact('booking'));
    }

    /**
     * Cancel booking
     */
    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorize('cancel', $booking);

        $request->validate([
            'cancellation_reason' => ['nullable', 'string', 'max:500'],
        ]);

        DB::beginTransaction();
        try {
            $result = $this->bookingService->cancelBooking(
                $booking->id,
                $request->cancellation_reason,
                auth()->id()
            );

            if (!$result['success']) {
                return back()->withErrors(['booking' => $result['message']]);
            }

            DB::commit();

            // Send cancellation notification
            $this->notificationService->sendBookingCancellation($booking);

            return redirect()->route('visitor.bookings.index')
                           ->with('success', __('bookings.cancelled_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['booking' => __('bookings.cancellation_failed')]);
        }
    }

    /**
     * Extend booking
     */
    public function extend(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorize('extend', $booking);

        $request->validate([
            'new_end_time' => ['required', 'date_format:H:i', 'after:' . $booking->end_datetime->format('H:i')],
        ]);

        $newEndDateTime = Carbon::parse($booking->start_datetime->format('Y-m-d') . ' ' . $request->new_end_time);

        DB::beginTransaction();
        try {
            $result = $this->bookingService->extendBooking(
                $booking->id,
                $newEndDateTime,
                auth()->id()
            );

            if (!$result['success']) {
                return back()->withErrors(['booking' => $result['message']]);
            }

            DB::commit();

            // Send extension notification
            $this->notificationService->sendBookingExtension($booking);

            return redirect()->route('visitor.payments.create', $booking)
                           ->with('success', __('bookings.extended_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['booking' => __('bookings.extension_failed')]);
        }
    }

    /**
     * Show booking receipt
     */
    public function receipt(Booking $booking): View
    {
        $this->authorize('view', $booking);

        $booking->load(['location', 'vehicle', 'slotType', 'payments', 'user']);

        return view('visitor.bookings.receipt', compact('booking'));
    }

    // AJAX Methods

    /**
     * Get available slots
     */
    public function getAvailableSlots(Request $request): JsonResponse
    {
        $request->validate([
            'location_id' => ['required', 'exists:parking_locations,id'],
            'start_datetime' => ['required', 'date', 'after_or_equal:now'],
            'end_datetime' => ['required', 'date', 'after:start_datetime'],
            'vehicle_type' => ['required', 'in:car,motorcycle,cng,bus,truck'],
        ]);

        $startDateTime = Carbon::parse($request->start_datetime);
        $endDateTime = Carbon::parse($request->end_datetime);

        $availability = $this->parkingLocationService->getSlotAvailability(
            $request->location_id,
            $startDateTime,
            $endDateTime,
            $request->vehicle_type
        );

        return response()->json([
            'success' => true,
            'data' => $availability
        ]);
    }

    /**
     * Calculate booking cost
     */
    public function calculateCost(Request $request): JsonResponse
    {
        $request->validate([
            'location_id' => ['required', 'exists:parking_locations,id'],
            'slot_type_id' => ['required', 'exists:slot_types,id'],
            'start_datetime' => ['required', 'date', 'after_or_equal:now'],
            'end_datetime' => ['required', 'date', 'after:start_datetime'],
        ]);

        $cost = $this->bookingService->calculateBookingCost(
            $request->location_id,
            $request->slot_type_id,
            Carbon::parse($request->start_datetime),
            Carbon::parse($request->end_datetime)
        );

        return response()->json([
            'success' => true,
            'data' => $cost
        ]);
    }

    /**
     * Validate booking before creation
     */
    public function validateBooking(Request $request): JsonResponse
    {
        $request->validate([
            'location_id' => ['required', 'exists:parking_locations,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'slot_type_id' => ['required', 'exists:slot_types,id'],
            'start_datetime' => ['required', 'date', 'after_or_equal:now'],
            'end_datetime' => ['required', 'date', 'after:start_datetime'],
        ]);

        $validationResult = $this->bookingService->validateBooking([
            'location_id' => $request->location_id,
            'vehicle_id' => $request->vehicle_id,
            'slot_type_id' => $request->slot_type_id,
            'start_datetime' => Carbon::parse($request->start_datetime),
            'end_datetime' => Carbon::parse($request->end_datetime),
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => $validationResult['is_valid'],
            'message' => $validationResult['message'],
            'data' => $validationResult
        ]);
    }

    // API Methods

    /**
     * API: List bookings
     */
    public function apiIndex(Request $request): JsonResponse
    {
        $filters = [
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location_id' => $request->location_id,
            'per_page' => $request->per_page ?? 20,
        ];

        $bookings = $this->bookingService->getUserBookings(auth()->id(), $filters);

        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }

    /**
     * API: Create booking
     */
    public function apiStore(Request $request): JsonResponse
    {
        $request->validate([
            'location_id' => ['required', 'exists:parking_locations,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'slot_type_id' => ['required', 'exists:slot_types,id'],
            'start_datetime' => ['required', 'date', 'after_or_equal:now'],
            'end_datetime' => ['required', 'date', 'after:start_datetime'],
            'special_requests' => ['nullable', 'string', 'max:500'],
        ]);

        // Verify vehicle belongs to user
        $vehicle = $this->vehicleService->getUserVehicle(auth()->id(), $request->vehicle_id);
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => __('vehicles.not_found')
            ], 404);
        }

        $startDateTime = Carbon::parse($request->start_datetime);
        $endDateTime = Carbon::parse($request->end_datetime);

        DB::beginTransaction();
        try {
            // Validate booking
            $validationResult = $this->bookingService->validateBooking([
                'location_id' => $request->location_id,
                'vehicle_id' => $request->vehicle_id,
                'slot_type_id' => $request->slot_type_id,
                'start_datetime' => $startDateTime,
                'end_datetime' => $endDateTime,
                'user_id' => auth()->id(),
            ]);

            if (!$validationResult['is_valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $validationResult['message']
                ], 422);
            }

            // Create booking
            $booking = $this->bookingService->createBooking([
                'user_id' => auth()->id(),
                'location_id' => $request->location_id,
                'vehicle_id' => $request->vehicle_id,
                'slot_type_id' => $request->slot_type_id,
                'start_datetime' => $startDateTime,
                'end_datetime' => $endDateTime,
                'special_requests' => $request->special_requests,
                'booking_cost' => $validationResult['estimated_cost'],
                'status' => 'pending',
            ]);

            DB::commit();

            // Send booking confirmation notification
            $this->notificationService->sendBookingConfirmation($booking);

            return response()->json([
                'success' => true,
                'data' => $booking->load(['location', 'vehicle', 'slotType']),
                'message' => __('bookings.created_successfully')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => __('bookings.creation_failed'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Show booking
     */
    public function apiShow(Booking $booking): JsonResponse
    {
        $this->authorize('view', $booking);

        $booking->load(['location', 'vehicle', 'slotType', 'payments', 'slot', 'user']);

        return response()->json([
            'success' => true,
            'data' => $booking
        ]);
    }

    /**
     * API: Update booking (for extensions/modifications)
     */
    public function apiUpdate(Request $request, Booking $booking): JsonResponse
    {
        $this->authorize('update', $booking);

        $request->validate([
            'action' => ['required', 'in:cancel,extend'],
            'new_end_datetime' => ['required_if:action,extend', 'date', 'after:end_datetime'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        DB::beginTransaction();
        try {
            if ($request->action === 'cancel') {
                $result = $this->bookingService->cancelBooking(
                    $booking->id,
                    $request->reason,
                    auth()->id()
                );

                if ($result['success']) {
                    $this->notificationService->sendBookingCancellation($booking);
                }
            } else if ($request->action === 'extend') {
                $result = $this->bookingService->extendBooking(
                    $booking->id,
                    Carbon::parse($request->new_end_datetime),
                    auth()->id()
                );

                if ($result['success']) {
                    $this->notificationService->sendBookingExtension($booking);
                }
            }

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 422);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $booking->fresh(['location', 'vehicle', 'slotType']),
                'message' => $result['message']
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => __('bookings.operation_failed'),
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
