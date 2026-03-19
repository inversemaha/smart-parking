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
            UserSeeder::class,                       // Create test users (needed first)
            VehicleSeeder::class,                    // Create test vehicles (needed for Phase 3)
            Phase1ParkingSeeder::class,              // Phase 1: Zones, Floors, Vehicle Types, Rates
            Phase2AccessControlSeeder::class,        // Phase 2: Parking Gates, QR Codes, Access Logs
            Phase3ParkingSessionsSeeder::class,      // Phase 3: Parking Sessions (Entry/Exit Tracking)
            ParkingLocationSeeder::class,            // Create parking locations and slots
            BookingSeeder::class,                    // Create test bookings
            PaymentSeeder::class,                    // Create test payments
            // AuditLogSeeder::class,                // Create audit logs (disabled for now)
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
        $this->command->info('├── Phase 1: Parking Zones, Floors, Vehicle Types, Rates');
        $this->command->info('├── Phase 2: Parking Gates, QR Codes, Access Logs');
        $this->command->info('├── Phase 3: Parking Sessions (Entry/Exit Tracking)');
        $this->command->info('├── Users: Admin and Test Users');
        $this->command->info('├── Vehicles: Test Vehicle Registry');
        $this->command->info('├── Bookings: Historical Booking Data');
        $this->command->info('└── Payments: Transaction Records');
        $this->command->newLine();
        $this->command->info('🚀 Ready to test the Smart Parking System!');
        $this->command->newLine();
        $this->command->info('🔑 DEFAULT CREDENTIALS:');
        $this->command->info('Admin: admin@parking.com / password');
        $this->command->info('Test Users: ahmed@example.com / password123');
        $this->command->info('Visit: http://localhost:8000');
    }

}
