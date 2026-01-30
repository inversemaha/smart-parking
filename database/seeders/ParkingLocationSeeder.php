<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Parking\Models\ParkingLocation;
use App\Domains\Parking\Models\ParkingSlot;

class ParkingLocationSeeder extends Seeder
{
    /**
     * Create parking locations and slots
     */
    public function run(): void
    {
        $this->command->info('🔄 Creating parking locations and slots...');

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
                    'motorcycle' => 30
                ]
            ],
            [
                'name' => 'Uttara Business District',
                'code' => 'UBD003',
                'address' => 'Sector 7, Uttara, Dhaka-1230',
                'latitude' => 23.8759,
                'longitude' => 90.3795,
                'capacity' => 100,
                'hourly_rate' => 50.00,
                'features' => ['security', 'covered', 'maintenance'],
                'slots_config' => [
                    'car' => 75,
                    'motorcycle' => 25
                ]
            ],
            [
                'name' => 'Banani Corporate Center',
                'code' => 'BCC004',
                'address' => 'Banani C/A, Dhaka-1213',
                'latitude' => 23.7936,
                'longitude' => 90.4066,
                'capacity' => 90,
                'hourly_rate' => 55.00,
                'features' => ['security', 'cctv', 'lighting'],
                'slots_config' => [
                    'car' => 65,
                    'motorcycle' => 25
                ]
            ],
            [
                'name' => 'Mirpur Shopping Complex',
                'code' => 'MSC005',
                'address' => 'Section 2, Mirpur, Dhaka-1216',
                'latitude' => 23.8103,
                'longitude' => 90.3661,
                'capacity' => 70,
                'hourly_rate' => 40.00,
                'features' => ['security', 'covered'],
                'slots_config' => [
                    'car' => 50,
                    'motorcycle' => 20
                ]
            ]
        ];

        foreach ($locations as $locationData) {
            if (!ParkingLocation::where('code', $locationData['code'])->exists()) {
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
                        'sunday' => ['open' => '08:00', 'close' => '22:00']
                    ],
                    'vehicle_types' => ['car', 'motorcycle'],
                    'amenities' => $locationData['features'],
                    'is_active' => true,
                    'opened_at' => now()->subMonths(rand(3, 12))
                ]);

                $this->command->info("✅ Created location: {$locationData['name']}");

                // Create slots for each vehicle type
                $slotNumber = 1;
                foreach ($locationData['slots_config'] as $vehicleType => $count) {
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
                    }
                }

                $this->command->info("  ✅ Created {$locationData['capacity']} slots");
            } else {
                $this->command->info("ℹ️ Location already exists: {$locationData['code']}");
            }
        }

        $this->command->info('✅ Parking location seeding completed.');
    }
}
