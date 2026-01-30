<?php

namespace App\Domains\Vehicle\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Vehicle\Services\VehicleService;
use App\Domains\Vehicle\Models\Vehicle;
use App\Shared\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class VisitorVehicleController extends Controller
{
    public function __construct(
        protected VehicleService $vehicleService,
        protected FileUploadService $fileUploadService
    ) {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * List visitor vehicles
     */
    public function index(): View
    {
        $vehicles = $this->vehicleService->getUserVehicles(auth()->id());

        return view('visitor.vehicles.index', compact('vehicles'));
    }

    /**
     * Show create vehicle form
     */
    public function create(): View
    {
        $vehicleTypes = $this->vehicleService->getVehicleTypes();

        return view('visitor.vehicles.create', compact('vehicleTypes'));
    }

    /**
     * Store new vehicle
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => ['required', 'in:car,motorcycle,cng,bus,truck'],
            'make' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['required', 'integer', 'between:1970,' . (date('Y') + 1)],
            'color' => ['required', 'string', 'max:50'],
            'license_plate' => ['required', 'string', 'regex:/^[A-Z0-9\s\-]+$/i', 'unique:vehicles,license_plate'],
            'is_default' => ['boolean'],
            'documents.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        $vehicleData = $request->only([
            'type', 'make', 'model', 'year', 'color', 'license_plate', 'is_default'
        ]);

        $vehicleData['user_id'] = auth()->id();

        $vehicle = $this->vehicleService->createVehicle($vehicleData);

        // Handle document uploads
        if ($request->hasFile('documents')) {
            $this->uploadVehicleDocuments($vehicle, $request->file('documents'));
        }

        return redirect()->route('visitor.vehicles.index')
                        ->with('success', __('vehicles.created_successfully'));
    }

    /**
     * Show vehicle details
     */
    public function show(Vehicle $vehicle): View
    {
        $this->authorize('view', $vehicle);

        return view('visitor.vehicles.show', compact('vehicle'));
    }

    /**
     * Show edit vehicle form
     */
    public function edit(Vehicle $vehicle): View
    {
        $this->authorize('update', $vehicle);

        $vehicleTypes = $this->vehicleService->getVehicleTypes();

        return view('visitor.vehicles.edit', compact('vehicle', 'vehicleTypes'));
    }

    /**
     * Update vehicle
     */
    public function update(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $this->authorize('update', $vehicle);

        $request->validate([
            'type' => ['required', 'in:car,motorcycle,cng,bus,truck'],
            'make' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['required', 'integer', 'between:1970,' . (date('Y') + 1)],
            'color' => ['required', 'string', 'max:50'],
            'license_plate' => ['required', 'string', 'regex:/^[A-Z0-9\s\-]+$/i', 'unique:vehicles,license_plate,' . $vehicle->id],
            'is_default' => ['boolean'],
        ]);

        $vehicleData = $request->only([
            'type', 'make', 'model', 'year', 'color', 'license_plate', 'is_default'
        ]);

        $this->vehicleService->updateVehicle($vehicle->id, $vehicleData);

        return redirect()->route('visitor.vehicles.index')
                        ->with('success', __('vehicles.updated_successfully'));
    }

    /**
     * Delete vehicle
     */
    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        $this->authorize('delete', $vehicle);

        // Check if vehicle has active bookings
        if ($vehicle->hasActiveBookings()) {
            return back()->withErrors(['vehicle' => __('vehicles.has_active_bookings')]);
        }

        $this->vehicleService->deleteVehicle($vehicle->id);

        return redirect()->route('visitor.vehicles.index')
                        ->with('success', __('vehicles.deleted_successfully'));
    }

    /**
     * Set vehicle as default
     */
    public function setDefault(Vehicle $vehicle): RedirectResponse
    {
        $this->authorize('update', $vehicle);

        $this->vehicleService->setDefaultVehicle(auth()->id(), $vehicle->id);

        return redirect()->route('visitor.vehicles.index')
                        ->with('success', __('vehicles.default_set'));
    }

    /**
     * Upload vehicle documents
     */
    public function uploadDocuments(Request $request, Vehicle $vehicle): JsonResponse
    {
        $this->authorize('update', $vehicle);

        $request->validate([
            'documents.*' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        $uploadedDocuments = $this->uploadVehicleDocuments($vehicle, $request->file('documents'));

        return response()->json([
            'success' => true,
            'message' => __('vehicles.documents_uploaded'),
            'documents' => $uploadedDocuments
        ]);
    }

    /**
     * Handle document upload
     */
    protected function uploadVehicleDocuments(Vehicle $vehicle, array $documents): array
    {
        $uploadedDocuments = [];

        foreach ($documents as $document) {
            $path = $this->fileUploadService->uploadFile(
                $document,
                "vehicles/{$vehicle->id}/documents",
                'doc_' . time() . '_' . uniqid()
            );

            $documentRecord = $vehicle->documents()->create([
                'type' => 'registration',
                'file_path' => $path,
                'file_name' => $document->getClientOriginalName(),
                'file_size' => $document->getSize(),
                'uploaded_by' => auth()->id(),
            ]);

            $uploadedDocuments[] = $documentRecord;
        }

        return $uploadedDocuments;
    }

    // API Methods

    /**
     * API: List vehicles
     */
    public function apiIndex(): JsonResponse
    {
        $vehicles = $this->vehicleService->getUserVehicles(auth()->id());

        return response()->json([
            'success' => true,
            'data' => $vehicles
        ]);
    }

    /**
     * API: Store vehicle
     */
    public function apiStore(Request $request): JsonResponse
    {
        $request->validate([
            'type' => ['required', 'in:car,motorcycle,cng,bus,truck'],
            'make' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['required', 'integer', 'between:1970,' . (date('Y') + 1)],
            'color' => ['required', 'string', 'max:50'],
            'license_plate' => ['required', 'string', 'regex:/^[A-Z0-9\s\-]+$/i', 'unique:vehicles,license_plate'],
            'is_default' => ['boolean'],
        ]);

        $vehicleData = $request->only([
            'type', 'make', 'model', 'year', 'color', 'license_plate', 'is_default'
        ]);

        $vehicleData['user_id'] = auth()->id();

        $vehicle = $this->vehicleService->createVehicle($vehicleData);

        return response()->json([
            'success' => true,
            'data' => $vehicle,
            'message' => __('vehicles.created_successfully')
        ]);
    }

    /**
     * API: Show vehicle
     */
    public function apiShow(Vehicle $vehicle): JsonResponse
    {
        $this->authorize('view', $vehicle);

        return response()->json([
            'success' => true,
            'data' => $vehicle->load('documents', 'verifications')
        ]);
    }

    /**
     * API: Update vehicle
     */
    public function apiUpdate(Request $request, Vehicle $vehicle): JsonResponse
    {
        $this->authorize('update', $vehicle);

        $request->validate([
            'type' => ['sometimes', 'in:car,motorcycle,cng,bus,truck'],
            'make' => ['sometimes', 'string', 'max:100'],
            'model' => ['sometimes', 'string', 'max:100'],
            'year' => ['sometimes', 'integer', 'between:1970,' . (date('Y') + 1)],
            'color' => ['sometimes', 'string', 'max:50'],
            'license_plate' => ['sometimes', 'string', 'regex:/^[A-Z0-9\s\-]+$/i', 'unique:vehicles,license_plate,' . $vehicle->id],
            'is_default' => ['boolean'],
        ]);

        $vehicleData = $request->only([
            'type', 'make', 'model', 'year', 'color', 'license_plate', 'is_default'
        ]);

        $updatedVehicle = $this->vehicleService->updateVehicle($vehicle->id, $vehicleData);

        return response()->json([
            'success' => true,
            'data' => $updatedVehicle,
            'message' => __('vehicles.updated_successfully')
        ]);
    }

    /**
     * API: Delete vehicle
     */
    public function apiDestroy(Vehicle $vehicle): JsonResponse
    {
        $this->authorize('delete', $vehicle);

        // Check if vehicle has active bookings
        if ($vehicle->hasActiveBookings()) {
            return response()->json([
                'success' => false,
                'message' => __('vehicles.has_active_bookings')
            ], 400);
        }

        $this->vehicleService->deleteVehicle($vehicle->id);

        return response()->json([
            'success' => true,
            'message' => __('vehicles.deleted_successfully')
        ]);
    }
}
