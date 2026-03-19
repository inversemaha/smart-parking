<?php

namespace Database\Seeders;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use App\Domains\User\Models\User;
use App\Domains\User\Models\Role;
use App\Domains\User\Models\Permission;
use Illuminate\Support\Facades\Hash;

/**
 * ============================================================
 * UNIFIED PERMISSIONS SYSTEM - ALL-IN-ONE SEEDER
 * ============================================================
 * 
 * This seeder consolidates ALL permissions from:
 * - Routes (web.php)
 * - Policies (AdminPolicy, others)
 * - AuthServiceProvider (Gates)
 * - Controllers and Middleware
 * 
 * Admin role gets ALL permissions by default.
 * Other roles get specific subsets.
 */
class UnifiedPermissionsSeeder extends Seeder
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

        $this->command->info('✅ UNIFIED PERMISSIONS SYSTEM CREATED SUCCESSFULLY!');
        $this->command->line('');
        $this->command->info('📊 SUMMARY:');
        $this->command->line('   ✓ ' . Permission::count() . ' Total Permissions');
        $this->command->line('   ✓ ' . Role::count() . ' Total Roles');
        $this->command->line('   ✓ Admin role has ALL permissions');
        $this->command->line('   ✓ Super Admin role has ALL permissions');
    }

    /**
     * Create ALL system-wide permissions
     */
    private function createAllPermissions(): void
    {
        $this->command->line('📋 Creating Permissions...');
        
        $permissions = [
            // ===== DASHBOARD =====
            ['name' => 'admin.dashboard.view', 'module' => 'admin', 'description' => 'View admin dashboard'],
            ['name' => 'user.dashboard.view', 'module' => 'dashboard', 'description' => 'View user dashboard'],

            // ===== USER MANAGEMENT (Model + Route + Policy) =====
            ['name' => 'manage_users', 'module' => 'users', 'description' => 'Full user management'],
            ['name' => 'admin.users.manage', 'module' => 'users', 'description' => 'Policy: Manage users'],
            ['name' => 'admin.users.view', 'module' => 'users', 'description' => 'Policy: View users'],
            ['name' => 'admin.users.create', 'module' => 'users', 'description' => 'Policy: Create users'],
            ['name' => 'admin.users.edit', 'module' => 'users', 'description' => 'Policy: Edit users'],
            ['name' => 'admin.users.delete', 'module' => 'users', 'description' => 'Policy: Delete users'],
            ['name' => 'admin.users.suspend', 'module' => 'users', 'description' => 'Policy: Suspend users'],
            ['name' => 'view_users', 'module' => 'users', 'description' => 'View users list'],
            ['name' => 'create_users', 'module' => 'users', 'description' => 'Create new users'],
            ['name' => 'edit_users', 'module' => 'users', 'description' => 'Edit user details'],
            ['name' => 'delete_users', 'module' => 'users', 'description' => 'Delete users'],

            // ===== ROLES & PERMISSIONS MANAGEMENT =====
            ['name' => 'manage_permissions', 'module' => 'permissions', 'description' => 'Manage permissions system'],
            ['name' => 'manage_roles', 'module' => 'permissions', 'description' => 'Manage roles system'],
            ['name' => 'admin.roles.manage', 'module' => 'permissions', 'description' => 'Policy: Manage roles'],
            ['name' => 'admin.roles.view', 'module' => 'permissions', 'description' => 'Policy: View roles'],
            ['name' => 'admin.permissions.manage', 'module' => 'permissions', 'description' => 'Policy: Manage permissions'],
            ['name' => 'assign_roles', 'module' => 'permissions', 'description' => 'Assign roles to users'],

            // ===== PARKING MANAGEMENT (ALL PHASES) =====
            ['name' => 'manage_parking', 'module' => 'parking', 'description' => 'Full parking management (zones, floors, rates, gates, qr, sessions)'],
            ['name' => 'admin.parking.manage', 'module' => 'parking', 'description' => 'Policy: Manage parking'],
            ['name' => 'admin.parking.view', 'module' => 'parking', 'description' => 'Policy: View parking'],
            ['name' => 'view_parking_areas', 'module' => 'parking', 'description' => 'View parking areas'],
            ['name' => 'create_parking_areas', 'module' => 'parking', 'description' => 'Create parking areas'],
            ['name' => 'edit_parking_areas', 'module' => 'parking', 'description' => 'Edit parking areas'],
            ['name' => 'delete_parking_areas', 'module' => 'parking', 'description' => 'Delete parking areas'],
            
            // Parking Zones
            ['name' => 'parking.zones.view', 'module' => 'parking', 'description' => 'View parking zones'],
            ['name' => 'parking.zones.create', 'module' => 'parking', 'description' => 'Create parking zones'],
            ['name' => 'parking.zones.edit', 'module' => 'parking', 'description' => 'Edit parking zones'],
            ['name' => 'parking.zones.delete', 'module' => 'parking', 'description' => 'Delete parking zones'],
            
            // Parking Floors
            ['name' => 'parking.floors.view', 'module' => 'parking', 'description' => 'View parking floors'],
            ['name' => 'parking.floors.create', 'module' => 'parking', 'description' => 'Create parking floors'],
            ['name' => 'parking.floors.edit', 'module' => 'parking', 'description' => 'Edit parking floors'],
            ['name' => 'parking.floors.delete', 'module' => 'parking', 'description' => 'Delete parking floors'],
            
            // Parking Rates
            ['name' => 'parking.rates.view', 'module' => 'parking', 'description' => 'View parking rates'],
            ['name' => 'parking.rates.create', 'module' => 'parking', 'description' => 'Create parking rates'],
            ['name' => 'parking.rates.edit', 'module' => 'parking', 'description' => 'Edit parking rates'],
            ['name' => 'parking.rates.delete', 'module' => 'parking', 'description' => 'Delete parking rates'],
            ['name' => 'parking.rates.import', 'module' => 'parking', 'description' => 'Import parking rates'],
            
            // Parking Gates
            ['name' => 'parking.gates.view', 'module' => 'parking', 'description' => 'View parking gates'],
            ['name' => 'parking.gates.create', 'module' => 'parking', 'description' => 'Create parking gates'],
            ['name' => 'parking.gates.edit', 'module' => 'parking', 'description' => 'Edit parking gates'],
            ['name' => 'parking.gates.delete', 'module' => 'parking', 'description' => 'Delete parking gates'],
            ['name' => 'parking.gates.operate', 'module' => 'parking', 'description' => 'Operate parking gates'],
            
            // QR Codes
            ['name' => 'parking.qr_codes.view', 'module' => 'parking', 'description' => 'View QR codes'],
            ['name' => 'parking.qr_codes.create', 'module' => 'parking', 'description' => 'Create QR codes'],
            ['name' => 'parking.qr_codes.edit', 'module' => 'parking', 'description' => 'Edit QR codes'],
            ['name' => 'parking.qr_codes.delete', 'module' => 'parking', 'description' => 'Delete QR codes'],
            ['name' => 'parking.qr_codes.download', 'module' => 'parking', 'description' => 'Download QR codes'],
            
            // Parking Sessions (Entry/Exit)
            ['name' => 'parking.sessions.view', 'module' => 'parking', 'description' => 'View parking sessions'],
            ['name' => 'parking.sessions.create', 'module' => 'parking', 'description' => 'Create parking sessions'],
            ['name' => 'parking.sessions.edit', 'module' => 'parking', 'description' => 'Edit parking sessions'],
            ['name' => 'parking.sessions.delete', 'module' => 'parking', 'description' => 'Delete parking sessions'],
            ['name' => 'parking.sessions.exit', 'module' => 'parking', 'description' => 'Exit vehicle from session'],
            ['name' => 'parking.sessions.extend', 'module' => 'parking', 'description' => 'Extend parking session'],
            ['name' => 'parking.sessions.collect_payment', 'module' => 'parking', 'description' => 'Collect payment for session'],

            // ===== VEHICLE MANAGEMENT =====
            ['name' => 'manage_vehicles', 'module' => 'vehicles', 'description' => 'Full vehicle management'],
            ['name' => 'admin.vehicles.verify', 'module' => 'vehicles', 'description' => 'Policy: Verify vehicles'],
            ['name' => 'verify_vehicles', 'module' => 'vehicles', 'description' => 'Verify vehicle registrations'],
            ['name' => 'view_all_vehicles', 'module' => 'vehicles', 'description' => 'View all user vehicles'],
            ['name' => 'vehicles.view', 'module' => 'vehicles', 'description' => 'View vehicles'],
            ['name' => 'vehicles.create', 'module' => 'vehicles', 'description' => 'Create vehicles'],
            ['name' => 'vehicles.edit', 'module' => 'vehicles', 'description' => 'Edit vehicles'],
            ['name' => 'vehicles.delete', 'module' => 'vehicles', 'description' => 'Delete vehicles'],
            ['name' => 'vehicles.verify', 'module' => 'vehicles', 'description' => 'Verify vehicles'],

            // ===== BOOKING MANAGEMENT =====
            ['name' => 'manage_bookings', 'module' => 'bookings', 'description' => 'Full booking management'],
            ['name' => 'admin.bookings.manage', 'module' => 'bookings', 'description' => 'Policy: Manage bookings'],
            ['name' => 'admin.bookings.view', 'module' => 'bookings', 'description' => 'Policy: View bookings'],
            ['name' => 'view_all_bookings', 'module' => 'bookings', 'description' => 'View all bookings'],
            ['name' => 'bookings.view', 'module' => 'bookings', 'description' => 'View bookings'],
            ['name' => 'bookings.create', 'module' => 'bookings', 'description' => 'Create bookings'],
            ['name' => 'bookings.edit', 'module' => 'bookings', 'description' => 'Edit bookings'],
            ['name' => 'bookings.cancel', 'module' => 'bookings', 'description' => 'Cancel bookings'],
            ['name' => 'cancel_bookings', 'module' => 'bookings', 'description' => 'Cancel any booking'],
            ['name' => 'extend_bookings', 'module' => 'bookings', 'description' => 'Extend booking duration'],

            // ===== PAYMENT MANAGEMENT =====
            ['name' => 'manage_payments', 'module' => 'payments', 'description' => 'Full payment management'],
            ['name' => 'admin.payments.manage', 'module' => 'payments', 'description' => 'Policy: Manage payments'],
            ['name' => 'admin.payments.view', 'module' => 'payments', 'description' => 'Policy: View payments'],
            ['name' => 'view_all_payments', 'module' => 'payments', 'description' => 'View all payments'],
            ['name' => 'payments.view', 'module' => 'payments', 'description' => 'View payments'],
            ['name' => 'payments.process', 'module' => 'payments', 'description' => 'Process payments'],
            ['name' => 'refund_payments', 'module' => 'payments', 'description' => 'Process refunds'],

            // ===== INVOICE MANAGEMENT =====
            ['name' => 'manage_invoices', 'module' => 'invoices', 'description' => 'Full invoice management'],
            ['name' => 'view_all_invoices', 'module' => 'invoices', 'description' => 'View all invoices'],
            ['name' => 'download_invoices', 'module' => 'invoices', 'description' => 'Download invoice PDFs'],
            ['name' => 'mark_invoice_paid', 'module' => 'invoices', 'description' => 'Mark invoices as paid'],

            // ===== GATE OPERATIONS =====
            ['name' => 'operate_gate', 'module' => 'gates', 'description' => 'Gate operator dashboard access'],
            ['name' => 'gate.entry', 'module' => 'gates', 'description' => 'Vehicle entry operations'],
            ['name' => 'gate.exit', 'module' => 'gates', 'description' => 'Vehicle exit operations'],
            ['name' => 'gate.scan', 'module' => 'gates', 'description' => 'QR code scanning'],
            ['name' => 'operate.gates', 'module' => 'gates', 'description' => 'Operate parking gates'],
            ['name' => 'override.gates', 'module' => 'gates', 'description' => 'Override gate operations'],
            ['name' => 'view.gate.logs', 'module' => 'gates', 'description' => 'View gate operation logs'],
            ['name' => 'gate_operations', 'module' => 'gates', 'description' => 'Full gate operations access'],

            // ===== REPORTS & ANALYTICS =====
            ['name' => 'view_reports', 'module' => 'reports', 'description' => 'View system reports and analytics'],
            ['name' => 'view_financial_reports', 'module' => 'reports', 'description' => 'View financial reports'],
            ['name' => 'export_reports', 'module' => 'reports', 'description' => 'Export reports to files'],

            // ===== SYSTEM SETTINGS & MANAGEMENT =====
            ['name' => 'manage_settings', 'module' => 'system', 'description' => 'Manage system settings'],
            ['name' => 'manage_system', 'module' => 'system', 'description' => 'System management'],
            ['name' => 'view_logs', 'module' => 'system', 'description' => 'View system logs'],
            ['name' => 'view_user_sessions', 'module' => 'system', 'description' => 'View user sessions'],
            ['name' => 'view_access_logs', 'module' => 'system', 'description' => 'View access logs'],
            ['name' => 'system.health', 'module' => 'system', 'description' => 'View system health'],
            ['name' => 'system.logs', 'module' => 'system', 'description' => 'View system logs'],
            ['name' => 'system.cache', 'module' => 'system', 'description' => 'Clear system cache'],

            // ===== USER PROFILE =====
            ['name' => 'edit_own_profile', 'module' => 'profile', 'description' => 'Edit own profile'],
            ['name' => 'view_own_bookings', 'module' => 'profile', 'description' => 'View own bookings'],
            ['name' => 'manage_own_vehicles', 'module' => 'profile', 'description' => 'Manage own vehicles'],
        ];

        $count = 0;
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                array_merge($permission, ['guard_name' => 'web', 'is_active' => true])
            );
            $count++;
        }

        $this->command->line("   ✓ Created {$count} permissions");
    }

    /**
     * Create all system roles
     */
    private function createAllRoles(): void
    {
        $this->command->line('🎭 Creating Roles...');
        
        $roles = [
            ['name' => 'super-admin', 'description' => 'Super Administrator - Full system access (all permissions)'],
            ['name' => 'admin', 'description' => 'Administrator - Full access to all modules (all permissions)'],
            ['name' => 'manager', 'description' => 'Manager - Limited administrative access'],
            ['name' => 'gate-operator', 'description' => 'Gate Operator - Gate control and vehicle tracking'],
            ['name' => 'operator', 'description' => 'Operator - Gate operations and limited access'],
            ['name' => 'accountant', 'description' => 'Accountant - Payment and invoice management'],
            ['name' => 'user', 'description' => 'User - Basic user with profile and booking access'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                array_merge($role, ['guard_name' => 'web', 'is_active' => true])
            );
        }

        $this->command->line("   ✓ Created " . count($roles) . " roles");
    }

    /**
     * Assign permissions to roles
     */
    private function assignPermissionsToRoles(): void
    {
        $this->command->line('🔐 Assigning Permissions to Roles...');
        
        $allPermissions = Permission::all();

        // ===== SUPER ADMIN: ALL PERMISSIONS =====
        $superAdmin = Role::where('name', 'super-admin')->first();
        if ($superAdmin) {
            $superAdmin->permissions()->sync($allPermissions->pluck('id'));
            $this->command->line("   ✓ Super Admin: " . $allPermissions->count() . " permissions");
        }

        // ===== ADMIN: ALL PERMISSIONS (SAME AS SUPER ADMIN) =====
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $admin->permissions()->sync($allPermissions->pluck('id'));
            $this->command->line("   ✓ Admin: " . $allPermissions->count() . " permissions");
        }

        // ===== MANAGER: Limited admin access =====
        $manager = Role::where('name', 'manager')->first();
        if ($manager) {
            $managerPermissions = Permission::whereIn('name', [
                'admin.dashboard.view',
                'view_users', 'view_all_vehicles', 'view_all_bookings',
                'manage_parking', 'view_parking_areas',
                'view_reports', 'view_logs'
            ])->pluck('id');
            $manager->permissions()->sync($managerPermissions);
            $this->command->line("   ✓ Manager: " . $managerPermissions->count() . " permissions");
        }

        // ===== GATE OPERATOR: Gate and vehicle operations =====
        $gateOperator = Role::where('name', 'gate-operator')->first();
        if ($gateOperator) {
            $gateOperatorPerms = Permission::whereIn('name', [
                'user.dashboard.view',
                'operate.gates', 'gate.entry', 'gate.exit', 'gate.scan',
                'view.gate.logs', 'gate_operations',
                'view_all_bookings', 'view_all_vehicles',
                'parking.sessions.view', 'parking.sessions.exit', 'parking.sessions.collect_payment'
            ])->pluck('id');
            $gateOperator->permissions()->sync($gateOperatorPerms);
            $this->command->line("   ✓ Gate Operator: " . $gateOperatorPerms->count() . " permissions");
        }

        // ===== OPERATOR: Gate operations only =====
        $operator = Role::where('name', 'operator')->first();
        if ($operator) {
            $operatorPerms = Permission::whereIn('name', [
                'operate_gate', 'gate.entry', 'gate.exit', 'gate.scan',
                'vehicles.view', 'bookings.view', 'parking.sessions.view'
            ])->pluck('id');
            $operator->permissions()->sync($operatorPerms);
            $this->command->line("   ✓ Operator: " . $operatorPerms->count() . " permissions");
        }

        // ===== ACCOUNTANT: Payment and invoice management =====
        $accountant = Role::where('name', 'accountant')->first();
        if ($accountant) {
            $accountantPerms = Permission::whereIn('name', [
                'user.dashboard.view',
                'manage_payments', 'view_all_payments', 'refund_payments',
                'manage_invoices', 'view_all_invoices', 'download_invoices', 'mark_invoice_paid',
                'view_reports', 'view_financial_reports'
            ])->pluck('id');
            $accountant->permissions()->sync($accountantPerms);
            $this->command->line("   ✓ Accountant: " . $accountantPerms->count() . " permissions");
        }

        // ===== USER: Basic user permissions =====
        $user = Role::where('name', 'user')->first();
        if ($user) {
            $userPerms = Permission::whereIn('name', [
                'user.dashboard.view',
                'edit_own_profile', 'view_own_bookings', 'manage_own_vehicles'
            ])->pluck('id');
            $user->permissions()->sync($userPerms);
            $this->command->line("   ✓ User: " . $userPerms->count() . " permissions");
        }
    }

    /**
     * Create default system users
     */
    private function createDefaultUsers(): void
    {
        $this->command->line('👥 Creating Default Users...');
        
        // Super Admin - Full access
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
        $this->command->line("   ✓ Super Admin: admin@parking.com");

        // Test User - Basic access
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
        $this->command->line("   ✓ User: user@parking.com");

        // Test Manager
        $manager = User::firstOrCreate(
            ['email' => 'manager@parking.com'],
            [
                'name' => 'Test Manager',
                'phone' => '01700000002',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true
            ]
        );
        if (!$manager->hasRole('manager')) {
            $manager->assignRole('manager');
        }
        $this->command->line("   ✓ Manager: manager@parking.com");

        // Test Gate Operator
        $gateOp = User::firstOrCreate(
            ['email' => 'operator@parking.com'],
            [
                'name' => 'Test Gate Operator',
                'phone' => '01700000003',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true
            ]
        );
        if (!$gateOp->hasRole('gate-operator')) {
            $gateOp->assignRole('gate-operator');
        }
        $this->command->line("   ✓ Gate Operator: operator@parking.com");

        $this->command->line('');
        $this->command->line('🔑 LOGIN CREDENTIALS:');
        $this->command->line('   Email: admin@parking.com | Role: Super Admin | Password: password');
        $this->command->line('   Email: manager@parking.com | Role: Manager | Password: password');
        $this->command->line('   Email: operator@parking.com | Role: Gate Operator | Password: password');
        $this->command->line('   Email: user@parking.com | Role: User | Password: password');
    }
}
