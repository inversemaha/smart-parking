<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\User\Models\User;
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Booking\Models\Booking;
use App\Domains\Parking\Models\ParkingLocation;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Create test bookings with various statuses
     */
    public function run(): void
    {
        $this->command->info('🔄 Creating test bookings...');

        $visitors = User::whereHas('roles', function($query) {
            $query->where('name', 'user');
        })->with('vehicles')->get();

        $locations = ParkingLocation::with('parkingSlots')->get();

        if ($visitors->isEmpty()) {
            $this->command->warn('⚠️ No visitors found. Run UserSeeder first.');
            return;
        }

        if ($locations->isEmpty()) {
            $this->command->warn('⚠️ No parking locations found. Run ParkingLocationSeeder first.');
            return;
        }

        $statuses = ['confirmed', 'active', 'completed', 'cancelled'];
        $createdCount = 0;
        $skippedCount = 0;

        // Create bookings for the last 30 days
        for ($day = 0; $day < 30; $day++) {
            $bookingDate = Carbon::today()->subDays($day);

            // Random number of bookings per day (1-5)
            $bookingsPerDay = rand(1, 5);

            for ($b = 0; $b < $bookingsPerDay; $b++) {
                $visitor = $visitors->random();
                $location = $locations->random();
                $vehicle = $visitor->vehicles->first();

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
                if (!$slot) continue;

                $startTime = $bookingDate->copy()->addHours(rand(8, 18));
                $duration = rand(1, 6); // 1-6 hours
                $endTime = $startTime->copy()->addHours($duration);
                $totalAmount = $location->hourly_rate * $duration;

                // Determine status based on date
                $status = 'completed';
                if ($bookingDate->isToday()) {
                    $status = collect(['confirmed', 'active'])->random();
                } elseif ($bookingDate->isFuture()) {
                    $status = 'confirmed';
                } elseif (rand(1, 10) === 1) { // 10% cancelled bookings
                    $status = 'cancelled';
                }

                // Check if similar booking already exists
                $existingBooking = Booking::where('user_id', $visitor->id)
                    ->where('parking_location_id', $location->id)
                    ->whereDate('start_time', $bookingDate)
                    ->first();

                if (!$existingBooking) {
                    Booking::create([
                        'booking_number' => 'BK' . $bookingDate->format('Ymd') . strtoupper(substr(md5(uniqid()), 0, 6)),
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

                    $createdCount++;
                } else {
                    $skippedCount++;
                }
            }
        }

        $this->command->info("✅ Created {$createdCount} bookings, skipped {$skippedCount} duplicates");
        $this->command->info('✅ Booking seeding completed.');
    }
}
