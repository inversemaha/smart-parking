<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Services\VehicleService;
use App\Repositories\VehicleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class VehicleController extends Controller
{
    protected $vehicleService;
    protected $vehicleRepository;

    public function __construct(VehicleService $vehicleService, VehicleRepository $vehicleRepository)
    {
        $this->middleware('auth:sanctum');
        $this->vehicleService = $vehicleService;
        $this->vehicleRepository = $vehicleRepository;
    }

    /**
     * Display a listing of user's vehicles.
     */
    public function index(Request $request)
    {
        try {
            $filters = $request->only([
                'status',
                'verification_status',
                'vehicle_type',
                'search',
                'per_page'
            ]);

            $vehicles = $this->vehicleRepository->getUserVehicles(auth()->id(), $filters);

            return response()->json([
                'success' => true,
                'message' => 'Vehicles retrieved successfully',
                'data' => $vehicles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve vehicles: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created vehicle.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'registration_number' => 'required|string|max:20|unique:vehicles,registration_number',
            'vehicle_type' => 'required|in:car,motorcycle,bus,truck',
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'required|string|max:30',
            'chassis_number' => 'nullable|string|max:50',
            'engine_number' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $vehicleData = array_merge($validator->validated(), [
                'user_id' => auth()->id(),
                'status' => 'active',
                'verification_status' => 'pending',
            ]);

            $vehicle = $this->vehicleService->createVehicle($vehicleData);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle added successfully and verification has been initiated',
                'data' => $vehicle->load('verificationLogs')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add vehicle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified vehicle.
     */
    public function show(Vehicle $vehicle)
    {
        try {
            Gate::authorize('view', $vehicle);

            $vehicle->load([
                'verificationLogs' => function ($query) {
                    $query->orderBy('created_at', 'desc')->limit(5);
                },
                'manualVerifications' => function ($query) {
                    $query->with('verifiedBy')->orderBy('created_at', 'desc');
                },
                'bookings' => function ($query) {
                    $query->with(['parkingSlot.parkingLocation', 'payments'])
                          ->orderBy('created_at', 'desc')
                          ->limit(10);
                }
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle details retrieved successfully',
                'data' => $vehicle
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve vehicle: ' . $e->getMessage()
            ], 403);
        }
    }

    /**
     * Update the specified vehicle.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        try {
            Gate::authorize('update', $vehicle);

            if ($vehicle->verification_status === 'verified') {
                return response()->json([
                    'success' => false,
                    'message' => 'Verified vehicles cannot be edited'
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'registration_number' => 'required|string|max:20|unique:vehicles,registration_number,' . $vehicle->id,
                'vehicle_type' => 'required|in:car,motorcycle,bus,truck',
                'brand' => 'required|string|max:50',
                'model' => 'required|string|max:50',
                'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
                'color' => 'required|string|max:30',
                'chassis_number' => 'nullable|string|max:50',
                'engine_number' => 'nullable|string|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updatedVehicle = $this->vehicleService->updateVehicle($vehicle, $validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Vehicle updated successfully',
                'data' => $updatedVehicle
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update vehicle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified vehicle.
     */
    public function destroy(Vehicle $vehicle)
    {
        try {
            Gate::authorize('delete', $vehicle);

            // Check for active bookings
            $activeBookings = $vehicle->bookings()
                                   ->whereIn('status', ['confirmed', 'checked_in'])
                                   ->count();

            if ($activeBookings > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete vehicle with active bookings'
                ], 422);
            }

            $this->vehicleRepository->delete($vehicle);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete vehicle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Request verification for a vehicle.
     */
    public function requestVerification(Vehicle $vehicle)
    {
        try {
            Gate::authorize('update', $vehicle);

            if ($vehicle->verification_status === 'verified') {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle is already verified'
                ], 422);
            }

            if ($vehicle->verification_status === 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Verification is already in progress'
                ], 422);
            }

            // Reset verification status and initiate new verification
            $vehicle->update([
                'verification_status' => 'pending',
                'verified_at' => null,
                'verified_by' => null,
                'verification_notes' => null,
            ]);

            $result = $this->vehicleService->initiateVerification($vehicle);

            return response()->json([
                'success' => true,
                'message' => 'Verification request submitted successfully',
                'data' => $vehicle->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Verification request failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get verification status of a vehicle.
     */
    public function verificationStatus(Vehicle $vehicle)
    {
        try {
            Gate::authorize('view', $vehicle);

            $verificationLogs = $vehicle->verificationLogs()
                                      ->orderBy('created_at', 'desc')
                                      ->limit(5)
                                      ->get();

            return response()->json([
                'success' => true,
                'message' => 'Verification status retrieved successfully',
                'data' => [
                    'status' => $vehicle->verification_status,
                    'verified_at' => $vehicle->verified_at,
                    'verification_notes' => $vehicle->verification_notes,
                    'verification_logs' => $verificationLogs,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve verification status: ' . $e->getMessage()
            ], 403);
        }
    }
}
