<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\User\Models\User;
use App\Domains\User\Models\Role;
use App\Domains\User\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DefaultRolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default permissions
        $permissions = [
            // Admin permissions
            ['name' => 'admin.dashboard.view', 'module' => 'admin', 'description' => 'View admin dashboard'],
            ['name' => 'admin.users.manage', 'module' => 'admin', 'description' => 'Manage users'],
            ['name' => 'admin.vehicles.manage', 'module' => 'admin', 'description' => 'Manage vehicles'],
            ['name' => 'admin.bookings.manage', 'module' => 'admin', 'description' => 'Manage all bookings'],
            ['name' => 'admin.payments.manage', 'module' => 'admin', 'description' => 'Manage payments'],
            ['name' => 'admin.reports.view', 'module' => 'admin', 'description' => 'View reports'],
            ['name' => 'admin.system.manage', 'module' => 'admin', 'description' => 'System management'],

            // Permission management
            ['name' => 'manage_permissions', 'module' => 'admin', 'description' => 'Manage permissions'],
            ['name' => 'manage_roles', 'module' => 'admin', 'description' => 'Manage roles'],

            // Vehicle permissions
            ['name' => 'vehicles.verify', 'module' => 'vehicle', 'description' => 'Verify vehicles'],
            ['name' => 'vehicles.view', 'module' => 'vehicle', 'description' => 'View vehicles'],
            ['name' => 'vehicles.create', 'module' => 'vehicle', 'description' => 'Create vehicles'],
            ['name' => 'vehicles.edit', 'module' => 'vehicle', 'description' => 'Edit vehicles'],
            ['name' => 'vehicles.delete', 'module' => 'vehicle', 'description' => 'Delete vehicles'],

            // User permissions
            ['name' => 'users.view', 'module' => 'user', 'description' => 'View users'],
            ['name' => 'users.create', 'module' => 'user', 'description' => 'Create users'],
            ['name' => 'users.edit', 'module' => 'user', 'description' => 'Edit users'],
            ['name' => 'users.delete', 'module' => 'user', 'description' => 'Delete users'],

            // Booking permissions
            ['name' => 'bookings.view', 'module' => 'booking', 'description' => 'View bookings'],
            ['name' => 'bookings.create', 'module' => 'booking', 'description' => 'Create bookings'],
            ['name' => 'bookings.edit', 'module' => 'booking', 'description' => 'Edit bookings'],
            ['name' => 'bookings.cancel', 'module' => 'booking', 'description' => 'Cancel bookings'],

            // Payment permissions
            ['name' => 'payments.view', 'module' => 'payment', 'description' => 'View payments'],
            ['name' => 'payments.process', 'module' => 'payment', 'description' => 'Process payments'],

            // Gate permissions
            ['name' => 'gate.entry', 'module' => 'gate', 'description' => 'Vehicle entry operations'],
            ['name' => 'gate.exit', 'module' => 'gate', 'description' => 'Vehicle exit operations'],
            ['name' => 'gate.scan', 'module' => 'gate', 'description' => 'QR code scanning'],

            // Parking permissions
            ['name' => 'parking.manage', 'module' => 'parking', 'description' => 'Manage parking areas and slots'],
            ['name' => 'parking.view', 'module' => 'parking', 'description' => 'View parking information'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                [
                    'guard_name' => 'web',
                    'module' => $permission['module'],
                    'description' => $permission['description'],
                    'is_active' => true
                ]
            );
        }

        // Create default roles
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super-admin'],
            [
                'guard_name' => 'web',
                'description' => 'Super Administrator with full access',
                'is_active' => true
            ]
        );

        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'guard_name' => 'web',
                'description' => 'Administrator with management access',
                'is_active' => true
            ]
        );

        $operatorRole = Role::firstOrCreate(
            ['name' => 'operator'],
            [
                'guard_name' => 'web',
                'description' => 'Gate operator with limited access',
                'is_active' => true
            ]
        );

        $userRole = Role::firstOrCreate(
            ['name' => 'user'],
            [
                'guard_name' => 'web',
                'description' => 'Regular user with basic access',
                'is_active' => true
            ]
        );

        // Assign permissions to roles
        $allPermissions = Permission::all();

        // Super admin gets all permissions
        $superAdminRole->permissions()->syncWithoutDetaching($allPermissions->pluck('id')->toArray());

        // Admin gets most permissions except super-admin level ones
        $adminPermissions = Permission::whereNotIn('name', [
            'manage_permissions', 'manage_roles'
        ])->pluck('id')->toArray();
        $adminRole->permissions()->syncWithoutDetaching($adminPermissions);

        // Operator gets gate and basic permissions
        $operatorPermissions = Permission::whereIn('module', ['gate', 'vehicle', 'booking'])
            ->whereIn('name', [
                'gate.entry', 'gate.exit', 'gate.scan',
                'vehicles.view', 'bookings.view'
            ])->pluck('id')->toArray();
        $operatorRole->permissions()->syncWithoutDetaching($operatorPermissions);

        // User gets basic permissions
        $userPermissions = Permission::whereIn('name', [
            'vehicles.view', 'vehicles.create', 'vehicles.edit',
            'bookings.view', 'bookings.create', 'bookings.cancel',
            'payments.view'
        ])->pluck('id')->toArray();
        $userRole->permissions()->syncWithoutDetaching($userPermissions);

        // Create default super admin user if not exists
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

        // Assign super-admin role to the default admin
        if (!$superAdmin->hasRole('super-admin')) {
            $superAdmin->assignRole('super-admin');
        }

        // Create default test user
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

        // Assign user role to the test user
        if (!$testUser->hasRole('user')) {
            $testUser->assignRole('user');
        }

        $this->command->info('Default roles, permissions, and users created successfully!');
    }
}
