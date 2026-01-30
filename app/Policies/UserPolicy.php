<?php

namespace App\Policies;

use App\Domains\User\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admin, Manager can view all users
        return $user->hasAnyRole(['admin', 'super-admin', 'manager']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Admin can view any user
        if ($user->hasAnyRole(['admin', 'super-admin'])) {
            return true;
        }

        // Users can view their own profile
        if ($user->id === $model->id) {
            return true;
        }

        // Managers can view users in their organization
        if ($user->hasRole('manager')) {
            return true; // Implement organization logic if needed
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admin and Manager can create users
        return $user->hasAnyRole(['admin', 'super-admin', 'manager']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Admin can update any user
        if ($user->hasAnyRole(['admin', 'super-admin'])) {
            return true;
        }

        // Users can update their own profile
        if ($user->id === $model->id) {
            return true;
        }

        // Managers can update users in their organization
        if ($user->hasRole('manager')) {
            return true; // Implement organization logic if needed
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Only super-admin can delete users
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Admin can delete regular users but not other admins
        if ($user->hasRole('admin')) {
            return !$model->hasAnyRole(['admin', 'super-admin']);
        }

        // Users cannot delete themselves or others
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can manage roles.
     */
    public function manageRoles(User $user, User $model): bool
    {
        // Super-admin can manage any roles
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Admin can assign/remove roles except admin/super-admin
        if ($user->hasRole('admin')) {
            return !$model->hasAnyRole(['admin', 'super-admin']);
        }

        return false;
    }

    /**
     * Determine whether the user can activate/deactivate accounts.
     */
    public function manageStatus(User $user, User $model): bool
    {
        // Admin can manage status of regular users
        if ($user->hasAnyRole(['admin', 'super-admin'])) {
            // Cannot deactivate super-admin or themselves
            if ($model->hasRole('super-admin') || $user->id === $model->id) {
                return $user->hasRole('super-admin');
            }
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view user reports.
     */
    public function viewReports(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin', 'accountant', 'viewer']);
    }

    /**
     * Determine whether the user can access admin features.
     */
    public function accessAdmin(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin']);
    }

    /**
     * Determine whether the user can access operator features.
     */
    public function accessOperator(User $user): bool
    {
        return $user->hasAnyRole(['operator', 'admin', 'super-admin']);
    }

    /**
     * Determine whether the user can access gate operator features.
     */
    public function accessGateOperator(User $user): bool
    {
        return $user->hasAnyRole(['gate-operator', 'admin', 'super-admin']);
    }

    /**
     * Determine whether the user can access accountant features.
     */
    public function accessAccountant(User $user): bool
    {
        return $user->hasAnyRole(['accountant', 'admin', 'super-admin']);
    }

    /**
     * Determine whether the user can access basic features.
     */
    public function accessBasic(User $user): bool
    {
        // All authenticated users have basic access unless deactivated
        return $user->is_active;
    }
}
