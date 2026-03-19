<?php

namespace App\Domains\Parking\Controllers;

use App\Domains\Parking\Models\ParkingZone;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ParkingZoneController extends Controller
{
    /**
     * Display a listing of parking zones
     */
    public function index(): View
    {
        $zones = ParkingZone::with('floors', 'rates')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.parking.zones.index', compact('zones'));
    }

    /**
     * Show the form for creating a new parking zone
     */
    public function create(): View
    {
        return view('admin.parking.zones.create');
    }

    /**
     * Store a newly created parking zone in database
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:parking_zones,name',
            'building_id' => 'required|integer',
            'description' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'total_capacity' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $zone = ParkingZone::create($validated);

        return redirect()
            ->route('admin.parking-zones.show', $zone)
            ->with('success', __('admin.zone_created_successfully'));
    }

    /**
     * Display a specific parking zone
     */
    public function show(ParkingZone $zone): View
    {
        $zone->load('floors', 'rates');
        
        return view('admin.parking.zones.show', compact('zone'));
    }

    /**
     * Show the form for editing parking zone
     */
    public function edit(ParkingZone $zone): View
    {
        return view('admin.parking.zones.edit', compact('zone'));
    }

    /**
     * Update parking zone in database
     */
    public function update(Request $request, ParkingZone $zone): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:parking_zones,name,' . $zone->id,
            'building_id' => 'required|integer',
            'description' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'total_capacity' => 'required|integer|min:1',
            'current_occupancy' => 'integer|min:0|lte:total_capacity',
            'is_active' => 'boolean',
        ]);

        $zone->update($validated);

        return redirect()
            ->route('admin.parking-zones.show', $zone)
            ->with('success', __('admin.zone_updated_successfully'));
    }

    /**
     * Delete a parking zone (soft delete)
     */
    public function destroy(ParkingZone $zone): RedirectResponse
    {
        $zone->delete();

        return redirect()
            ->route('admin.parking-zones.index')
            ->with('success', __('admin.zone_deleted_successfully'));
    }

    /**
     * Restore a soft-deleted parking zone
     */
    public function restore(string $id): RedirectResponse
    {
        $zone = ParkingZone::onlyTrashed()->findOrFail($id);
        $zone->restore();

        return redirect()
            ->route('admin.parking-zones.index')
            ->with('success', __('admin.zone_restored_successfully'));
    }
}
