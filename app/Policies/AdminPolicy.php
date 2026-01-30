<?php

namespace App\Policies;

use App\Domains\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * AdminPolicy
 *
 * Policy for administrative operations and access control.
 */
class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Check if user can access admin dashboard.
     */
    public function viewDashboard(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.dashboard.view');
    }

    /**
     * Check if user can manage users.
     */
    public function manageUsers(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.users.manage');
    }

    /**
     * Check if user can view users.
     */
    public function viewUsers(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.users.view');
    }

    /**
     * Check if user can create users.
     */
    public function createUsers(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.users.create');
    }

    /**
     * Check if user can edit users.
     */
    public function editUsers(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.users.edit');
    }

    /**
     * Check if user can delete users.
     */
    public function deleteUsers(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.users.delete');
    }

    /**
     * Check if user can suspend/activate users.
     */
    public function suspendUsers(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.users.suspend');
    }

    /**
     * Check if user can manage roles and permissions.
     */
    public function manageRoles(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.roles.manage');
    }

    /**
     * Check if user can view roles.
     */
    public function viewRoles(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.roles.view');
    }

    /**
     * Check if user can manage permissions.
     */
    public function managePermissions(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.permissions.manage');
    }

    /**
     * Check if user can verify vehicles.
     */
    public function verifyVehicles(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.vehicles.verify');
    }

    /**
     * Check if user can manage parking locations.
     */
    public function manageParkingLocations(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.parking.manage');
    }

    /**
     * Check if user can view parking management.
     */
    public function viewParkingManagement(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.parking.view');
    }

    /**
     * Check if user can manage bookings.
     */
    public function manageBookings(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.bookings.manage');
    }

    /**
     * Check if user can view bookings.
     */
    public function viewBookings(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.bookings.view');
    }

    /**
     * Check if user can manage payments.
     */
    public function managePayments(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.payments.manage');
    }

    /**
     * Check if user can view payments.
     */
    public function viewPayments(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.payments.view');
    }

    /**
     * Check if user can access reports.
     */
    public function viewReports(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.reports.view');
    }

    /**
     * Check if user can export reports.
     */
    public function exportReports(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.reports.export');
    }

    /**
     * Check if user can view audit logs.
     */
    public function viewAuditLogs(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.audit.view');
    }

    /**
     * Check if user can export audit logs.
     */
    public function exportAuditLogs(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.audit.export');
    }

    /**
     * Check if user can manage system settings.
     */
    public function manageSystemSettings(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.settings.manage');
    }

    /**
     * Check if user can view system settings.
     */
    public function viewSystemSettings(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.settings.view');
    }

    /**
     * Check if user can access system monitoring.
     */
    public function viewSystemMonitoring(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.system.monitor');
    }

    /**
     * Check if user can manage gates.
     */
    public function manageGates(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.gates.manage');
    }

    /**
     * Check if user can view gate operations.
     */
    public function viewGateOperations(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.gates.view');
    }

    /**
     * Check if user can perform emergency operations.
     */
    public function performEmergencyOperations(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.emergency.manage');
    }

    /**
     * Check if user can clear system cache.
     */
    public function clearCache(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.system.cache.clear');
    }

    /**
     * Check if user can broadcast messages.
     */
    public function broadcastMessages(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermission('admin.messages.broadcast');
    }

    /**
     * Universal admin check - if user has admin role, allow everything.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }
}
