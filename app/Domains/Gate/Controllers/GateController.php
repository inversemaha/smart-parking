<?php

namespace App\Domains\Gate\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Booking\Models\Booking;
use App\Domains\Booking\Models\VehicleEntry;
use App\Domains\Booking\Models\VehicleExit;
use App\Domains\Parking\Models\ParkingSlot;
use App\Services\RedisCacheService;
use App\Jobs\SendNotification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Gate Operations Controller
 *
 * Handles vehicle entry and exit operations at parking gates.
 */
class GateController extends Controller
{
    protected RedisCacheService $cacheService;

    public function __construct(RedisCacheService $cacheService)
    {
        $this->middleware(['auth', 'permission:operate.gates']);
        $this->cacheService = $cacheService;
    }

    /**
     * Process vehicle entry.
     */
    public function vehicleEntry(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'booking_id' => 'required|exists:bookings,id',
                'vehicle_number_plate' => 'required|string',
                'gate_number' => 'required|string',
                'entry_method' => 'nullable|string|in:manual,automatic,qr_code',
                'notes' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Get booking with related data
            $booking = Booking::with(['vehicle', 'parkingSlot', 'user'])
                ->find($request->booking_id);

            // Verify booking status and vehicle
            if ($booking->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking is not active'
                ], 400);
            }

            if ($booking->vehicle->number_plate !== $request->vehicle_number_plate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle number plate does not match booking'
                ], 400);
            }

            // Check if vehicle has already entered
            $existingEntry = VehicleEntry::where('booking_id', $booking->id)->first();
            if ($existingEntry) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle has already entered'
                ], 400);
            }

            // Create vehicle entry record
            $entry = VehicleEntry::create([
                'vehicle_id' => $booking->vehicle_id,
                'booking_id' => $booking->id,
                'parking_location_id' => $booking->parking_location_id,
                'parking_slot_id' => $booking->parking_slot_id,
                'gate_number' => $request->gate_number,
                'entry_time' => Carbon::now(),
                'recorded_by' => auth()->id(),
                'entry_method' => $request->entry_method ?? 'manual',
                'is_valid_entry' => true,
                'notes' => $request->notes,
            ]);

            // Update booking status
            $booking->update([
                'status' => 'occupied',
                'entry_time' => Carbon::now(),
            ]);

            // Update parking slot status
            $booking->parkingSlot->update([
                'status' => 'occupied',
                'occupied_at' => Carbon::now(),
                'current_vehicle_id' => $booking->vehicle_id,
            ]);

            // Log slot history
            $booking->parkingSlot->slotHistories()->create([
                'booking_id' => $booking->id,
                'status' => 'occupied',
                'action' => 'vehicle_entry',
                'performed_by' => auth()->id(),
                'notes' => "Vehicle {$booking->vehicle->number_plate} entered via gate {$request->gate_number}",
            ]);

            // Clear cache for this location
            $this->cacheService->invalidateSlotAvailability($booking->parkingSlot->parking_location_id);

            DB::commit();

            // Send notification to user
            SendNotification::dispatch(
                $booking->user,
                'vehicle_entry',
                [
                    'booking_id' => $booking->id,
                    'vehicle_plate' => $booking->vehicle->number_plate,
                    'location' => $booking->parkingSlot->parkingLocation->name,
                    'slot' => $booking->parkingSlot->slot_number,
                    'entry_time' => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );

            Log::info('Vehicle entry processed successfully', [
                'booking_id' => $booking->id,
                'vehicle_id' => $booking->vehicle_id,
                'gate_number' => $request->gate_number,
                'operator_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle entry processed successfully',
                'data' => [
                    'entry_id' => $entry->id,
                    'booking_id' => $booking->id,
                    'vehicle_plate' => $booking->vehicle->number_plate,
                    'slot_number' => $booking->parkingSlot->slot_number,
                    'entry_time' => $entry->entry_time->format('Y-m-d H:i:s'),
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing vehicle entry: ' . $e->getMessage(), [
                'booking_id' => $request->booking_id,
                'vehicle_number_plate' => $request->vehicle_number_plate,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process vehicle entry'
            ], 500);
        }
    }

    /**
     * Process vehicle exit.
     */
    public function vehicleExit(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'vehicle_number_plate' => 'required|string',
                'gate_number' => 'required|string',
                'exit_method' => 'nullable|string|in:manual,automatic,qr_code',
                'notes' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Find the vehicle and active entry
            $vehicle = Vehicle::where('number_plate', $request->vehicle_number_plate)->first();

            if (!$vehicle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle not found'
                ], 404);
            }

            // Get the active entry (without exit)
            $entry = VehicleEntry::with(['booking.parkingSlot.parkingLocation', 'booking.user'])
                ->where('vehicle_id', $vehicle->id)
                ->whereDoesntHave('vehicleExit')
                ->latest()
                ->first();

            if (!$entry) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active parking session found for this vehicle'
                ], 400);
            }

            $booking = $entry->booking;
            $exitTime = Carbon::now();
            $entryTime = $entry->entry_time;
            $parkingDuration = $exitTime->diffInMinutes($entryTime);

            // Calculate parking charges
            $charges = $this->calculateParkingCharges($booking->parkingSlot, $parkingDuration);

            // Create vehicle exit record
            $exit = VehicleExit::create([
                'vehicle_entry_id' => $entry->id,
                'vehicle_id' => $vehicle->id,
                'booking_id' => $booking->id,
                'parking_location_id' => $booking->parking_location_id,
                'gate_number' => $request->gate_number,
                'exit_time' => $exitTime,
                'recorded_by' => auth()->id(),
                'exit_method' => $request->exit_method ?? 'manual',
                'duration_minutes' => $parkingDuration,
                'calculated_fee' => $charges['total_amount'],
                'paid_amount' => $charges['total_amount'],
                'payment_status' => 'paid',
                'notes' => $request->notes,
            ]);

            // Update booking status
            $booking->update([
                'status' => 'completed',
                'exit_time' => $exitTime,
                'total_amount' => $charges['total_amount'],
                'parking_duration_minutes' => $parkingDuration,
            ]);

            // Update parking slot status
            $booking->parkingSlot->update([
                'status' => 'available',
                'occupied_at' => null,
                'current_vehicle_id' => null,
                'current_booking_id' => null,
            ]);

            // Log slot history
            $booking->parkingSlot->slotHistories()->create([
                'booking_id' => $booking->id,
                'status' => 'available',
                'action' => 'vehicle_exit',
                'performed_by' => auth()->id(),
                'notes' => "Vehicle {$vehicle->number_plate} exited via gate {$request->gate_number}. Duration: {$parkingDuration} minutes",
            ]);

            // Clear cache for this location
            $this->cacheService->invalidateSlotAvailability($booking->parkingSlot->parking_location_id);

            DB::commit();

            // Send notification to user
            SendNotification::dispatch(
                $booking->user,
                'vehicle_exit',
                [
                    'booking_id' => $booking->id,
                    'vehicle_plate' => $vehicle->number_plate,
                    'location' => $booking->parkingSlot->parkingLocation->name,
                    'slot' => $booking->parkingSlot->slot_number,
                    'exit_time' => $exitTime->format('Y-m-d H:i:s'),
                    'duration' => $parkingDuration,
                    'amount' => $charges['total_amount'],
                ]
            );

            Log::info('Vehicle exit processed successfully', [
                'vehicle_id' => $vehicle->id,
                'entry_id' => $entry->id,
                'exit_id' => $exit->id,
                'duration_minutes' => $parkingDuration,
                'total_amount' => $charges['total_amount'],
                'gate_number' => $request->gate_number,
                'operator_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle exit processed successfully',
                'data' => [
                    'exit_id' => $exit->id,
                    'booking_id' => $booking->id,
                    'vehicle_plate' => $vehicle->number_plate,
                    'entry_time' => $entry->entry_time->format('Y-m-d H:i:s'),
                    'exit_time' => $exitTime->format('Y-m-d H:i:s'),
                    'duration_minutes' => $parkingDuration,
                    'charges' => $charges,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing vehicle exit: ' . $e->getMessage(), [
                'vehicle_number_plate' => $request->vehicle_number_plate,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process vehicle exit'
            ], 500);
        }
    }

    /**
     * Scan QR code for quick entry/exit.
     */
    public function scanQrCode(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'qr_code' => 'required|string',
                'gate_number' => 'required|string',
                'action' => 'required|in:entry,exit',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Decode QR code (assuming it contains booking ID or vehicle plate)
            $qrData = json_decode(base64_decode($request->qr_code), true);

            if (!$qrData || !isset($qrData['type'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid QR code'
                ], 400);
            }

            if ($request->action === 'entry' && $qrData['type'] === 'booking') {
                // Process entry using booking ID from QR code
                return $this->vehicleEntry(new Request([
                    'booking_id' => $qrData['booking_id'],
                    'vehicle_number_plate' => $qrData['vehicle_plate'],
                    'gate_number' => $request->gate_number,
                    'entry_method' => 'qr_code',
                    'notes' => 'Entry via QR code scan',
                ]));
            } elseif ($request->action === 'exit' && $qrData['type'] === 'vehicle') {
                // Process exit using vehicle plate from QR code
                return $this->vehicleExit(new Request([
                    'vehicle_number_plate' => $qrData['vehicle_plate'],
                    'gate_number' => $request->gate_number,
                    'exit_method' => 'qr_code',
                    'notes' => 'Exit via QR code scan',
                ]));
            }

            return response()->json([
                'success' => false,
                'message' => 'QR code type does not match requested action'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Error processing QR code scan: ' . $e->getMessage(), [
                'qr_code' => $request->qr_code,
                'action' => $request->action,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process QR code'
            ], 500);
        }
    }

    /**
     * Get gate operation logs.
     */
    public function getGateLogs(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'gate_number' => 'nullable|string',
                'date_from' => 'nullable|date',
                'date_to' => 'nullable|date',
                'action_type' => 'nullable|in:entry,exit',
                'per_page' => 'nullable|integer|min:1|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Build query for entries and exits
            $entriesQuery = VehicleEntry::with(['vehicle', 'parkingSlot', 'booking.user'])
                ->select(['id', 'vehicle_id', 'parking_slot_id', 'gate_number', 'entry_time as action_time', 'recorded_by', 'notes'])
                ->addSelect(DB::raw("'entry' as action_type"));

            $exitsQuery = VehicleExit::with(['vehicle', 'parkingLocation'])
                ->join('vehicle_entries', 'vehicle_exits.vehicle_entry_id', '=', 'vehicle_entries.id')
                ->select(['vehicle_exits.id', 'vehicle_exits.vehicle_id', 'vehicle_entries.parking_slot_id', 'vehicle_exits.gate_number', 'vehicle_exits.exit_time as action_time', 'vehicle_exits.recorded_by', 'vehicle_exits.notes'])
                ->addSelect(DB::raw("'exit' as action_type"));

            // Apply filters
            if ($request->filled('gate_number')) {
                $entriesQuery->where('gate_number', $request->gate_number);
                $exitsQuery->where('vehicle_exits.gate_number', $request->gate_number);
            }

            if ($request->filled('date_from')) {
                $entriesQuery->whereDate('entry_time', '>=', $request->date_from);
                $exitsQuery->whereDate('exit_time', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $entriesQuery->whereDate('entry_time', '<=', $request->date_to);
                $exitsQuery->whereDate('exit_time', '<=', $request->date_to);
            }

            // Combine queries based on action type filter
            if ($request->action_type === 'entry') {
                $logs = $entriesQuery->latest('entry_time')->paginate($request->per_page ?? 20);
            } elseif ($request->action_type === 'exit') {
                $logs = $exitsQuery->latest('exit_time')->paginate($request->per_page ?? 20);
            } else {
                // Union both queries (simplified approach - in real implementation, you might want to use a more sophisticated method)
                $entries = $entriesQuery->latest('entry_time')->take(50)->get();
                $exits = $exitsQuery->latest('exit_time')->take(50)->get();
                $logs = $entries->merge($exits)->sortByDesc('action_time')->take(20);
            }

            return response()->json([
                'success' => true,
                'data' => $logs,
                'message' => 'Gate logs retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching gate logs: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch gate logs'
            ], 500);
        }
    }

    /**
     * Calculate parking charges based on duration and slot type.
     */
    protected function calculateParkingCharges(ParkingSlot $slot, int $durationMinutes): array
    {
        // Basic calculation - in real implementation, this would be more sophisticated
        $ratePerHour = $slot->hourly_rate ?? 50; // Default rate
        $hours = ceil($durationMinutes / 60);

        $baseAmount = $hours * $ratePerHour;
        $tax = $baseAmount * 0.05; // 5% tax
        $totalAmount = $baseAmount + $tax;

        return [
            'duration_minutes' => $durationMinutes,
            'hours_charged' => $hours,
            'rate_per_hour' => $ratePerHour,
            'base_amount' => $baseAmount,
            'tax_amount' => $tax,
            'total_amount' => $totalAmount,
        ];
    }
}
