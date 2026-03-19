<?php

namespace App\Domains\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Vehicle\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminVehicleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage_vehicles|admin');
    }

    /**
     * Display a listing of all vehicles.
     */
    public function index(Request $request)
    {
        $query = Vehicle::with('user', 'verifiedBy');

        // Filter by verification status
        if ($request->has('status') && $request->status) {
            $query->where('verification_status', $request->status);
        }

        // Filter by vehicle type
        if ($request->has('type') && $request->type) {
            $query->where('vehicle_type', $request->type);
        }

        // Filter by active status
        if ($request->has('active')) {
            $query->where('is_active', $request->active == 'true');
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('registration_number', 'ilike', "%{$search}%")
                  ->orWhere('brand', 'ilike', "%{$search}%")
                  ->orWhere('model', 'ilike', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'ilike', "%{$search}%");
                  });
            });
        }

        $vehicles = $query->paginate(15);

        return view('admin.vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new vehicle.
     */
    public function create()
    {
        return view('admin.vehicles.create');
    }

    /**
     * Store a newly created vehicle in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'registration_number' => 'required|string|max:20|unique:vehicles,registration_number',
            'vehicle_type' => 'required|in:car,motorcycle,bus,truck',
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'color' => 'required|string|max:30',
            'manufacture_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'is_active' => 'nullable|boolean',
        ]);

        try {
            Vehicle::create([
                'registration_number' => $validated['registration_number'],
                'vehicle_type' => $validated['vehicle_type'],
                'brand' => $validated['brand'],
                'model' => $validated['model'],
                'color' => $validated['color'],
                'manufacture_year' => $validated['manufacture_year'],
                'is_active' => $validated['is_active'] ?? true,
                'verification_status' => 'pending',
            ]);

            return redirect()
                ->route('admin.vehicles.index')
                ->with('success', 'Vehicle added successfully');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to add vehicle: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified vehicle.
     */
    public function show(Vehicle $vehicle)
    {
        $vehicle->load('user', 'verifiedBy', 'bookings.parking_location');
        return view('admin.vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified vehicle.
     */
    public function edit(Vehicle $vehicle)
    {
        return view('admin.vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified vehicle in database.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'vehicle_type' => 'required|in:car,motorcycle,bus,truck',
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'color' => 'required|string|max:30',
            'manufacture_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'is_active' => 'nullable|boolean',
        ]);

        try {
            $vehicle->update([
                'vehicle_type' => $validated['vehicle_type'],
                'brand' => $validated['brand'],
                'model' => $validated['model'],
                'color' => $validated['color'],
                'manufacture_year' => $validated['manufacture_year'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            return redirect()
                ->route('admin.vehicles.show', $vehicle->id)
                ->with('success', 'Vehicle updated successfully');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to update vehicle: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete the specified vehicle.
     */
    public function destroy(Vehicle $vehicle)
    {
        try {
            $vehicle->delete();

            return redirect()
                ->route('admin.vehicles.index')
                ->with('success', 'Vehicle deleted successfully');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to delete vehicle: ' . $e->getMessage());
        }
    }
}
