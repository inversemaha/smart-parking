<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Domains\User\Models\User;
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Parking\Models\ParkingLocation;
use App\Domains\Parking\Models\ParkingSlot;
use App\Domains\Booking\Models\Booking;
use App\Domains\Payment\Models\Payment;
use App\Shared\Models\AuditLog;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ModuleSeeder extends Seeder
{
    /**
     * Synchronized Module-wise Seeder
     * Handles all data creation in proper dependency order
     * Safe to run multiple times
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting Synchronized Module Seeder...');
        $this->command->info('');

        // Step 1: Roles & Permissions Module
        $this->seedRolesAndPermissions();

        // Step 2: User Management Module
        $this->seedUsers();

        // Step 3: Parking Infrastructure Module
        $this->seedParkingInfrastructure();

        // Step 4: Vehicle Management Module
        $this->seedVehicles();

        // Step 5: Booking System Module
        $this->seedBookings();

        // Step 6: Payment System Module
        $this->seedPayments();

        // Step 7: Audit & Logging Module
        $this->seedAuditLogs();

        $this->command->info('');
        $this->command->info('🎉 SYNCHRONIZED SEEDING COMPLETED!');
        $this->command->info('');
        $this->displayCredentials();
    }

    /**
     * Seed Roles & Permissions Module
     */
    private function seedRolesAndPermissions(): void
    {
        $this->command->info('👥 Module 1: Roles & Permissions');

        // Check if roles exist
        if (Role::count() > 0) {
            $this->command->warn('   ⚠️  Roles already exist, skipping...');
            return;
        }

        $roles = ['super-admin', 'admin', 'manager', 'user'];
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        $this->command->info('   ✅ Roles created successfully');
    }

    /**
     * Seed User Management Module
     */
    private function seedUsers(): void
    {
        $this->command->info('👤 Module 2: User Management');

        // Admin Users
        $adminUsers = [
            [
                'name' => 'Super Administrator',
                'email' => 'super@parking.dev',
                'phone' => '01700000001',
                'role' => 'super-admin'
            ],
            [
                'name' => 'System Admin',
                'email' => 'admin@parking.dev',
                'phone' => '01700000002',
                'role' => 'admin'
            ],
            [
                'name' => 'Parking Manager',
                'email' => 'manager@parking.dev',
                'phone' => '01700000003',
                'role' => 'manager'
            ]
        ];

        foreach ($adminUsers as $userData) {
            if (!User::where('email', $userData['email'])->exists()) {
                $user = User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'phone' => $userData['phone'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('password123'),
                    'is_active' => true,
                ]);
                $user->assignRole($userData['role']);
                $this->command->info("   ✅ {$userData['role']} created: {$userData['email']}");
            }
        }

        // Visitor Users
        $visitors = [
            ['name' => 'Ahmed Rahman', 'email' => 'ahmed@example.com', 'phone' => '01711111111'],
            ['name' => 'Fatima Khan', 'email' => 'fatima@example.com', 'phone' => '01722222222'],
            ['name' => 'Karim Islam', 'email' => 'karim@example.com', 'phone' => '01733333333'],
            ['name' => 'Rashida Begum', 'email' => 'rashida@example.com', 'phone' => '01744444444'],
            ['name' => 'Mahmud Hassan', 'email' => 'mahmud@example.com', 'phone' => '01755555555'],
        ];

        $visitorCount = 0;
        foreach ($visitors as $visitorData) {
            if (!User::where('email', $visitorData['email'])->exists()) {
                $visitor = User::create([
                    'name' => $visitorData['name'],
                    'email' => $visitorData['email'],
                    'phone' => $visitorData['phone'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('password123'),
                    'is_active' => true,
                ]);
                $visitor->assignRole('user');
                $visitorCount++;
            }
        }

        if ($visitorCount > 0) {
            $this->command->info("   ✅ {$visitorCount} visitors created successfully");
        } else {
            $this->command->warn('   ⚠️  Visitors already exist, skipping...');
        }
    }

    /**
     * Seed Parking Infrastructure Module
     */
    private function seedParkingInfrastructure(): void
    {
        $this->command->info('🏢 Module 3: Parking Infrastructure');

        if (ParkingLocation::count() > 0) {
            $this->command->warn('   ⚠️  Parking locations already exist, skipping...');
            return;
        }

        $locations = [
            [
                'name' => 'Central Business District Parking',
                'code' => 'CBD',
                'description' => 'Premium parking facility in the heart of the business district',
                'address' => 'Motijheel Commercial Area, Dhaka-1000',
                'latitude' => 23.7297,
                'longitude' => 90.4176,
                'hourly_rate' => 60.00,
                'slots' => ['car' => 80, 'motorcycle' => 40, 'suv' => 20]
            ],
            [
                'name' => 'Shopping Mall Complex',
                'code' => 'SMC',
                'description' => 'Multi-level parking for shopping convenience',
                'address' => 'Gulshan Avenue, Dhaka-1212',
                'latitude' => 23.7867,
                'longitude' => 90.4150,
                'hourly_rate' => 50.00,
                'slots' => ['car' => 100, 'motorcycle' => 60, 'suv' => 30]
            ],
            [
                'name' => 'Airport Terminal Parking',
                'code' => 'ATP',
                'description' => 'Secure long-term and short-term airport parking',
                'address' => 'Hazrat Shahjalal International Airport, Dhaka-1229',
                'latitude' => 23.8103,
                'longitude' => 90.4125,
                'hourly_rate' => 80.00,
                'slots' => ['car' => 120, 'motorcycle' => 30, 'suv' => 50]
            ],
            [
                'name' => 'University Campus Parking',
                'code' => 'UCP',
                'description' => 'Student and faculty parking facility',
                'address' => 'Dhaka University Area, Dhaka-1000',
                'latitude' => 23.7289,
                'longitude' => 90.3944,
                'hourly_rate' => 30.00,
                'slots' => ['car' => 60, 'motorcycle' => 80, 'suv' => 15]
            ],
            [
                'name' => 'Green City Hospital Parking',
                'code' => 'GCH',
                'description' => 'Hospital visitor and staff parking',
                'address' => 'Dhanmondi, Dhaka-1205',
                'latitude' => 23.7461,
                'longitude' => 90.3742,
                'hourly_rate' => 40.00,
                'slots' => ['car' => 90, 'motorcycle' => 50, 'suv' => 25]
            ]
        ];

        $totalSlots = 0;
        foreach ($locations as $locationData) {
            $location = ParkingLocation::create([
                'name' => $locationData['name'],
                'code' => $locationData['code'],
                'description' => $locationData['description'],
                'address' => $locationData['address'],
                'latitude' => $locationData['latitude'],
                'longitude' => $locationData['longitude'],
                'total_capacity' => array_sum($locationData['slots']),
                'hourly_rate' => $locationData['hourly_rate'],
                'operating_hours' => ['open' => '00:00', 'close' => '23:59'],
                'vehicle_types' => array_keys($locationData['slots']),
                'amenities' => ['security', 'lighting', 'cctv'],
                'is_active' => true,
                'opened_at' => now()->subYears(2),
            ]);

            // Create slots
            $slotNumber = 1;
            foreach ($locationData['slots'] as $vehicleType => $count) {
                for ($i = 1; $i <= $count; $i++) {
                    ParkingSlot::create([
                        'parking_location_id' => $location->id,
                        'slot_number' => $locationData['code'] . '-' . str_pad($slotNumber, 3, '0', STR_PAD_LEFT),
                        'slot_type' => 'regular',
                        'vehicle_types' => [$vehicleType],
                        'status' => 'available',
                        'is_active' => true,
                    ]);
                    $slotNumber++;
                    $totalSlots++;
                }
            }
        }

        $this->command->info("   ✅ {$location->count()} parking locations with {$totalSlots} slots created");
    }

    /**
     * Seed Vehicle Management Module
     */
    private function seedVehicles(): void
    {
        $this->command->info('🚗 Module 4: Vehicle Management');

        if (Vehicle::count() > 0) {
            $this->command->warn('   ⚠️  Vehicles already exist, skipping...');
            return;
        }

        $visitors = User::whereHas('roles', function($query) {
            $query->where('name', 'user');
        })->get();

        $vehicleData = [
            ['registration_number' => 'ঢাকা-মেট্রো-গা-১২-৩৪৫৬', 'brand' => 'Toyota', 'model' => 'Corolla', 'manufacture_year' => 2020, 'color' => 'White', 'vehicle_type' => 'car'],
            ['registration_number' => 'ঢাকা-মেট্রো-খ-১৫-৭৮৯০', 'brand' => 'Honda', 'model' => 'Civic', 'manufacture_year' => 2019, 'color' => 'Blue', 'vehicle_type' => 'car'],
            ['registration_number' => 'ঢাকা-মেট্রো-ঘ-২৩-৪৫৬৭', 'brand' => 'Yamaha', 'model' => 'FZ', 'manufacture_year' => 2021, 'color' => 'Black', 'vehicle_type' => 'motorcycle'],
            ['registration_number' => 'ঢাকা-মেট্রো-গ-৩৪-৫৬৭৮', 'brand' => 'Suzuki', 'model' => 'Swift', 'manufacture_year' => 2018, 'color' => 'Red', 'vehicle_type' => 'car'],
            ['registration_number' => 'ঢাকা-মেট্রো-ক-৪৫-৬৭৮৯', 'brand' => 'Honda', 'model' => 'CR-V', 'manufacture_year' => 2022, 'color' => 'Silver', 'vehicle_type' => 'suv'],
        ];

        $vehicleCount = 0;
        foreach ($visitors as $index => $visitor) {
            if (isset($vehicleData[$index])) {
                Vehicle::create([
                    'user_id' => $visitor->id,
                    'registration_number' => $vehicleData[$index]['registration_number'],
                    'brand' => $vehicleData[$index]['brand'],
                    'model' => $vehicleData[$index]['model'],
                    'manufacture_year' => $vehicleData[$index]['manufacture_year'],
                    'color' => $vehicleData[$index]['color'],
                    'vehicle_type' => $vehicleData[$index]['vehicle_type'],
                    'verification_status' => 'verified',
                    'verified_at' => now()->subDays(rand(1, 30)),
                ]);
                $vehicleCount++;
            }
        }

        $this->command->info("   ✅ {$vehicleCount} vehicles created successfully");
    }

    /**
     * Seed Booking System Module
     */
    private function seedBookings(): void
    {
        $this->command->info('📅 Module 5: Booking System');

        if (Booking::count() > 0) {
            $this->command->warn('   ⚠️  Bookings already exist, skipping...');
            return;
        }

        $visitors = User::whereHas('roles', function($query) {
            $query->where('name', 'user');
        })->with('vehicles')->get();

        $locations = ParkingLocation::with('parkingSlots')->get();
        $bookingCount = 0;
        $statuses = ['confirmed', 'active', 'completed', 'cancelled'];

        // Create bookings for the last 30 days
        for ($day = 30; $day >= 0; $day--) {
            $bookingDate = now()->subDays($day);

            // Create 3-5 bookings per day
            $dailyBookings = rand(3, 5);

            for ($i = 0; $i < $dailyBookings; $i++) {
                $visitor = $visitors->random();
                $vehicle = $visitor->vehicles->random();
                $location = $locations->random();

                // Find available slot
                $availableSlots = $location->parkingSlots->filter(function($slot) use ($vehicle) {
                    $vehicleTypes = is_array($slot->vehicle_types) ? $slot->vehicle_types : json_decode($slot->vehicle_types, true);
                    return in_array($vehicle->vehicle_type, $vehicleTypes) && $slot->status === 'available';
                });

                if ($availableSlots->isEmpty()) continue;

                $slot = $availableSlots->random();
                $startTime = $bookingDate->copy()->addHours(rand(8, 18));
                $duration = rand(1, 6);
                $endTime = $startTime->copy()->addHours($duration);
                $status = $statuses[array_rand($statuses)];

                Booking::create([
                    'user_id' => $visitor->id,
                    'vehicle_id' => $vehicle->id,
                    'parking_location_id' => $location->id,
                    'parking_slot_id' => $slot->id,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'duration_hours' => $duration,
                    'hourly_rate' => $location->hourly_rate,
                    'total_amount' => $location->hourly_rate * $duration,
                    'status' => $status,
                    'payment_status' => $status === 'cancelled' ? 'refunded' : 'paid',
                    'notes' => $status === 'cancelled' ? 'User cancelled booking' : 'Regular booking',
                    'created_at' => $startTime->copy()->subHours(rand(1, 24)),
                ]);

                $bookingCount++;
            }
        }

        $this->command->info("   ✅ {$bookingCount} bookings created successfully");
    }

    /**
     * Seed Payment System Module
     */
    private function seedPayments(): void
    {
        $this->command->info('💳 Module 6: Payment System');

        if (Payment::count() > 0) {
            $this->command->warn('   ⚠️  Payments already exist, skipping...');
            return;
        }

        $bookings = Booking::where('payment_status', 'paid')->get();
        $paymentMethods = ['card', 'mobile_banking', 'internet_banking'];
        $gateways = ['sslcommerz', 'bkash', 'nagad'];

        foreach ($bookings as $booking) {
            Payment::create([
                'user_id' => $booking->user_id,
                'payable_type' => 'App\\Domains\\Booking\\Models\\Booking',
                'payable_id' => $booking->id,
                'amount' => $booking->total_amount,
                'currency' => 'BDT',
                'status' => 'paid',
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'gateway' => $gateways[array_rand($gateways)],
                'gateway_transaction_id' => 'TXN' . now()->timestamp . rand(1000, 9999),
                'gateway_response' => [
                    'status' => 'VALID',
                    'amount' => $booking->total_amount,
                    'currency' => 'BDT'
                ],
                'initiated_at' => $booking->created_at,
                'paid_at' => $booking->created_at->addMinutes(2),
            ]);
        }

        $this->command->info("   ✅ " . $bookings->count() . " payments created successfully");
    }

    /**
     * Seed Audit & Logging Module
     */
    private function seedAuditLogs(): void
    {
        $this->command->info('📊 Module 7: Audit & Logging');

        if (AuditLog::count() > 0) {
            $this->command->warn('   ⚠️  Audit logs already exist, skipping...');
            return;
        }

        $users = User::all();
        $actions = [
            'user.login' => 'User logged into the system',
            'user.logout' => 'User logged out from the system',
            'vehicle.created' => 'New vehicle registered',
            'booking.created' => 'New parking booking created',
            'payment.completed' => 'Payment completed successfully',
            'booking.cancelled' => 'Booking cancelled by user',
        ];

        $logCount = 0;
        for ($day = 30; $day >= 0; $day--) {
            $logDate = now()->subDays($day);

            // Create 5-10 audit logs per day
            $dailyLogs = rand(5, 10);

            for ($i = 0; $i < $dailyLogs; $i++) {
                $user = $users->random();
                $action = array_rand($actions);

                AuditLog::create([
                    'user_id' => $user->id,
                    'action' => $action,
                    'description' => $actions[$action],
                    'ip_address' => '192.168.1.' . rand(1, 255),
                    'user_agent' => 'Mozilla/5.0 (compatible; Smart Parking System)',
                    'performed_at' => $logDate->copy()->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                ]);

                $logCount++;
            }
        }

        $this->command->info("   ✅ {$logCount} audit logs created successfully");
    }

    /**
     * Display login credentials
     */
    private function displayCredentials(): void
    {
        $this->command->info('🔑 LOGIN CREDENTIALS:');
        $this->command->info('');
        $this->command->table(['Role', 'Email', 'Password'], [
            ['Super Admin', 'super@parking.dev', 'password123'],
            ['Admin', 'admin@parking.dev', 'password123'],
            ['Manager', 'manager@parking.dev', 'password123'],
            ['Visitor', 'ahmed@example.com', 'password123'],
            ['Visitor', 'fatima@example.com', 'password123'],
        ]);
        $this->command->info('');
        $this->command->info('🌐 Access URLs:');
        $this->command->info('   Admin Panel: http://localhost:8000/admin/login');
        $this->command->info('   Visitor Panel: http://localhost:8000/visitor/login');
        $this->command->info('');
    }
}
