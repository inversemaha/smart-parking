<?php

namespace App\Domains\User\Repositories;

use App\Domains\User\Models\User;
use App\Shared\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository extends BaseRepository
{
    /**
     * Get the model class
     */
    protected function getModelClass(): string
    {
        return User::class;
    }

    /**
     * Get all users with filtering and pagination
     */
    public function getAllUsers(array $filters = []): Collection
    {
        $query = $this->query();

        // Apply filters
        if (!empty($filters['search'])) {
            $query->where(function (Builder $q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('phone', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['role'])) {
            $query->whereHas('roles', function (Builder $q) use ($filters) {
                $q->where('name', $filters['role']);
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (!empty($filters['locale'])) {
            $query->where('locale', $filters['locale']);
        }

        // Date range filters
        if (!empty($filters['created_from'])) {
            $query->where('created_at', '>=', $filters['created_from']);
        }

        if (!empty($filters['created_to'])) {
            $query->where('created_at', '<=', $filters['created_to']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Include relationships
        $query->with(['roles', 'vehicles', 'bookings']);

        // Pagination
        if (!empty($filters['paginate'])) {
            $perPage = $filters['per_page'] ?? 15;
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    /**
     * Search users by query
     */
    public function search(string $query): Collection
    {
        return $this->query()
            ->where(function (Builder $q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('email', 'like', '%' . $query . '%')
                  ->orWhere('phone', 'like', '%' . $query . '%');
            })
            ->with(['roles'])
            ->orderBy('name')
            ->limit(50)
            ->get();
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?User
    {
        return $this->query()
            ->where('email', $email)
            ->with(['roles'])
            ->first();
    }

    /**
     * Find user by phone
     */
    public function findByPhone(string $phone): ?User
    {
        return $this->query()
            ->where('phone', $phone)
            ->with(['roles'])
            ->first();
    }

    /**
     * Get active users
     */
    public function getActiveUsers(): Collection
    {
        return $this->query()
            ->where('is_active', true)
            ->with(['roles'])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get inactive users
     */
    public function getInactiveUsers(): Collection
    {
        return $this->query()
            ->where('is_active', false)
            ->with(['roles'])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get users by role
     */
    public function getUsersByRole(string $roleName): Collection
    {
        return $this->query()
            ->whereHas('roles', function (Builder $q) use ($roleName) {
                $q->where('name', $roleName);
            })
            ->with(['roles'])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get users with multiple roles
     */
    public function getUsersByRoles(array $roleNames): Collection
    {
        return $this->query()
            ->whereHas('roles', function (Builder $q) use ($roleNames) {
                $q->whereIn('name', $roleNames);
            })
            ->with(['roles'])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get user statistics
     */
    public function getUserStatistics(): array
    {
        $totalUsers = $this->query()->count();
        $activeUsers = $this->query()->where('is_active', true)->count();
        $inactiveUsers = $this->query()->where('is_active', false)->count();
        $verifiedUsers = $this->query()->whereNotNull('email_verified_at')->count();
        $unverifiedUsers = $this->query()->whereNull('email_verified_at')->count();

        // Users by role
        $usersByRole = $this->query()
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->join('roles', 'user_roles.role_id', '=', 'roles.id')
            ->selectRaw('roles.name as role_name, COUNT(*) as count')
            ->groupBy('roles.name')
            ->pluck('count', 'role_name')
            ->toArray();

        // Recent registrations (last 30 days)
        $recentRegistrations = $this->query()
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        return [
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'inactive_users' => $inactiveUsers,
            'verified_users' => $verifiedUsers,
            'unverified_users' => $unverifiedUsers,
            'users_by_role' => $usersByRole,
            'recent_registrations' => $recentRegistrations,
        ];
    }

    /**
     * Get users with expired tokens (for cleanup)
     */
    public function getUsersWithExpiredTokens(): Collection
    {
        return $this->query()
            ->whereHas('tokens', function (Builder $q) {
                $q->where('expires_at', '<', now());
            })
            ->with(['tokens'])
            ->get();
    }

    /**
     * Get users by locale
     */
    public function getUsersByLocale(string $locale): Collection
    {
        return $this->query()
            ->where('locale', $locale)
            ->with(['roles'])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get recently active users
     */
    public function getRecentlyActiveUsers(int $days = 7): Collection
    {
        return $this->query()
            ->where('last_login_at', '>=', now()->subDays($days))
            ->with(['roles'])
            ->orderBy('last_login_at', 'desc')
            ->get();
    }

    /**
     * Soft delete user
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Restore soft deleted user
     */
    public function restore(int $userId): bool
    {
        $user = User::withTrashed()->find($userId);
        if ($user) {
            return $user->restore();
        }
        return false;
    }

    /**
     * Permanently delete user
     */
    public function forceDelete(int $userId): bool
    {
        $user = User::withTrashed()->find($userId);
        if ($user) {
            return $user->forceDelete();
        }
        return false;
    }
}
