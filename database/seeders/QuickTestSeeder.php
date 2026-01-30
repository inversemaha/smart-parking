<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\User\Models\User;
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Booking\Models\Booking;
use App\Domains\Payment\Models\Payment;
use App\Domains\Parking\Models\ParkingLocation;
use Carbon\Carbon;

class QuickTestSeeder extends Seeder
{
    /**
     * Quick test data for immediate testing
     * Run with: php artisan db:seed --class=QuickTestSeeder
     */
    public function run(): void
    {
        $this->command->info('🚀 Creating quick test data...');

        // Ensure we have basic data first
        if (ParkingLocation::count() === 0) {
            $this->command->info('🏢 No parking locations found, creating basic setup...');
            $this->call(ParkingLocationSeeder::class);
        }

        // Create/get test visitor
        $visitor = User::where('email', 'test@example.com')->first();

        if (!$visitor) {
            $visitor = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'phone' => '01777777777',
                'email_verified_at' => now(),
                'password' => \Hash::make('password'),
                'is_active' => true,
            ]);
            $visitor->assignRole('user');

            $this->command->info('✅ Quick test user created: test@example.com / password');
        }

        // Create test vehicle
        if (!$visitor->vehicles()->exists()) {
            Vehicle::create([
                'user_id' => $visitor->id,
                'registration_number' => 'TEST-123',
                'brand' => 'Toyota',
                'model' => 'Corolla',
                'manufacture_year' => 2022,
                'color' => 'White',
                'vehicle_type' => 'car',
                'verification_status' => 'verified',
            ]);

            $this->command->info('✅ Test vehicle created for user.');
        }

        // Create test booking
        $todayBooking = Booking::where('user_id', $visitor->id)
            ->whereDate('start_time', today())
            ->first();

        if (!$todayBooking) {
            $location = ParkingLocation::first();
            if ($location) {
                $slot = $location->parkingSlots()
                    ->whereJsonContains('vehicle_types', 'car')
                    ->where('status', 'available')
                    ->first();

                if ($slot) {
                    $startTime = now()->addHours(1);
                    $endTime = $startTime->copy()->addHours(3);

                    $booking = Booking::create([
                        'booking_number' => 'BK' . now()->format('Ymd') . strtoupper(substr(md5(uniqid()), 0, 6)),
                        'user_id' => $visitor->id,
                        'vehicle_id' => $visitor->vehicles()->first()->id,
                        'parking_location_id' => $location->id,
                        'parking_slot_id' => $slot->id,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'duration_hours' => 3,
                        'hourly_rate' => $location->hourly_rate,
                        'total_amount' => $location->hourly_rate * 3,
                        'status' => 'confirmed',
                        'payment_status' => 'paid',
                        'notes' => 'Quick test booking',
                    ]);

                    // Create payment
                    Payment::create([
                        'payment_id' => 'PAY' . now()->format('Ymd') . strtoupper(substr(md5(uniqid()), 0, 8)),
                        'user_id' => $visitor->id,
                        'payable_type' => 'App\\Domains\\Booking\\Models\\Booking',
                        'payable_id' => $booking->id,
                        'payment_method' => 'card',
                        'gateway' => 'sslcommerz',
                        'amount' => $booking->total_amount,
                        'currency' => 'BDT',
                        'status' => 'paid',
                        'gateway_transaction_id' => 'SSL' . now()->timestamp,
                        'gateway_response' => [
                            'status' => 'VALID',
                            'amount' => $booking->total_amount,
                            'currency' => 'BDT'
                        ],
                        'initiated_at' => now()->subMinutes(10),
                        'paid_at' => now(),
                    ]);

                    $this->command->info('✅ Test booking created for today.');
                }
            }
        }

        $this->command->newLine();
        $this->command->info('🎯 QUICK TEST SETUP COMPLETE!');
        $this->command->newLine();
        $this->command->info('🔑 Test Credentials:');
        $this->command->info('📧 Email: test@example.com');
        $this->command->info('🔑 Password: password');
        $this->command->newLine();
        $this->command->info('🌐 Visit: http://localhost:8000/visitor/login');
        $this->command->newLine();
    }
}
