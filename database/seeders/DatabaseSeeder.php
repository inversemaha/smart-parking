<?php

namespace Database\Seeders;

use App\Domains\User\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting synchronized database seeding...');
        $this->command->newLine();

        // Core system seeders (always run first)
        $this->command->info('📋 Seeding core system data...');
        $this->call([
            DefaultRolesAndPermissionsSeeder::class,
            SystemSettingsSeeder::class,
        ]);
        $this->command->info('✅ Core system seeding completed.');
        $this->command->newLine();

        // Module-specific seeders (can be run independently)
        $this->command->info('🏢 Seeding business modules...');
        $this->call([
            Phase1ParkingSeeder::class,    // Create Phase 1 parking zones, floors, vehicle types and rates
            ParkingLocationSeeder::class,  // Create parking locations and slots
            UserSeeder::class,             // Create test users
            VehicleSeeder::class,          // Create test vehicles
            BookingSeeder::class,          // Create test bookings
            PaymentSeeder::class,          // Create test payments
            // AuditLogSeeder::class,      // Create audit logs (disabled for now)
        ]);
        $this->command->info('✅ Business module seeding completed.');
        $this->command->newLine();

        // Display seeding summary
        $this->displaySeedingSummary();
    }

    /**
     * Display seeding completion summary
     */
    private function displaySeedingSummary(): void
    {
        $this->command->info('🎯 DATABASE SEEDING COMPLETED!');
        $this->command->newLine();
        $this->command->info('📊 SEEDING SUMMARY:');
        $this->command->info('├── Core System: Roles, Permissions, Settings');
        $this->command->info('├── Parking: Locations and Slots');
        $this->command->info('├── Users: Admin and Test Users');
        $this->command->info('├── Vehicles: Test Vehicle Registry');
        $this->command->info('├── Bookings: Historical Booking Data');
        $this->command->info('├── Payments: Transaction Records');
        $this->command->info('└── Audit: System Activity Logs');
        $this->command->newLine();
        $this->command->info('🚀 Ready to test the Smart Parking System!');
        $this->command->newLine();
        $this->command->info('🔑 DEFAULT CREDENTIALS:');
        $this->command->info('Admin: admin@parking.com / password');
        $this->command->info('Test Users: ahmed@example.com / password123');
        $this->command->info('Visit: http://localhost:8000');
    }

}
