<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Parking\Models\ParkingLocation;
use App\Domains\Parking\Models\ParkingSlot;

class ParkingAreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create parking areas
        $areas = [
            [
                'name' => 'Central Parking Area',
                'code' => 'CPA001',
                'description' => 'Main parking area in the city center',
                'address' => 'Dhaka, Bangladesh',
                'latitude' => 23.8103,
                'longitude' => 90.4125,
                'total_capacity' => 100,
                'hourly_rate' => 50.00,
                'daily_rate' => 800.00,
                'monthly_rate' => 15000.00,
                'operating_hours' => [
                    'monday' => ['open' => '06:00', 'close' => '22:00'],
                    'tuesday' => ['open' => '06:00', 'close' => '22:00'],
                    'wednesday' => ['open' => '06:00', 'close' => '22:00'],
                    'thursday' => ['open' => '06:00', 'close' => '22:00'],
                    'friday' => ['open' => '06:00', 'close' => '22:00'],
                    'saturday' => ['open' => '06:00', 'close' => '22:00'],
                    'sunday' => ['open' => '08:00', 'close' => '20:00'],
                ],
                'vehicle_types' => ['car', 'motorcycle'],
                'amenities' => ['security', 'lighting', 'covered'],
                'is_active' => true,
                'opened_at' => now(),
            ],
            [
                'name' => 'Motijheel Commercial Area',
                'code' => 'MCA002',
                'description' => 'Commercial district parking facility',
                'address' => 'Motijheel, Dhaka',
                'latitude' => 23.7337,
                'longitude' => 90.4198,
                'total_capacity' => 150,
                'hourly_rate' => 60.00,
                'daily_rate' => 1000.00,
                'monthly_rate' => 18000.00,
                'operating_hours' => [
                    'monday' => ['open' => '05:00', 'close' => '23:00'],
                    'tuesday' => ['open' => '05:00', 'close' => '23:00'],
                    'wednesday' => ['open' => '05:00', 'close' => '23:00'],
                    'thursday' => ['open' => '05:00', 'close' => '23:00'],
                    'friday' => ['open' => '05:00', 'close' => '23:00'],
                    'saturday' => ['open' => '05:00', 'close' => '23:00'],
                    'sunday' => ['open' => '07:00', 'close' => '21:00'],
                ],
                'vehicle_types' => ['car', 'motorcycle', 'bicycle'],
                'amenities' => ['security', 'lighting', 'cctv', 'restroom'],
                'is_active' => true,
                'opened_at' => now(),
            ],
            [
                'name' => 'University Area Parking',
                'code' => 'UAP003',
                'description' => 'Parking facility near university campus',
                'address' => 'Shahbag, Dhaka',
                'latitude' => 23.7379,
                'longitude' => 90.3947,
                'total_capacity' => 80,
                'hourly_rate' => 30.00,
                'daily_rate' => 500.00,
                'monthly_rate' => 10000.00,
                'operating_hours' => [
                    'monday' => '24/7',
                    'tuesday' => '24/7',
                    'wednesday' => '24/7',
                    'thursday' => '24/7',
                    'friday' => '24/7',
                    'saturday' => '24/7',
                    'sunday' => '24/7',
                ],
                'vehicle_types' => ['car', 'motorcycle', 'bicycle'],
                'amenities' => ['lighting', 'bicycle_rack'],
                'is_active' => true,
                'opened_at' => now(),
            ],
        ];

        foreach ($areas as $areaData) {
            $area = ParkingLocation::firstOrCreate(
                ['code' => $areaData['code']],
                $areaData
            );

            // Create parking slots for each area
            $this->createParkingSlotsForArea($area);
        }

        $this->command->info('Parking areas and slots created successfully!');
    }

    /**
     * Create parking slots for a given area.
     */
    private function createParkingSlotsForArea(ParkingLocation $area): void
    {
        $slotTypes = ['regular', 'vip', 'disabled', 'electric'];
        $floors = ['G', '1', '2']; // Ground, First, Second
        $sections = ['A', 'B', 'C', 'D'];

        $slotCount = 0;
        $targetSlots = $area->total_capacity;

        foreach ($floors as $floor) {
            foreach ($sections as $section) {
                for ($number = 1; $number <= 10; $number++) {
                    if ($slotCount >= $targetSlots) {
                        break 3; // Break all loops
                    }

                    $slotNumber = "{$floor}{$section}{$number}";
                    $slotType = $slotTypes[array_rand($slotTypes)];

                    // Determine supported vehicle types based on slot type
                    $vehicleTypes = match($slotType) {
                        'electric' => ['car'],
                        'disabled' => ['car'],
                        'vip' => ['car'],
                        default => ['car', 'motorcycle'],
                    };

                    // Set dimensions based on slot type
                    [$length, $width] = match($slotType) {
                        'vip' => [5.5, 2.5],
                        'disabled' => [5.5, 2.5],
                        'electric' => [5.0, 2.3],
                        default => [5.0, 2.3],
                    };

                    ParkingSlot::firstOrCreate(
                        [
                            'parking_location_id' => $area->id,
                            'slot_number' => $slotNumber
                        ],
                        [
                            'floor' => $floor,
                            'section' => $section,
                            'slot_type' => $slotType,
                            'vehicle_types' => $vehicleTypes,
                            'status' => 'available',
                            'length_meters' => $length,
                            'width_meters' => $width,
                            'notes' => null,
                            'is_active' => true,
                            'last_occupied_at' => null,
                        ]
                    );

                    $slotCount++;
                }
            }
        }
    }
}
