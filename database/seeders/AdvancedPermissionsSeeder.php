<?php

use Illuminate\Database\Seeder;
use App\Domains\User\Models\Permission;
use App\Domains\User\Models\Role;

class AdvancedPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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

            // Payment Management Permissions
            ['name' => 'manage_payments', 'module' => 'payments', 'description' => 'Full payment management access'],
            ['name' => 'view_all_payments', 'module' => 'payments', 'description' => 'View all user payments'],
            ['name' => 'refund_payments', 'module' => 'payments', 'description' => 'Process payment refunds'],

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

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                array_merge($permission, ['guard_name' => 'web', 'is_active' => true])
            );
        }

        // Create roles with specific permissions
        $this->createRoles();

        $this->command->info('Advanced permissions and roles system seeded successfully!');
    }

    /**
     * Create roles with appropriate permissions.
     */
    private function createRoles(): void
    {
        // Super Admin Role (has all permissions)
        $superAdmin = Role::firstOrCreate(
            ['name' => 'super-admin'],
            [
                'guard_name' => 'web',
                'description' => 'Super Administrator with full system access',
                'is_active' => true
            ]
        );
        $superAdmin->permissions()->sync(Permission::all()->pluck('id'));

        // Admin Role (most permissions except super admin functions)
        $admin = Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'guard_name' => 'web',
                'description' => 'Administrator with management access',
                'is_active' => true
            ]
        );
        $adminPermissions = Permission::whereIn('name', [
            'admin.dashboard.view', 'manage_users', 'view_users', 'create_users', 'edit_users',
            'manage_parking', 'view_parking_areas', 'create_parking_areas', 'edit_parking_areas',
            'manage_bookings', 'view_all_bookings', 'cancel_bookings',
            'manage_vehicles', 'verify_vehicles', 'view_all_vehicles',
            'manage_payments', 'view_all_payments', 'refund_payments',
            'view_reports', 'export_reports', 'view_financial_reports',
            'view_logs', 'view_user_sessions', 'view_access_logs'
        ])->pluck('id');
        $admin->permissions()->sync($adminPermissions);

        // Manager Role (limited admin access)
        $manager = Role::firstOrCreate(
            ['name' => 'manager'],
            [
                'guard_name' => 'web',
                'description' => 'Manager with limited administrative access',
                'is_active' => true
            ]
        );
        $managerPermissions = Permission::whereIn('name', [
            'admin.dashboard.view', 'view_users', 'manage_parking', 'view_parking_areas',
            'view_all_bookings', 'manage_vehicles', 'view_all_vehicles',
            'view_reports', 'view_logs'
        ])->pluck('id');
        $manager->permissions()->sync($managerPermissions);

        // Gate Operator Role
        $gateOperator = Role::firstOrCreate(
            ['name' => 'gate-operator'],
            [
                'guard_name' => 'web',
                'description' => 'Gate operator with gate control access',
                'is_active' => true
            ]
        );
        $gatePermissions = Permission::whereIn('name', [
            'user.dashboard.view', 'operate.gates', 'view.gate.logs',
            'view_all_bookings', 'view_all_vehicles'
        ])->pluck('id');
        $gateOperator->permissions()->sync($gatePermissions);

        // User Role (standard user permissions)
        $user = Role::firstOrCreate(
            ['name' => 'user'],
            [
                'guard_name' => 'web',
                'description' => 'Standard user with basic access',
                'is_active' => true
            ]
        );
        $userPermissions = Permission::whereIn('name', [
            'user.dashboard.view', 'edit_own_profile', 'view_own_bookings', 'manage_own_vehicles'
        ])->pluck('id');
        $user->permissions()->sync($userPermissions);
    }
}
