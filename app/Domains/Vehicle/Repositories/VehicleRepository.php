<?php

namespace App\Domains\Vehicle\Repositories;

use App\Domains\Vehicle\Models\Vehicle;
use App\Shared\Contracts\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class VehicleRepository implements RepositoryInterface
{
    protected $model;

    public function __construct(Vehicle $model)
    {
        $this->model = $model;
    }

    /**
     * Find a resource by ID.
     */
    public function find(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * Get all resources with optional filters.
     */
    public function getAll(array $filters = [])
    {
        return $this->getAllVehicles($filters);
    }

    /**
     * Get paginated results.
     */
    public function paginate(array $filters = [], int $perPage = 15)
    {
        return $this->getAllVehicles($filters);
    }

    /**
     * Count total resources.
     */
    public function count(array $filters = []): int
    {
        $query = $this->model->query();

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        return $query->count();
    }

    /**
     * Create a new vehicle.
     */
    public function create(array $data): Vehicle
    {
        return $this->model->create($data);
    }

    /**
     * Update vehicle.
     */
    public function update($model, array $data)
    {
        $model->update($data);
        return $model->fresh();
    }

    /**
     * Find vehicle by registration number.
     */
    public function findByRegistration(string $registrationNumber): ?Vehicle
    {
        return $this->model->where('registration_number', $registrationNumber)->first();
    }

    /**
     * Get vehicles for a specific user.
     */
    public function getUserVehicles(int $userId, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->where('user_id', $userId);

        // Apply filters
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['verification_status'])) {
            $query->where('verification_status', $filters['verification_status']);
        }

        if (isset($filters['vehicle_type'])) {
            $query->where('vehicle_type', $filters['vehicle_type']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('registration_number', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('model', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('brand', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->with(['verificationLogs', 'manualVerifications'])
                    ->orderBy('created_at', 'desc')
                    ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get all vehicles with pagination.
     */
    public function getAllVehicles(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->with(['user', 'verificationLogs']);

        // Apply filters
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['verification_status'])) {
            $query->where('verification_status', $filters['verification_status']);
        }

        if (isset($filters['vehicle_type'])) {
            $query->where('vehicle_type', $filters['vehicle_type']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('registration_number', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('model', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('brand', 'like', '%' . $filters['search'] . '%')
                  ->orWhereHas('user', function ($userQuery) use ($filters) {
                      $userQuery->where('name', 'like', '%' . $filters['search'] . '%')
                               ->orWhere('email', 'like', '%' . $filters['search'] . '%');
                  });
            });
        }

        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')
                    ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get vehicles pending verification.
     */
    public function getPendingVerification(): Collection
    {
        return $this->model->where('verification_status', 'pending')
                          ->with(['user', 'verificationLogs'])
                          ->orderBy('created_at', 'asc')
                          ->get();
    }

    /**
     * Get vehicle statistics.
     */
    public function getStatistics(): array
    {
        return [
            'total' => $this->model->count(),
            'active' => $this->model->where('status', 'active')->count(),
            'inactive' => $this->model->where('status', 'inactive')->count(),
            'pending_verification' => $this->model->where('verification_status', 'pending')->count(),
            'verified' => $this->model->where('verification_status', 'verified')->count(),
            'failed_verification' => $this->model->where('verification_status', 'failed')->count(),
            'manual_verified' => $this->model->where('verification_status', 'manual_verified')->count(),
            'by_type' => $this->model->selectRaw('vehicle_type, COUNT(*) as count')
                                   ->groupBy('vehicle_type')
                                   ->pluck('count', 'vehicle_type')
                                   ->toArray(),
        ];
    }

    /**
     * Delete vehicle.
     */
    public function delete($model): bool
    {
        return $model->delete();
    }

    /**
     * Bulk update vehicles.
     */
    public function bulkUpdate(array $vehicleIds, array $data): int
    {
        return $this->model->whereIn('id', $vehicleIds)->update($data);
    }
}
