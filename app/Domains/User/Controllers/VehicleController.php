<?php

namespace App\Domains\User\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Domains\Vehicle\Services\VehicleService;
use App\Shared\DTOs\VehicleDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class VehicleController extends Controller
{
    protected $vehicleService;
    protected $vehicleRepository;

    public function __construct(VehicleService $vehicleService, VehicleRepository $vehicleRepository)
    {
        $this->middleware('auth');
        $this->vehicleService = $vehicleService;
        $this->vehicleRepository = $vehicleRepository;
    }

    /**
     * Display a listing of user's vehicles.
     */
    public function index(Request $request)
    {
        $filters = $request->only([
            'status',
            'verification_status',
            'vehicle_type',
            'search',
            'per_page'
        ]);

        $vehicles = $this->vehicleRepository->getUserVehicles(auth()->id(), $filters);

        return view('user.vehicles.index', compact('vehicles', 'filters'));
    }

    /**
     * Show the form for creating a new vehicle.
     */
    public function create()
    {
        return view('user.vehicles.create');
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
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            $vehicleData = array_merge($validator->validated(), [
                'user_id' => auth()->id(),
                'status' => 'active',
                'verification_status' => 'pending',
            ]);

            $vehicle = $this->vehicleService->createVehicle($vehicleData);

            return redirect()->route('user.vehicles.show', $vehicle)
                           ->with('success', __('Vehicle added successfully and verification has been initiated.'));
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', __('Failed to add vehicle: :message', ['message' => $e->getMessage()]))
                           ->withInput();
        }
    }

    /**
     * Display the specified vehicle.
     */
    public function show(Vehicle $vehicle)
    {
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

        return view('user.vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified vehicle.
     */
    public function edit(Vehicle $vehicle)
    {
        Gate::authorize('update', $vehicle);

        if ($vehicle->verification_status === 'verified') {
            return redirect()->route('user.vehicles.show', $vehicle)
                           ->with('error', __('Verified vehicles cannot be edited.'));
        }

        return view('user.vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified vehicle.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        Gate::authorize('update', $vehicle);

        if ($vehicle->verification_status === 'verified') {
            return redirect()->back()
                           ->with('error', __('Verified vehicles cannot be edited.'));
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
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            $this->vehicleService->updateVehicle($vehicle, $validator->validated());

            return redirect()->route('user.vehicles.show', $vehicle)
                           ->with('success', __('Vehicle updated successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', __('Failed to update vehicle: :message', ['message' => $e->getMessage()]))
                           ->withInput();
        }
    }

    /**
     * Remove the specified vehicle.
     */
    public function destroy(Vehicle $vehicle)
    {
        Gate::authorize('delete', $vehicle);

        try {
            // Check for active bookings
            $activeBookings = $vehicle->bookings()
                                   ->whereIn('status', ['confirmed', 'checked_in'])
                                   ->count();

            if ($activeBookings > 0) {
                return response()->json([
                    'success' => false,
                    'message' => __('Cannot delete vehicle with active bookings.'),
                ]);
            }

            $this->vehicleRepository->delete($vehicle);

            return response()->json([
                'success' => true,
                'message' => __('Vehicle deleted successfully.'),
                'redirect' => route('user.vehicles.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Failed to delete vehicle: :message', ['message' => $e->getMessage()]),
            ]);
        }
    }

    /**
     * Request re-verification for a vehicle.
     */
    public function requestVerification(Vehicle $vehicle)
    {
        Gate::authorize('update', $vehicle);

        if ($vehicle->verification_status === 'verified') {
            return response()->json([
                'success' => false,
                'message' => __('Vehicle is already verified.'),
            ]);
        }

        if ($vehicle->verification_status === 'pending') {
            return response()->json([
                'success' => false,
                'message' => __('Verification is already in progress.'),
            ]);
        }

        try {
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
                'message' => __('Verification request submitted successfully.'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Verification request failed: :message', ['message' => $e->getMessage()]),
            ]);
        }
    }
}
