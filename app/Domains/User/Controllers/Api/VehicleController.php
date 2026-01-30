<?php

namespace App\Domains\User\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Domains\Vehicle\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    /**
     * Get user's vehicles.
     */
    public function index(Request $request): JsonResponse
    {
        $vehicles = $request->user()->vehicles()
            ->with(['documents'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $vehicles
        ]);
    }

    /**
     * Create a new vehicle.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'vehicle_type' => 'required|in:car,motorcycle,bicycle',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1980|max:' . (date('Y') + 1),
            'license_plate' => 'required|string|unique:vehicles,license_plate',
            'color' => 'required|string|max:50',
            'engine_number' => 'nullable|string|max:100',
            'chassis_number' => 'nullable|string|max:100',
            'documents' => 'sometimes|array',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $vehicle = Vehicle::create([
                'user_id' => $request->user()->id,
                'vehicle_type' => $request->vehicle_type,
                'brand' => $request->brand,
                'model' => $request->model,
                'year' => $request->year,
                'license_plate' => strtoupper($request->license_plate),
                'color' => $request->color,
                'engine_number' => $request->engine_number,
                'chassis_number' => $request->chassis_number,
                'verification_status' => 'pending'
            ]);

            // Handle document uploads
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $index => $file) {
                    $path = $file->store('vehicles/documents', 'public');

                    $vehicle->documents()->create([
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'type' => $this->getDocumentType($file->getClientOriginalName()),
                        'uploaded_at' => now()
                    ]);
                }
            }

            $vehicle->load('documents');

            return response()->json([
                'success' => true,
                'message' => 'Vehicle registered successfully',
                'data' => $vehicle
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific vehicle.
     */
    public function show(Request $request, Vehicle $vehicle): JsonResponse
    {
        // Check ownership
        if ($vehicle->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $vehicle->load(['documents', 'bookings' => function ($query) {
            $query->latest()->take(5);
        }]);

        return response()->json([
            'success' => true,
            'data' => $vehicle
        ]);
    }

    /**
     * Update vehicle.
     */
    public function update(Request $request, Vehicle $vehicle): JsonResponse
    {
        // Check ownership
        if ($vehicle->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'brand' => 'sometimes|string|max:100',
            'model' => 'sometimes|string|max:100',
            'year' => 'sometimes|integer|min:1980|max:' . (date('Y') + 1),
            'color' => 'sometimes|string|max:50',
            'engine_number' => 'sometimes|nullable|string|max:100',
            'chassis_number' => 'sometimes|nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $vehicle->update($request->only([
                'brand', 'model', 'year', 'color', 'engine_number', 'chassis_number'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Vehicle updated successfully',
                'data' => $vehicle
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete vehicle.
     */
    public function destroy(Request $request, Vehicle $vehicle): JsonResponse
    {
        // Check ownership
        if ($vehicle->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Check if vehicle has active bookings
        if ($vehicle->bookings()->where('status', 'active')->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete vehicle with active bookings'
            ], 400);
        }

        try {
            // Delete documents from storage
            foreach ($vehicle->documents as $document) {
                if (Storage::disk('public')->exists($document->path)) {
                    Storage::disk('public')->delete($document->path);
                }
            }

            $vehicle->delete();

            return response()->json([
                'success' => true,
                'message' => 'Vehicle deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle deletion failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload vehicle documents.
     */
    public function uploadDocuments(Request $request, Vehicle $vehicle): JsonResponse
    {
        // Check ownership
        if ($vehicle->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'documents' => 'required|array',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $uploadedDocuments = [];

            foreach ($request->file('documents') as $file) {
                $path = $file->store('vehicles/documents', 'public');

                $document = $vehicle->documents()->create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $this->getDocumentType($file->getClientOriginalName()),
                    'uploaded_at' => now()
                ]);

                $uploadedDocuments[] = $document;
            }

            return response()->json([
                'success' => true,
                'message' => 'Documents uploaded successfully',
                'data' => $uploadedDocuments
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Document upload failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Determine document type from filename.
     */
    private function getDocumentType(string $filename): string
    {
        $filename = strtolower($filename);

        if (str_contains($filename, 'license') || str_contains($filename, 'driving')) {
            return 'driving_license';
        } elseif (str_contains($filename, 'registration') || str_contains($filename, 'reg')) {
            return 'registration';
        } elseif (str_contains($filename, 'insurance')) {
            return 'insurance';
        } elseif (str_contains($filename, 'fitness')) {
            return 'fitness_certificate';
        } else {
            return 'other';
        }
    }
}
