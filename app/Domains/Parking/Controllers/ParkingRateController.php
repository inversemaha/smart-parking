<?php

namespace App\Domains\Parking\Controllers;

use App\Domains\Parking\Models\ParkingRate;
use App\Domains\Parking\Models\ParkingZone;
use App\Domains\Parking\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ParkingRateController extends Controller
{
    /**
     * Display a listing of parking rates (rate matrix view)
     */
    public function index(): View
    {
        $zones = ParkingZone::active()
            ->with('rates.vehicleType')
            ->orderBy('name')
            ->get();

        $vehicleTypes = VehicleType::active()
            ->ordered()
            ->get();

        $rates = ParkingRate::with('zone', 'vehicleType')->paginate(20);

        return view('admin.parking.rates.index', compact('zones', 'vehicleTypes', 'rates'));
    }

    /**
     * Show the form for creating a new parking rate
     */
    public function create(): View
    {
        $zones = ParkingZone::active()
            ->orderBy('name')
            ->get();

        $vehicleTypes = VehicleType::active()
            ->ordered()
            ->get();

        return view('admin.parking.rates.create', compact('zones', 'vehicleTypes'));
    }

    /**
     * Store a newly created parking rate in database
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:parking_zones,id',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'hourly_rate' => 'required|numeric|min:0',
            'daily_rate' => 'required|numeric|min:0',
            'peak_hour_rate' => 'nullable|numeric|min:0',
            'off_peak_rate' => 'nullable|numeric|min:0',
            'peak_hours_start' => 'nullable|date_format:H:i',
            'peak_hours_end' => 'nullable|date_format:H:i',
            'is_active' => 'boolean',
        ]);

        // Check unique constraint: zone_id + vehicle_type_id
        $exists = ParkingRate::where('zone_id', $validated['zone_id'])
            ->where('vehicle_type_id', $validated['vehicle_type_id'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', __('admin.rate_already_exists_for_zone_vehicle'));
        }

        $rate = ParkingRate::create($validated);

        return redirect()
            ->route('admin.parking-rates.show', $rate)
            ->with('success', __('admin.rate_created_successfully'));
    }

    /**
     * Display a specific parking rate
     */
    public function show(ParkingRate $rate): View
    {
        $rate->load('zone', 'vehicleType');
        
        return view('admin.parking.rates.show', compact('rate'));
    }

    /**
     * Show the form for editing parking rate
     */
    public function edit(ParkingRate $rate): View
    {
        $zones = ParkingZone::active()
            ->orderBy('name')
            ->get();

        $vehicleTypes = VehicleType::active()
            ->ordered()
            ->get();

        return view('admin.parking.rates.edit', compact('rate', 'zones', 'vehicleTypes'));
    }

    /**
     * Update parking rate in database
     */
    public function update(Request $request, ParkingRate $rate): RedirectResponse
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:parking_zones,id',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'hourly_rate' => 'required|numeric|min:0',
            'daily_rate' => 'required|numeric|min:0',
            'peak_hour_rate' => 'nullable|numeric|min:0',
            'off_peak_rate' => 'nullable|numeric|min:0',
            'peak_hours_start' => 'nullable|date_format:H:i',
            'peak_hours_end' => 'nullable|date_format:H:i',
            'is_active' => 'boolean',
        ]);

        // Check unique constraint if zone_id or vehicle_type_id changed
        if ($validated['zone_id'] !== $rate->zone_id || $validated['vehicle_type_id'] !== $rate->vehicle_type_id) {
            $exists = ParkingRate::where('zone_id', $validated['zone_id'])
                ->where('vehicle_type_id', $validated['vehicle_type_id'])
                ->where('id', '!=', $rate->id)
                ->exists();

            if ($exists) {
                return back()
                    ->withInput()
                    ->with('error', __('admin.rate_already_exists_for_zone_vehicle'));
            }
        }

        $rate->update($validated);

        return redirect()
            ->route('admin.parking-rates.show', $rate)
            ->with('success', __('admin.rate_updated_successfully'));
    }

    /**
     * Delete a parking rate (soft delete)
     */
    public function destroy(ParkingRate $rate): RedirectResponse
    {
        $rate->delete();

        return redirect()
            ->route('admin.parking-rates.index')
            ->with('success', __('admin.rate_deleted_successfully'));
    }

    /**
     * Restore a soft-deleted parking rate
     */
    public function restore(string $id): RedirectResponse
    {
        $rate = ParkingRate::onlyTrashed()->findOrFail($id);
        $rate->restore();

        return redirect()
            ->route('admin.parking-rates.index')
            ->with('success', __('admin.rate_restored_successfully'));
    }

    /**
     * Show rate matrix view for a specific zone
     */
    public function matrixByZone(ParkingZone $zone): View
    {
        $vehicleTypes = VehicleType::active()->ordered()->get();
        $rates = $zone->rates()->with('vehicleType')->get();

        return view('admin.parking.rates.matrix-zone', compact('zone', 'vehicleTypes', 'rates'));
    }

    /**
     * Bulk import rates from CSV
     */
    public function importForm(): View
    {
        return view('admin.parking.rates.import');
    }

    /**
     * Process CSV import
     */
    public function import(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $validated['csv_file'];
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);
        $imported = 0;
        $errors = [];

        while (($row = fgetcsv($handle)) !== false) {
            try {
                $data = array_combine($header, $row);
                
                $exists = ParkingRate::where('zone_id', $data['zone_id'])
                    ->where('vehicle_type_id', $data['vehicle_type_id'])
                    ->exists();

                if (!$exists) {
                    ParkingRate::create($data);
                    $imported++;
                }
            } catch (\Exception $e) {
                $errors[] = "Row error: " . $e->getMessage();
            }
        }

        fclose($handle);

        return back()
            ->with('success', __('admin.rates_imported_successfully', ['count' => $imported]))
            ->with('errors', $errors);
    }
}
