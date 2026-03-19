<?php

namespace App\Domains\Parking\Controllers;

use App\Domains\Parking\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class VehicleTypeController extends Controller
{
    /**
     * Display a listing of vehicle types
     */
    public function index(): View
    {
        $vehicleTypes = VehicleType::ordered()
            ->paginate(15);

        return view('admin.parking.vehicle-types.index', compact('vehicleTypes'));
    }

    /**
     * Show the form for creating a new vehicle type
     */
    public function create(): View
    {
        return view('admin.parking.vehicle-types.create');
    }

    /**
     * Store a newly created vehicle type in database
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:vehicle_types,name',
            'description' => 'nullable|string|max:500',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'rate_multiplier' => 'required|numeric|min:0.1|max:10',
            'icon_url' => 'nullable|string|max:255|url',
            'display_order' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $vehicleType = VehicleType::create($validated);

        return redirect()
            ->route('admin.vehicle-types.show', $vehicleType)
            ->with('success', __('admin.vehicle_type_created_successfully'));
    }

    /**
     * Display a specific vehicle type
     */
    public function show(VehicleType $vehicleType): View
    {
        $vehicleType->load('rates');
        
        return view('admin.parking.vehicle-types.show', compact('vehicleType'));
    }

    /**
     * Show the form for editing vehicle type
     */
    public function edit(VehicleType $vehicleType): View
    {
        return view('admin.parking.vehicle-types.edit', compact('vehicleType'));
    }

    /**
     * Update vehicle type in database
     */
    public function update(Request $request, VehicleType $vehicleType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:vehicle_types,name,' . $vehicleType->id,
            'description' => 'nullable|string|max:500',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'rate_multiplier' => 'required|numeric|min:0.1|max:10',
            'icon_url' => 'nullable|string|max:255|url',
            'display_order' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $vehicleType->update($validated);

        return redirect()
            ->route('admin.vehicle-types.show', $vehicleType)
            ->with('success', __('admin.vehicle_type_updated_successfully'));
    }

    /**
     * Delete a vehicle type (soft delete)
     */
    public function destroy(VehicleType $vehicleType): RedirectResponse
    {
        // Check if this vehicle type has associated rates
        if ($vehicleType->rates()->exists()) {
            return back()
                ->with('error', __('admin.cannot_delete_vehicle_type_has_rates'));
        }

        $vehicleType->delete();

        return redirect()
            ->route('admin.vehicle-types.index')
            ->with('success', __('admin.vehicle_type_deleted_successfully'));
    }

    /**
     * Restore a soft-deleted vehicle type
     */
    public function restore(string $id): RedirectResponse
    {
        $vehicleType = VehicleType::onlyTrashed()->findOrFail($id);
        $vehicleType->restore();

        return redirect()
            ->route('admin.vehicle-types.index')
            ->with('success', __('admin.vehicle_type_restored_successfully'));
    }

    /**
     * Bulk update display order
     */
    public function updateOrder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:vehicle_types,id',
            'items.*.order' => 'required|integer|min:1',
        ]);

        foreach ($validated['items'] as $item) {
            VehicleType::where('id', $item['id'])->update(['display_order' => $item['order']]);
        }

        return back()->with('success', __('admin.order_updated_successfully'));
    }
}
