<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Domains\User\Models\User;
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Booking\Models\Booking;
use App\Domains\Payment\Models\Payment;
use App\Domains\Parking\Models\ParkingLocation;
use App\Domains\Parking\Models\ParkingSlot;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds for comprehensive testing.
     */
    public function run(): void
    {
        $this->createTestUsers();
        $this->createTestLocationsWithSlots();
        $this->createTestVehicles();
        $this->createTestBookings();
        $this->createTestPayments();
    }

    /**
     * Create test users with different roles
     */
    private function createTestUsers(): void
    {
        // Skip if users already exist
        if (User::where('email', 'superadmin@parking.com')->exists()) {
            $this->command->info('ℹ️ Test users already exist, skipping...');
            return;
        }

        // Create Super Admin (avoid conflict with DefaultRolesAndPermissionsSeeder)
        if (!User::where('email', 'test.superadmin@parking.com')->exists()) {
            $superAdmin = User::create([
                'name' => 'Test Super Administrator',
                'email' => 'test.superadmin@parking.com',
                'phone' => '01700000001',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]);
            $superAdmin->assignRole('super-admin');
        }

        // Create Admin (avoid conflict with DefaultRolesAndPermissionsSeeder)
        if (!User::where('email', 'test.admin@parking.com')->exists()) {
            $admin = User::create([
                'name' => 'Test Admin',
                'email' => 'test.admin@parking.com',
                'phone' => '01700000002',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]);
            $admin->assignRole('admin');
        }

        // Create Manager
        if (!User::where('email', 'test.manager@parking.com')->exists()) {
            $manager = User::create([
                'name' => 'Test Manager',
                'email' => 'test.manager@parking.com',
                'phone' => '01700000003',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]);
            $manager->assignRole('manager');
        }

        // Create test visitors (Regular Users)
        $visitors = [
            [
                'name' => 'Ahmed Rahman',
                'email' => 'ahmed@example.com',
                'phone' => '01711111111',
                'gender' => 'male',
                'birth' => '1992-06-10'
            ],
            [
                'name' => 'Fatima Khan',
                'email' => 'fatima@example.com',
                'phone' => '01722222222',
                'gender' => 'female',
                'birth' => '1995-08-25'
            ],
            [
                'name' => 'Karim Islam',
                'email' => 'karim@example.com',
                'phone' => '01733333333',
                'gender' => 'male',
                'birth' => '1987-12-05'
            ],
            [
                'name' => 'Rashida Begum',
                'email' => 'rashida@example.com',
                'phone' => '01744444444',
                'gender' => 'female',
                'birth' => '1993-04-18'
            ],
            [
                'name' => 'Mahmud Hassan',
                'email' => 'mahmud@example.com',
                'phone' => '01755555555',
                'gender' => 'male',
                'birth' => '1989-09-12'
            ]
        ];

        foreach ($visitors as $visitorData) {
            $visitor = User::create([
                'name' => $visitorData['name'],
                'email' => $visitorData['email'],
                'phone' => $visitorData['phone'],
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]);
            $visitor->assignRole('user');
        }

        $this->command->info('✅ Test users created successfully.');
    }

    /**
     * Create test parking locations with slots
     */
    private function createTestLocationsWithSlots(): void
    {
        // Skip if locations already exist
        if (ParkingLocation::count() > 0) {
            $this->command->info('ℹ️ Parking locations already exist, skipping...');
            return;
        }

        $locations = [
            [
                'name' => 'Gulshan Commercial Hub',
                'code' => 'GCH001',
                'address' => 'Gulshan Avenue, Dhaka-1212',
                'latitude' => 23.7925,
                'longitude' => 90.4078,
                'capacity' => 80,
                'hourly_rate' => 60.00,
                'features' => ['security', 'cctv', 'covered', 'lighting'],
                'slots_config' => [
                    'car' => 60,
                    'motorcycle' => 20
                ]
            ],
            [
                'name' => 'Dhanmondi Shopping Center',
                'code' => 'DSC002',
                'address' => 'Dhanmondi Road 27, Dhaka-1205',
                'latitude' => 23.7461,
                'longitude' => 90.3742,
                'capacity' => 120,
                'hourly_rate' => 45.00,
                'features' => ['security', 'lighting', 'ev_charging'],
                'slots_config' => [
                    'car' => 90,
                    'motorcycle' => 25,
                    'suv' => 5
                ]
            ],
            [
                'name' => 'Uttara Modern Complex',
                'code' => 'UMC003',
                'address' => 'Uttara Sector 7, Dhaka-1230',
                'latitude' => 23.8759,
                'longitude' => 90.3795,
                'capacity' => 200,
                'hourly_rate' => 40.00,
                'features' => ['security', 'cctv', 'covered', 'disabled_access'],
                'slots_config' => [
                    'car' => 150,
                    'motorcycle' => 40,
                    'truck' => 10
                ]
            ],
            [
                'name' => 'Old Dhaka Heritage Parking',
                'code' => 'ODH004',
                'address' => 'Lalbagh Road, Dhaka-1211',
                'latitude' => 23.7196,
                'longitude' => 90.3883,
                'capacity' => 50,
                'hourly_rate' => 35.00,
                'features' => ['security', 'lighting'],
                'slots_config' => [
                    'car' => 35,
                    'motorcycle' => 15
                ]
            ],
            [
                'name' => 'Bashundhara City Mall Parking',
                'code' => 'BCM005',
                'address' => 'Panthapath, Dhaka-1215',
                'latitude' => 23.7515,
                'longitude' => 90.3844,
                'capacity' => 300,
                'hourly_rate' => 50.00,
                'features' => ['security', 'cctv', 'covered', 'ev_charging', 'disabled_access'],
                'slots_config' => [
                    'car' => 200,
                    'motorcycle' => 50,
                    'suv' => 30,
                    'truck' => 20
                ]
            ]
        ];

        foreach ($locations as $locationData) {
            $location = ParkingLocation::create([
                'name' => $locationData['name'],
                'code' => $locationData['code'],
                'description' => 'Modern parking facility with advanced security and convenience features.',
                'address' => $locationData['address'],
                'latitude' => $locationData['latitude'],
                'longitude' => $locationData['longitude'],
                'total_capacity' => $locationData['capacity'],
                'hourly_rate' => $locationData['hourly_rate'],
                'operating_hours' => [
                    'monday' => ['open' => '06:00', 'close' => '24:00'],
                    'tuesday' => ['open' => '06:00', 'close' => '24:00'],
                    'wednesday' => ['open' => '06:00', 'close' => '24:00'],
                    'thursday' => ['open' => '06:00', 'close' => '24:00'],
                    'friday' => ['open' => '06:00', 'close' => '24:00'],
                    'saturday' => ['open' => '06:00', 'close' => '24:00'],
                    'sunday' => ['open' => '08:00', 'close' => '22:00'],
                ],
                'vehicle_types' => array_keys($locationData['slots_config']),
                'amenities' => $locationData['features'],
                'is_active' => true,
                'opened_at' => now()->subDays(rand(30, 365)),
            ]);

            // Create slots for each vehicle type
            $slotNumber = 1;
            foreach ($locationData['slots_config'] as $vehicleType => $count) {
                for ($i = 1; $i <= $count; $i++) {
                    ParkingSlot::create([
                        'parking_location_id' => $location->id,
                        'slot_number' => $locationData['code'] . '-' . str_pad($slotNumber, 3, '0', STR_PAD_LEFT),
                        'vehicle_types' => [$vehicleType],
                        'slot_type' => 'regular',
                        'status' => 'available',
                        'is_active' => true,
                    ]);
                    $slotNumber++;
                }
            }
        }

        $this->command->info('✅ Test parking locations and slots created successfully.');
    }

    /**
     * Create test vehicles for visitors
     */
    private function createTestVehicles(): void
    {
        // Skip if vehicles already exist
        if (Vehicle::count() > 0) {
            $this->command->info('ℹ️ Test vehicles already exist, skipping...');
            return;
        }

        $visitors = User::whereHas('roles', function($query) {
            $query->where('name', 'user');
        })->get();

        $vehicleData = [
            [
                'registration_number' => 'ঢাকা-মেট্রো-গা-১২-৩৪৫৬',
                'brand' => 'Toyota',
                'model' => 'Corolla',
                'manufacture_year' => 2020,
                'color' => 'White',
                'vehicle_type' => 'car'
            ],
            [
                'registration_number' => 'ঢাকা-মেট্রো-খ-১৫-৭৮৯০',
                'brand' => 'Honda',
                'model' => 'Civic',
                'manufacture_year' => 2019,
                'color' => 'Blue',
                'vehicle_type' => 'car'
            ],
            [
                'registration_number' => 'ঢাকা-মেট্রো-ঘ-২৩-৪৫৬৭',
                'brand' => 'Yamaha',
                'model' => 'FZ',
                'manufacture_year' => 2021,
                'color' => 'Black',
                'vehicle_type' => 'motorcycle'
            ],
            [
                'registration_number' => 'ঢাকা-মেট্রো-গ-৩৪-৫৬৭৮',
                'brand' => 'Suzuki',
                'model' => 'Swift',
                'manufacture_year' => 2018,
                'color' => 'Red',
                'vehicle_type' => 'car'
            ],
            [
                'registration_number' => 'ঢাকা-মেট্রো-ক-৪৫-৬৭৮৯',
                'brand' => 'Honda',
                'model' => 'CR-V',
                'manufacture_year' => 2022,
                'color' => 'Silver',
                'vehicle_type' => 'suv'
            ]
        ];

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
                ]);
            }

            // Add a second vehicle for some users
            if ($index < 2) {
                Vehicle::create([
                    'user_id' => $visitor->id,
                    'registration_number' => 'ঢাকা-মেট্রো-চ-' . rand(10, 99) . '-' . rand(1000, 9999),
                    'brand' => 'Honda',
                    'model' => 'CB Shine',
                    'manufacture_year' => 2020,
                    'color' => 'Black',
                    'vehicle_type' => 'motorcycle',
                    'verification_status' => 'verified',
                ]);
            }
        }

        $this->command->info('✅ Test vehicles created successfully.');
    }

    /**
     * Create test bookings with various statuses
     */
    private function createTestBookings(): void
    {
        // Skip if bookings already exist
        if (Booking::count() > 0) {
            $this->command->info('ℹ️ Test bookings already exist, skipping...');
            return;
        }

        $visitors = User::whereHas('roles', function($query) {
            $query->where('name', 'user');
        })->with('vehicles')->get();

        $locations = ParkingLocation::with('slots')->get();

        $bookingStatuses = ['confirmed', 'active', 'completed', 'cancelled'];

        // Create bookings for the last 30 days
        for ($day = 30; $day >= 0; $day--) {
            $bookingDate = now()->subDays($day);

            // Create 3-8 random bookings per day
            $dailyBookings = rand(3, 8);

            for ($i = 0; $i < $dailyBookings; $i++) {
                $visitor = $visitors->random();
                $vehicle = $visitor->vehicles->first();
                $location = $locations->random();

                if (!$vehicle) continue;

                // Get available slots for the vehicle type
                $availableSlots = $location->parkingSlots->filter(function($slot) use ($vehicle) {
                    $vehicleTypes = is_array($slot->vehicle_types) ? $slot->vehicle_types : json_decode($slot->vehicle_types, true);
                    return in_array($vehicle->vehicle_type, $vehicleTypes) && $slot->status === 'available';
                });
                if ($availableSlots->isEmpty()) {
                    $availableSlots = $location->parkingSlots->filter(function($slot) {
                        $vehicleTypes = is_array($slot->vehicle_types) ? $slot->vehicle_types : json_decode($slot->vehicle_types, true);
                        return in_array('car', $vehicleTypes) && $slot->status === 'available';
                    });
                }

                $slot = $availableSlots->random();

                $startTime = $bookingDate->copy()->addHours(rand(8, 18));
                $duration = rand(1, 6); // 1-6 hours
                $endTime = $startTime->copy()->addHours($duration);

                $totalAmount = $location->hourly_rate * $duration;

                // Determine status based on time
                $status = 'confirmed';
                if ($endTime->isPast()) {
                    $status = collect(['completed', 'cancelled'])->random();
                } elseif ($startTime->isPast() && $endTime->isFuture()) {
                    $status = 'active';
                }

                Booking::create([
                    'user_id' => $visitor->id,
                    'vehicle_id' => $vehicle->id,
                    'parking_location_id' => $location->id,
                    'parking_slot_id' => $slot->id,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'duration_hours' => $duration,
                    'hourly_rate' => $location->hourly_rate,
                    'total_amount' => $totalAmount,
                    'status' => $status,
                    'payment_status' => $status === 'cancelled' ? 'refunded' : 'paid',
                    'notes' => $status === 'cancelled' ? 'User cancelled the booking' : 'Regular booking',
                    'created_at' => $startTime->copy()->subHours(rand(1, 24)),
                    'updated_at' => $status === 'cancelled' ? $startTime->copy()->subMinutes(30) : now()
                ]);
            }
        }

        $this->command->info('✅ Test bookings created successfully.');
    }

    /**
     * Create test payments
     */
    private function createTestPayments(): void
    {
        // Skip if payments already exist
        if (Payment::count() > 0) {
            $this->command->info('ℹ️ Test payments already exist, skipping...');
            return;
        }

        $bookings = Booking::whereIn('status', ['confirmed', 'active', 'completed'])->get();

        $paymentMethods = ['card', 'mobile_banking', 'internet_banking'];
        $paymentStatuses = ['paid', 'pending', 'failed', 'refunded'];

        foreach ($bookings as $booking) {
            $paymentStatus = 'paid';

            // Some failed payments for pending/cancelled bookings
            if ($booking->status === 'cancelled') {
                $paymentStatus = collect(['failed', 'refunded'])->random();
            } elseif (rand(1, 20) === 1) { // 5% failed payments
                $paymentStatus = 'failed';
            }

            Payment::create([
                'user_id' => $booking->user_id,
                'payable_type' => 'App\\Domains\\Booking\\Models\\Booking',
                'payable_id' => $booking->id,
                'payment_method' => collect($paymentMethods)->random(),
                'gateway' => 'sslcommerz',
                'amount' => $booking->total_amount,
                'currency' => 'BDT',
                'status' => $paymentStatus,
                'gateway_transaction_id' => 'SSL' . time() . rand(1000, 9999),
                'gateway_response' => [
                    'status' => $paymentStatus === 'paid' ? 'VALID' : 'FAILED',
                    'tran_date' => $booking->created_at->format('Y-m-d H:i:s'),
                    'amount' => $booking->total_amount,
                    'currency' => 'BDT'
                ],
                'initiated_at' => $booking->created_at->addMinutes(2),
                'paid_at' => $paymentStatus === 'paid' ? $booking->created_at->addMinutes(5) : null,
                'failed_at' => $paymentStatus === 'failed' ? $booking->created_at->addMinutes(10) : null,
                'created_at' => $booking->created_at->addMinutes(2),
                'updated_at' => $paymentStatus === 'paid' ? $booking->created_at->addMinutes(5) : $booking->created_at->addMinutes(10)
            ]);
        }

        $this->command->info('✅ Test payments created successfully.');
    }
}
