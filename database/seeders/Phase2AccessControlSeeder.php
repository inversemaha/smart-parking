<?php

namespace Database\Seeders;

use App\Domains\Parking\Models\ParkingGate;
use App\Domains\Parking\Models\ParkingQrCode;
use App\Domains\Parking\Models\ParkingZone;
use Illuminate\Database\Seeder;

class Phase2AccessControlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all zones for gate creation (with floors relationship loaded)
        $zones = ParkingZone::with('floors')->get();

        if ($zones->isEmpty()) {
            echo "No zones found. Please run Phase1ParkingSeeder first.\n";
            return;
        }

        foreach ($zones as $zone) {
            // Create entry gate for each zone
            ParkingGate::create([
                'zone_id' => $zone->id,
                'floor_id' => $zone->floors->first()?->id,
                'name' => $zone->name . ' - Entry Gate',
                'description' => 'Main entry gate for ' . $zone->name,
                'gate_type' => 'entry',
                'gate_status' => 'operational',
                'location' => $zone->location . ' (Entrance)',
                'contact_person' => 'Gate Operator',
                'contact_phone' => '+880-1700-000000',
                'is_active' => true,
            ]);

            // Create exit gate for each zone
            ParkingGate::create([
                'zone_id' => $zone->id,
                'floor_id' => $zone->floors->last()?->id,
                'name' => $zone->name . ' - Exit Gate',
                'description' => 'Main exit gate for ' . $zone->name,
                'gate_type' => 'exit',
                'gate_status' => 'operational',
                'location' => $zone->location . ' (Exit)',
                'contact_person' => 'Gate Operator',
                'contact_phone' => '+880-1700-000000',
                'is_active' => true,
            ]);

            // Create QR code for zone
            ParkingQrCode::create([
                'zone_id' => $zone->id,
                'code' => 'ZONE_' . $zone->id . '_' . uniqid(),
                'qr_data' => $this->generatePlaceholderQrCode(['type' => 'zone', 'zone_id' => $zone->id]),
                'type' => 'zone',
                'metadata' => [
                    'zone_name' => $zone->name,
                    'zone_capacity' => $zone->total_capacity,
                    'zone_location' => $zone->location,
                ],
                'is_active' => true,
            ]);

            // Create QR codes for each floor in the zone
            foreach ($zone->floors as $floor) {
                ParkingQrCode::create([
                    'zone_id' => $zone->id,
                    'floor_id' => $floor->id,
                    'code' => 'FLOOR_' . $floor->id . '_' . uniqid(),
                    'qr_data' => $this->generatePlaceholderQrCode(['type' => 'floor', 'floor_id' => $floor->id]),
                    'type' => 'floor',
                    'metadata' => [
                        'floor_name' => $floor->floor_name,
                        'zone_name' => $zone->name,
                        'capacity' => $floor->total_capacity,
                    ],
                    'is_active' => true,
                ]);
            }
        }

        echo "Phase 2 Access Control seeding completed successfully!\n";
    }

    /**
     * Generate a placeholder QR code (stored as base64 SVG)
     */
    private function generatePlaceholderQrCode(array $data): string
    {
        // Create simple placeholder SVG that won't require external package
        $content = json_encode($data);
        $encoded = base64_encode($content);
        
        // Return a simple data URI placeholder
        // In production, you would use a QR code library to generate the actual image
        return "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMzAwIiBoZWlnaHQ9IjMwMCIgZmlsbD0iI2ZmZiIvPjx0ZXh0IHg9IjE1MCIgeT0iMTUwIiBmb250LXNpemU9IjE4IiBmaWxsPSIjMDAwIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+UVIgQ29kZTogJiN4QkFENiZ4QzIwMSIgLSMxNjM7ZXRQ0LAv5/3Nx1+59hFBAr94=";
    }
}

