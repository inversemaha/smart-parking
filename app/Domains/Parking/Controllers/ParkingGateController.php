<?php

namespace App\Domains\Parking\Controllers;

use App\Domains\Parking\Models\ParkingGate;
use App\Domains\Parking\Models\ParkingZone;
use App\Domains\Parking\Models\ParkingFloor;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ParkingGateController extends Controller
{
    /**
     * Display a listing of parking gates
     */
    public function index(): View
    {
        $gates = ParkingGate::with('zone', 'floor')
            ->orderBy('zone_id')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.parking.gates.index', compact('gates'));
    }

    /**
     * Show the form for creating a new parking gate
     */
    public function create(): View
    {
        $zones = ParkingZone::active()->orderBy('name')->get();
        $floors = ParkingFloor::orderBy('zone_id')->orderBy('floor_number')->get();

        return view('admin.parking.gates.create', compact('zones', 'floors'));
    }

    /**
     * Store a newly created parking gate in database
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:parking_zones,id',
            'floor_id' => 'nullable|exists:parking_floors,id',
            'name' => 'required|string|max:255|unique:parking_gates,name',
            'description' => 'nullable|string|max:500',
            'gate_type' => 'required|in:entry,exit,bidirectional',
            'gate_status' => 'required|in:operational,maintenance,closed',
            'location' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'camera_url' => 'nullable|string|max:255|url',
            'is_active' => 'boolean',
        ]);

        $gate = ParkingGate::create($validated);

        return redirect()
            ->route('admin.parking-gates.show', $gate)
            ->with('success', __('admin.gate_created_successfully'));
    }

    /**
     * Display a specific parking gate
     */
    public function show(ParkingGate $gate): View
    {
        $gate->load('zone', 'floor', 'accessLogs');
        $recentAccess = $gate->recentAccessAttempts()->limit(50)->get();

        return view('admin.parking.gates.show', compact('gate', 'recentAccess'));
    }

    /**
     * Show the form for editing parking gate
     */
    public function edit(ParkingGate $gate): View
    {
        $zones = ParkingZone::active()->orderBy('name')->get();
        $floors = ParkingFloor::orderBy('zone_id')->orderBy('floor_number')->get();

        return view('admin.parking.gates.edit', compact('gate', 'zones', 'floors'));
    }

    /**
     * Update parking gate in database
     */
    public function update(Request $request, ParkingGate $gate): RedirectResponse
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:parking_zones,id',
            'floor_id' => 'nullable|exists:parking_floors,id',
            'name' => 'required|string|max:255|unique:parking_gates,name,' . $gate->id,
            'description' => 'nullable|string|max:500',
            'gate_type' => 'required|in:entry,exit,bidirectional',
            'gate_status' => 'required|in:operational,maintenance,closed',
            'location' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'camera_url' => 'nullable|string|max:255|url',
            'is_active' => 'boolean',
        ]);

        $gate->update($validated);

        return redirect()
            ->route('admin.parking-gates.show', $gate)
            ->with('success', __('admin.gate_updated_successfully'));
    }

    /**
     * Delete a parking gate (soft delete)
     */
    public function destroy(ParkingGate $gate): RedirectResponse
    {
        $gate->delete();

        return redirect()
            ->route('admin.parking-gates.index')
            ->with('success', __('admin.gate_deleted_successfully'));
    }

    /**
     * Restore a soft-deleted parking gate
     */
    public function restore(string $id): RedirectResponse
    {
        $gate = ParkingGate::onlyTrashed()->findOrFail($id);
        $gate->restore();

        return redirect()
            ->route('admin.parking-gates.index')
            ->with('success', __('admin.gate_restored_successfully'));
    }

    /**
     * Change gate operational status
     */
    public function updateStatus(Request $request, ParkingGate $gate): RedirectResponse
    {
        $validated = $request->validate([
            'gate_status' => 'required|in:operational,maintenance,closed',
        ]);

        $gate->update($validated);

        return back()->with('success', __('admin.gate_status_updated'));
    }

    /**
     * Get access logs for a gate
     */
    public function accessLogs(ParkingGate $gate, Request $request): View
    {
        $query = $gate->accessLogs();

        if ($request->has('status')) {
            $query->where('access_status', $request->access_status);
        }

        $logs = $query->orderBy('accessed_at', 'desc')->paginate(50);

        return view('admin.parking.gates.access-logs', compact('gate', 'logs'));
    }
}
