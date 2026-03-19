<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\User\Models\User;
use App\Domains\User\Models\Role;
use App\Domains\User\Models\Permission;
use Illuminate\Support\Facades\Hash;

/**
 * @deprecated Use UnifiedPermissionsSeeder instead
 * 
 * This seeder has been consolidated into UnifiedPermissionsSeeder.php
 * UnifiedPermissionsSeeder provides:
 * - 117 comprehensive permissions covering ALL modules
 * - 7 roles with appropriate permission assignments
 * - Admin role with complete access to all modules
 * - Default users for testing
 */
class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createAllPermissions();
        $this->createAllRoles();
        $this->assignPermissionsToRoles();
        $this->createDefaultUsers();

        $this->command->info('✅ All roles, permissions, and users created successfully!');
    }

    /**
     * Create all system permissions
     */
    private function createAllPermissions(): void
    {
        $permissions = [
            // Dashboard Permissions
            ['name' => 'admin.dashboard.view', 'module' => 'dashboard', 'description' => 'View admin dashboard'],
            ['name' => 'user.dashboard.view', 'module' => 'dashboard', 'description' => 'View user dashboard'],

            // User Management Permissions
            ['name' => 'manage_users', 'module' => 'users', 'description' => 'Full user management access'],
            ['name' => 'view_users', 'module' => 'users', 'description' => 'View users list'],
            ['name' => 'create_users', 'module' => 'users', 'description' => 'Create new users'],
            ['name' => 'edit_users', 'module' => 'users', 'description' => 'Edit user details'],
            ['name' => 'delete_users', 'module' => 'users', 'description' => 'Delete users'],

            // Role & Permission Management
            ['name' => 'manage_permissions', 'module' => 'permissions', 'description' => 'Manage permissions system'],
            ['name' => 'manage_roles', 'module' => 'permissions', 'description' => 'Manage roles system'],
            ['name' => 'assign_roles', 'module' => 'permissions', 'description' => 'Assign roles to users'],

            // Parking Management Permissions
            ['name' => 'manage_parking', 'module' => 'parking', 'description' => 'Full parking management access'],
            ['name' => 'view_parking_areas', 'module' => 'parking', 'description' => 'View parking areas'],
            ['name' => 'create_parking_areas', 'module' => 'parking', 'description' => 'Create parking areas'],
            ['name' => 'edit_parking_areas', 'module' => 'parking', 'description' => 'Edit parking areas'],
            ['name' => 'delete_parking_areas', 'module' => 'parking', 'description' => 'Delete parking areas'],

            // Booking Management Permissions
            ['name' => 'manage_bookings', 'module' => 'bookings', 'description' => 'Full booking management access'],
            ['name' => 'view_all_bookings', 'module' => 'bookings', 'description' => 'View all user bookings'],
            ['name' => 'cancel_bookings', 'module' => 'bookings', 'description' => 'Cancel any booking'],
            ['name' => 'extend_bookings', 'module' => 'bookings', 'description' => 'Extend booking duration'],

            // Vehicle Management Permissions
            ['name' => 'manage_vehicles', 'module' => 'vehicles', 'description' => 'Full vehicle management access'],
            ['name' => 'verify_vehicles', 'module' => 'vehicles', 'description' => 'Verify vehicle registrations'],
            ['name' => 'view_all_vehicles', 'module' => 'vehicles', 'description' => 'View all user vehicles'],

            // Gate Operations Permissions
            ['name' => 'operate.gates', 'module' => 'gates', 'description' => 'Operate parking gates'],
            ['name' => 'override.gates', 'module' => 'gates', 'description' => 'Override gate operations'],
            ['name' => 'view.gate.logs', 'module' => 'gates', 'description' => 'View gate operation logs'],
            ['name' => 'operate_gate', 'module' => 'gate', 'description' => 'Gate operator dashboard access'],

            // Payment Management Permissions
            ['name' => 'manage_payments', 'module' => 'payments', 'description' => 'Full payment management access'],
            ['name' => 'view_all_payments', 'module' => 'payments', 'description' => 'View all user payments'],
            ['name' => 'refund_payments', 'module' => 'payments', 'description' => 'Process payment refunds'],

            // Invoice Management Permissions
            ['name' => 'manage_invoices', 'module' => 'invoices', 'description' => 'Full invoice management access'],
            ['name' => 'view_all_invoices', 'module' => 'invoices', 'description' => 'View all invoices'],
            ['name' => 'download_invoices', 'module' => 'invoices', 'description' => 'Download invoice PDFs'],
            ['name' => 'mark_invoice_paid', 'module' => 'invoices', 'description' => 'Mark invoices as paid'],

            // Report & Analytics Permissions
            ['name' => 'view_reports', 'module' => 'reports', 'description' => 'View system reports and analytics'],
            ['name' => 'export_reports', 'module' => 'reports', 'description' => 'Export reports to files'],
            ['name' => 'view_financial_reports', 'module' => 'reports', 'description' => 'View financial reports'],

            // System Management Permissions
            ['name' => 'manage_settings', 'module' => 'system', 'description' => 'Manage system settings'],
            ['name' => 'view_logs', 'module' => 'system', 'description' => 'View system logs'],
            ['name' => 'view_user_sessions', 'module' => 'system', 'description' => 'View user sessions'],
            ['name' => 'view_access_logs', 'module' => 'system', 'description' => 'View access logs'],

            // User Profile Permissions
            ['name' => 'edit_own_profile', 'module' => 'profile', 'description' => 'Edit own profile'],
            ['name' => 'view_own_bookings', 'module' => 'profile', 'description' => 'View own bookings'],
            ['name' => 'manage_own_vehicles', 'module' => 'profile', 'description' => 'Manage own vehicles'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                array_merge($permission, ['guard_name' => 'web', 'is_active' => true])
            );
        }

        $this->command->info('📋 Created ' . count($permissions) . ' permissions');
    }

    /**
     * Create all system roles
     */
    private function createAllRoles(): void
    {
        $roles = [
            ['name' => 'super-admin', 'description' => 'Super Administrator with full system access'],
            ['name' => 'admin', 'description' => 'Administrator with management access'],
            ['name' => 'manager', 'description' => 'Manager with limited administrative access'],
            ['name' => 'gate-operator', 'description' => 'Gate operator with gate control access'],
            ['name' => 'operator', 'description' => 'Gate operator with limited access'],
            ['name' => 'user', 'description' => 'Standard user with basic access'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                array_merge($role, ['guard_name' => 'web', 'is_active' => true])
            );
        }

        $this->command->info('🎭 Created ' . count($roles) . ' roles');
    }

    /**
     * Assign permissions to roles
     */
    private function assignPermissionsToRoles(): void
    {
        $allPermissions = Permission::all();

        // Super Admin - All permissions
        $superAdmin = Role::where('name', 'super-admin')->first();
        if ($superAdmin) {
            $superAdmin->permissions()->sync($allPermissions->pluck('id'));
        }

        // Admin - All except permission/role management
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $adminPermissions = Permission::whereNotIn('name', [
                'manage_permissions', 'manage_roles'
            ])->pluck('id');
            $admin->permissions()->sync($adminPermissions);
        }

        // Manager - Limited admin access
        $manager = Role::where('name', 'manager')->first();
        if ($manager) {
            $managerPermissions = Permission::whereIn('name', [
                'admin.dashboard.view', 'view_users', 'manage_parking', 'view_parking_areas',
                'view_all_bookings', 'manage_vehicles', 'view_all_vehicles',
                'view_reports', 'view_logs'
            ])->pluck('id');
            $manager->permissions()->sync($managerPermissions);
        }

        // Gate Operator - Gate control and basic permissions
        $gateOperator = Role::where('name', 'gate-operator')->first();
        if ($gateOperator) {
            $gatePermissions = Permission::whereIn('name', [
                'user.dashboard.view', 'operate.gates', 'view.gate.logs',
                'view_all_bookings', 'view_all_vehicles'
            ])->pluck('id');
            $gateOperator->permissions()->sync($gatePermissions);
        }

        // Operator - Gate operations only
        $operator = Role::where('name', 'operator')->first();
        if ($operator) {
            $operatorPermissions = Permission::whereIn('name', [
                'operate_gate', 'gate.entry', 'gate.exit', 'gate.scan',
                'vehicles.view', 'bookings.view', 'parking.view'
            ])->pluck('id');
            $operator->permissions()->sync($operatorPermissions);
        }

        // User - Basic permissions only
        $user = Role::where('name', 'user')->first();
        if ($user) {
            $userPermissions = Permission::whereIn('name', [
                'user.dashboard.view', 'edit_own_profile', 'view_own_bookings', 'manage_own_vehicles'
            ])->pluck('id');
            $user->permissions()->sync($userPermissions);
        }

        $this->command->info('✔️  Assigned permissions to all roles');
    }

    /**
     * Create default system users
     */
    private function createDefaultUsers(): void
    {
        // Create super admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@parking.com'],
            [
                'name' => 'Super Administrator',
                'phone' => '01700000000',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true
            ]
        );

        if (!$superAdmin->hasRole('super-admin')) {
            $superAdmin->assignRole('super-admin');
        }

        // Create test user
        $testUser = User::firstOrCreate(
            ['email' => 'user@parking.com'],
            [
                'name' => 'Test User',
                'phone' => '01700000001',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true
            ]
        );

        if (!$testUser->hasRole('user')) {
            $testUser->assignRole('user');
        }

        $this->command->info('👥 Created default users');
        $this->command->line('   Admin: admin@parking.com / password');
        $this->command->line('   User: user@parking.com / password');
    }
}
