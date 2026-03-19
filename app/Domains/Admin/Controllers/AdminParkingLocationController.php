<?php

namespace App\Domains\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Parking\Models\ParkingLocation;
use Illuminate\Http\Request;

class AdminParkingLocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage_parking_locations|admin');
    }

    /**
     * Display a listing of all parking locations.
     */
    public function index(Request $request)
    {
        $query = ParkingLocation::query();

        // Filter by status
        if ($request->has('status') && $request->status) {
            $active = $request->status === 'active';
            $query->where('is_active', $active);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('code', 'ilike', "%{$search}%")
                  ->orWhere('address', 'ilike', "%{$search}%");
            });
        }

        $parkingLocations = $query->paginate(15);

        return view('admin.parking-locations.index', compact('parkingLocations'));
    }

    /**
     * Show the form for creating a new parking location.
     */
    public function create()
    {
        return view('admin.parking-locations.create');
    }

    /**
     * Store a newly created parking location in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:parking_locations,name',
            'code' => 'required|string|max:10|unique:parking_locations,code',
            'description' => 'nullable|string|max:1000',
            'address' => 'required|string|max:500',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'total_capacity' => 'required|integer|min:1',
            'hourly_rate' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            ParkingLocation::create([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'description' => $validated['description'],
                'address' => $validated['address'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'total_capacity' => $validated['total_capacity'],
                'hourly_rate' => $validated['hourly_rate'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            return redirect()
                ->route('admin.parking-locations.index')
                ->with('success', 'Parking location created successfully');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to create parking location: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified parking location.
     */
    public function show(ParkingLocation $parkingLocation)
    {
        $parkingLocation->load('parkingSlots', 'bookings');
        return view('admin.parking-locations.show', compact('parkingLocation'));
    }

    /**
     * Show the form for editing the specified parking location.
     */
    public function edit(ParkingLocation $parkingLocation)
    {
        return view('admin.parking-locations.edit', compact('parkingLocation'));
    }

    /**
     * Update the specified parking location in database.
     */
    public function update(Request $request, ParkingLocation $parkingLocation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:parking_locations,name,' . $parkingLocation->id,
            'description' => 'nullable|string|max:1000',
            'address' => 'required|string|max:500',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'total_capacity' => 'required|integer|min:1',
            'hourly_rate' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            $parkingLocation->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'address' => $validated['address'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'total_capacity' => $validated['total_capacity'],
                'hourly_rate' => $validated['hourly_rate'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            return redirect()
                ->route('admin.parking-locations.show', $parkingLocation->id)
                ->with('success', 'Parking location updated successfully');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to update parking location: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete the specified parking location.
     */
    public function destroy(ParkingLocation $parkingLocation)
    {
        try {
            $parkingLocation->delete();

            return redirect()
                ->route('admin.parking-locations.index')
                ->with('success', 'Parking location deleted successfully');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to delete parking location: ' . $e->getMessage());
        }
    }
}
