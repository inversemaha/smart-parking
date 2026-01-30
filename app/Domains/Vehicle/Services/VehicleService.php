<?php

namespace App\Domains\Vehicle\Services;

use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Vehicle\Models\BrtaVerificationLog;
use App\Domains\Vehicle\Repositories\VehicleRepository;
use App\Shared\Contracts\VehicleServiceInterface;
use App\Shared\DTOs\VehicleDTO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehicleService implements VehicleServiceInterface
{
    protected $vehicleRepository;
    protected $brtaService;

    public function __construct(VehicleRepository $vehicleRepository, BrtaService $brtaService)
    {
        $this->vehicleRepository = $vehicleRepository;
        $this->brtaService = $brtaService;
    }

    /**
     * Process business logic.
     */
    public function process(array $data)
    {
        return $this->createVehicle($data);
    }

    /**
     * Validate business rules.
     */
    public function validate(array $data): array
    {
        $rules = [
            'registration_number' => 'required|string|max:20|unique:vehicles,registration_number',
            'vehicle_type' => 'required|in:car,motorcycle,bus,truck',
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'required|string|max:30',
        ];

        return $rules;
    }

    /**
     * Handle domain events.
     */
    public function handleEvent(string $event, array $data): bool
    {
        switch ($event) {
            case 'vehicle.created':
                return $this->onVehicleCreated($data);
            case 'vehicle.verified':
                return $this->onVehicleVerified($data);
            default:
                return false;
        }
    }

    /**
     * Create a new vehicle.
     */
    public function createVehicle(array $data): Vehicle
    {
        DB::beginTransaction();

        try {
            $vehicle = $this->vehicleRepository->create($data);

            // Initiate BRTA verification if enabled
            if ($this->shouldVerifyWithBrta($vehicle)) {
                $this->initiateVerification($vehicle);
            }

            DB::commit();

            return $vehicle;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create vehicle: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update vehicle information.
     */
    public function updateVehicle(Vehicle $vehicle, array $data): Vehicle
    {
        // If registration number changed, re-verify
        if (isset($data['registration_number']) &&
            $data['registration_number'] !== $vehicle->registration_number) {
            $data['verification_status'] = 'pending';
            $data['verified_at'] = null;
            $data['verified_by'] = null;
        }

        $vehicle = $this->vehicleRepository->update($vehicle, $data);

        // Re-initiate verification if needed
        if ($vehicle->verification_status === 'pending' &&
            $this->shouldVerifyWithBrta($vehicle)) {
            $this->initiateVerification($vehicle);
        }

        return $vehicle;
    }

    /**
     * Initiate BRTA verification.
     */
    public function initiateVerification(Vehicle $vehicle): bool
    {
        try {
            return $this->brtaService->verifyVehicle($vehicle);
        } catch (\Exception $e) {
            Log::error('BRTA verification failed for vehicle ' . $vehicle->id . ': ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Manually verify vehicle.
     */
    public function manualVerification(Vehicle $vehicle, int $verifiedBy, string $status, string $reason, array $documents = null): bool
    {
        DB::beginTransaction();

        try {
            // Create manual verification record
            $vehicle->manualVerifications()->create([
                'verified_by' => $verifiedBy,
                'status' => $status,
                'reason' => $reason,
                'documents' => $documents,
                'verified_at' => now(),
            ]);

            // Update vehicle status
            $vehicle->update([
                'verification_status' => $status === 'approved' ? 'manual_verified' : 'failed',
                'verified_at' => now(),
                'verified_by' => $verifiedBy,
                'verification_notes' => $reason,
            ]);

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Manual verification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get vehicles for user.
     */
    public function getUserVehicles(int $userId, array $filters = [])
    {
        return $this->vehicleRepository->getUserVehicles($userId, $filters);
    }

    /**
     * Check if vehicle should be verified with BRTA.
     */
    private function shouldVerifyWithBrta(Vehicle $vehicle): bool
    {
        return config('services.brta.enabled', false) &&
               $vehicle->verification_status === 'pending';
    }

    /**
     * Get user vehicle count.
     */
    public function getUserVehicleCount(int $userId): int
    {
        return Vehicle::where('user_id', $userId)->count();
    }

    /**
     * Get user's recent vehicles.
     */
    public function getUserRecentVehicles(int $userId, int $limit = 5)
    {
        return Vehicle::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
