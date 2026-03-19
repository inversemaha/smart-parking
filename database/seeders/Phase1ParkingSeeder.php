<?php

namespace Database\Seeders;

use App\Domains\Parking\Models\ParkingZone;
use App\Domains\Parking\Models\ParkingFloor;
use App\Domains\Parking\Models\VehicleType;
use App\Domains\Parking\Models\ParkingRate;
use Illuminate\Database\Seeder;

class Phase1ParkingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Vehicle Types
        $carType = VehicleType::create([
            'name' => 'Car',
            'description' => 'Standard 4-door sedan or similar',
            'width' => 1.8,
            'height' => 1.5,
            'length' => 4.5,
            'rate_multiplier' => 1.0,
            'icon_url' => null,
            'display_order' => 1,
            'is_active' => true,
        ]);

        $suvType = VehicleType::create([
            'name' => 'SUV',
            'description' => 'Sport Utility Vehicle (takes more space)',
            'width' => 2.0,
            'height' => 1.7,
            'length' => 4.8,
            'rate_multiplier' => 1.25,
            'icon_url' => null,
            'display_order' => 2,
            'is_active' => true,
        ]);

        $bikeType = VehicleType::create([
            'name' => 'Motorcycle/Bike',
            'description' => '2-wheeler motorcycle or scooter',
            'width' => 0.8,
            'height' => 1.2,
            'length' => 2.0,
            'rate_multiplier' => 0.5,
            'icon_url' => null,
            'display_order' => 3,
            'is_active' => true,
        ]);

        $vanType = VehicleType::create([
            'name' => 'Van/Truck',
            'description' => 'Commercial vehicle or cargo van',
            'width' => 2.5,
            'height' => 2.0,
            'length' => 5.5,
            'rate_multiplier' => 1.5,
            'icon_url' => null,
            'display_order' => 4,
            'is_active' => true,
        ]);

        // Create Parking Zones
        $zoneA = ParkingZone::create([
            'name' => 'Zone A - Premium',
            'building_id' => 'BLDG-001',
            'description' => 'Premium parking zone near main entrance',
            'location' => 'Building A, Ground to 3rd Floor',
            'latitude' => 23.8103,
            'longitude' => 90.3563,
            'total_capacity' => 150,
            'current_occupancy' => 0,
            'is_active' => true,
        ]);

        $zoneB = ParkingZone::create([
            'name' => 'Zone B - Standard',
            'building_id' => 'BLDG-001',
            'description' => 'Standard parking zone',
            'location' => 'Building B, Basement to 2nd Floor',
            'latitude' => 23.8105,
            'longitude' => 90.3565,
            'total_capacity' => 200,
            'current_occupancy' => 0,
            'is_active' => true,
        ]);

        $zoneC = ParkingZone::create([
            'name' => 'Zone C - Economy',
            'building_id' => 'BLDG-002',
            'description' => 'Economy parking zone',
            'location' => 'Building C, Ground to 2nd Floor',
            'latitude' => 23.8107,
            'longitude' => 90.3567,
            'total_capacity' => 100,
            'current_occupancy' => 0,
            'is_active' => true,
        ]);

        // Create Parking Floors for Zone A
        $floorA1 = ParkingFloor::create([
            'zone_id' => $zoneA->id,
            'floor_number' => 1,
            'floor_name' => 'Ground Floor',
            'description' => 'Ground floor of Zone A (near main entrance)',
            'total_capacity' => 50,
            'current_occupancy' => 0,
            'hourly_rate' => 50.00,
            'daily_rate' => 400.00,
            'is_active' => true,
        ]);

        $floorA2 = ParkingFloor::create([
            'zone_id' => $zoneA->id,
            'floor_number' => 2,
            'floor_name' => '2nd Floor',
            'description' => 'Second floor of Zone A',
            'total_capacity' => 50,
            'current_occupancy' => 0,
            'hourly_rate' => 45.00,
            'daily_rate' => 360.00,
            'is_active' => true,
        ]);

        $floorA3 = ParkingFloor::create([
            'zone_id' => $zoneA->id,
            'floor_number' => 3,
            'floor_name' => '3rd Floor',
            'description' => 'Third floor of Zone A',
            'total_capacity' => 50,
            'current_occupancy' => 0,
            'hourly_rate' => 40.00,
            'daily_rate' => 320.00,
            'is_active' => true,
        ]);

        // Create Parking Floors for Zone B
        $floorB1 = ParkingFloor::create([
            'zone_id' => $zoneB->id,
            'floor_number' => 1,
            'floor_name' => 'Basement',
            'description' => 'Basement level of Zone B',
            'total_capacity' => 100,
            'current_occupancy' => 0,
            'hourly_rate' => 30.00,
            'daily_rate' => 240.00,
            'is_active' => true,
        ]);

        $floorB2 = ParkingFloor::create([
            'zone_id' => $zoneB->id,
            'floor_number' => 2,
            'floor_name' => 'Ground Floor',
            'description' => 'Ground floor of Zone B',
            'total_capacity' => 100,
            'current_occupancy' => 0,
            'hourly_rate' => 35.00,
            'daily_rate' => 280.00,
            'is_active' => true,
        ]);

        // Create Parking Rates for Zone A (Premium rates)
        ParkingRate::create([
            'zone_id' => $zoneA->id,
            'vehicle_type_id' => $carType->id,
            'hourly_rate' => 50.00,
            'daily_rate' => 400.00,
            'peak_hour_rate' => 75.00,
            'off_peak_rate' => 40.00,
            'peak_hours_start' => '08:00',
            'peak_hours_end' => '18:00',
            'is_active' => true,
        ]);

        ParkingRate::create([
            'zone_id' => $zoneA->id,
            'vehicle_type_id' => $suvType->id,
            'hourly_rate' => 62.50,
            'daily_rate' => 500.00,
            'peak_hour_rate' => 93.75,
            'off_peak_rate' => 50.00,
            'peak_hours_start' => '08:00',
            'peak_hours_end' => '18:00',
            'is_active' => true,
        ]);

        ParkingRate::create([
            'zone_id' => $zoneA->id,
            'vehicle_type_id' => $bikeType->id,
            'hourly_rate' => 25.00,
            'daily_rate' => 200.00,
            'peak_hour_rate' => 37.50,
            'off_peak_rate' => 20.00,
            'peak_hours_start' => '08:00',
            'peak_hours_end' => '18:00',
            'is_active' => true,
        ]);

        ParkingRate::create([
            'zone_id' => $zoneA->id,
            'vehicle_type_id' => $vanType->id,
            'hourly_rate' => 75.00,
            'daily_rate' => 600.00,
            'peak_hour_rate' => 112.50,
            'off_peak_rate' => 60.00,
            'peak_hours_start' => '08:00',
            'peak_hours_end' => '18:00',
            'is_active' => true,
        ]);

        // Create Parking Rates for Zone B (Standard rates)
        ParkingRate::create([
            'zone_id' => $zoneB->id,
            'vehicle_type_id' => $carType->id,
            'hourly_rate' => 30.00,
            'daily_rate' => 240.00,
            'peak_hour_rate' => 45.00,
            'off_peak_rate' => 25.00,
            'peak_hours_start' => '09:00',
            'peak_hours_end' => '17:00',
            'is_active' => true,
        ]);

        ParkingRate::create([
            'zone_id' => $zoneB->id,
            'vehicle_type_id' => $suvType->id,
            'hourly_rate' => 37.50,
            'daily_rate' => 300.00,
            'peak_hour_rate' => 56.25,
            'off_peak_rate' => 31.25,
            'peak_hours_start' => '09:00',
            'peak_hours_end' => '17:00',
            'is_active' => true,
        ]);

        ParkingRate::create([
            'zone_id' => $zoneB->id,
            'vehicle_type_id' => $bikeType->id,
            'hourly_rate' => 15.00,
            'daily_rate' => 120.00,
            'peak_hour_rate' => 22.50,
            'off_peak_rate' => 12.50,
            'peak_hours_start' => '09:00',
            'peak_hours_end' => '17:00',
            'is_active' => true,
        ]);

        // Create Parking Rates for Zone C (Economy rates)
        ParkingRate::create([
            'zone_id' => $zoneC->id,
            'vehicle_type_id' => $carType->id,
            'hourly_rate' => 20.00,
            'daily_rate' => 160.00,
            'is_active' => true,
        ]);

        ParkingRate::create([
            'zone_id' => $zoneC->id,
            'vehicle_type_id' => $suvType->id,
            'hourly_rate' => 25.00,
            'daily_rate' => 200.00,
            'is_active' => true,
        ]);
    }
}
