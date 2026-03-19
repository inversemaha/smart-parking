<?php

namespace App\Domains\Parking\Controllers;

use App\Domains\Parking\Models\ParkingFloor;
use App\Domains\Parking\Models\ParkingZone;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ParkingFloorController extends Controller
{
    /**
     * Display a listing of parking floors
     */
    public function index(): View
    {
        $floors = ParkingFloor::with('zone')
            ->orderBy('zone_id')
            ->orderBy('floor_number')
            ->paginate(15);

        return view('admin.parking-floors.index', compact('floors'));
    }

    /**
     * Show the form for creating a new parking floor
     */
    public function create(): View
    {
        $zones = ParkingZone::active()->orderBy('name')->get();
        
        return view('admin.parking-floors.create', compact('zones'));
    }

    /**
     * Store a newly created parking floor in database
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:parking_zones,id',
            'floor_number' => 'required|integer|min:1',
            'floor_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'total_capacity' => 'required|integer|min:1',
            'hourly_rate' => 'nullable|numeric|min:0',
            'daily_rate' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        // Check unique constraint: zone_id + floor_number
        $exists = ParkingFloor::where('zone_id', $validated['zone_id'])
            ->where('floor_number', $validated['floor_number'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', __('admin.floor_already_exists_in_zone'));
        }

        $floor = ParkingFloor::create($validated);

        return redirect()
            ->route('admin.parking-floors.show', $floor)
            ->with('success', __('admin.floor_created_successfully'));
    }

    /**
     * Display a specific parking floor
     */
    public function show(ParkingFloor $floor): View
    {
        $floor->load('zone', 'slots');
        
        return view('admin.parking-floors.show', compact('floor'));
    }

    /**
     * Show the form for editing parking floor
     */
    public function edit(ParkingFloor $floor): View
    {
        $zones = ParkingZone::active()->orderBy('name')->get();

        return view('admin.parking-floors.edit', compact('floor', 'zones'));
    }

    /**
     * Update parking floor in database
     */
    public function update(Request $request, ParkingFloor $floor): RedirectResponse
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:parking_zones,id',
            'floor_number' => 'required|integer|min:1',
            'floor_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'total_capacity' => 'required|integer|min:1',
            'current_occupancy' => 'integer|min:0|lte:total_capacity',
            'hourly_rate' => 'nullable|numeric|min:0',
            'daily_rate' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        // Check unique constraint if zone_id or floor_number changed
        if ($validated['zone_id'] !== $floor->zone_id || $validated['floor_number'] !== $floor->floor_number) {
            $exists = ParkingFloor::where('zone_id', $validated['zone_id'])
                ->where('floor_number', $validated['floor_number'])
                ->where('id', '!=', $floor->id)
                ->exists();

            if ($exists) {
                return back()
                    ->withInput()
                    ->with('error', __('admin.floor_already_exists_in_zone'));
            }
        }

        $floor->update($validated);

        return redirect()
            ->route('admin.parking-floors.show', $floor)
            ->with('success', __('admin.floor_updated_successfully'));
    }

    /**
     * Delete a parking floor (soft delete)
     */
    public function destroy(ParkingFloor $floor): RedirectResponse
    {
        $floor->delete();

        return redirect()
            ->route('admin.parking-floors.index')
            ->with('success', __('admin.floor_deleted_successfully'));
    }

    /**
     * Restore a soft-deleted parking floor
     */
    public function restore(string $id): RedirectResponse
    {
        $floor = ParkingFloor::onlyTrashed()->findOrFail($id);
        $floor->restore();

        return redirect()
            ->route('admin.parking-floors.index')
            ->with('success', __('admin.floor_restored_successfully'));
    }
}
