<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Parking\Models\{ParkingSession, ParkingZone, ParkingGate, ParkingRate};
use App\Domains\Vehicle\Models\Vehicle;
use Carbon\Carbon;

class Phase3ParkingSessionsSeeder extends Seeder
{
    /**
     * Seed parking sessions with realistic entry/exit patterns
     */
    public function run(): void
    {
        // Get zones with rates
        $zones = ParkingZone::with(['gates', 'floors', 'rates'])->get();
        $vehicles = Vehicle::where('verification_status', 'verified')
            ->where('is_active', true)
            ->inRandomOrder()
            ->limit(10)
            ->get();
        
        if ($vehicles->isEmpty() || $zones->isEmpty()) {
            $this->command->warn('Skipping Phase 3 seeding: No vehicles or zones found');
            return;
        }

        $sessionsCreated = 0;

        // 1. Create current active sessions
        foreach (range(1, 5) as $i) {
            $vehicle = $vehicles->random();
            $zone = $zones->random();
            $gate = $zone->gates->count() > 0 ? $zone->gates->first() : null;
            $rate = $zone->rates->count() > 0 ? $zone->rates->first() : null;

            // Active session (just entered)
            ParkingSession::create([
                'vehicle_id' => $vehicle->id,
                'zone_id' => $zone->id,
                'floor_id' => $zone->floors->count() > 0 ? $zone->floors->random()->id : null,
                'entry_gate_id' => $gate?->id,
                'license_plate' => $vehicle->registration_number,
                'session_status' => 'active',
                'vehicle_category' => $vehicle->type->name ?? 'Car',
                'parking_rate_id' => $rate?->id,
                'base_rate_per_hour' => $rate?->hourly_rate ?? 50,
                'vehicle_multiplier' => $vehicle->type?->rate_multiplier ?? 1.0,
                'entry_time' => now()->subMinutes(rand(30, 300)),
                'entry_metadata' => json_encode(['sensor' => 'Gate-' . ($i % 2 ? 'Entry' : 'Exit')]),
                'charging_status' => 'pending',
            ]);
            $sessionsCreated++;
        }

        // 2. Create recently completed sessions
        foreach (range(1, 8) as $i) {
            $vehicle = $vehicles->random();
            $zone = $zones->random();
            $entryGate = $zone->gates->count() > 0 ? $zone->gates->where('gate_type', 'entry')->first() ?? $zone->gates->first() : null;
            $exitGate = $zone->gates->count() > 0 ? $zone->gates->where('gate_type', 'exit')->first() ?? $zone->gates->last() : null;
            $rate = $zone->rates->count() > 0 ? $zone->rates->first() : null;

            $entryTime = now()->subHours($i)->subMinutes(rand(0, 59));
            $exitTime = $entryTime->copy()->addMinutes(rand(45, 480)); // 45 min to 8 hours
            
            $durationMinutes = $entryTime->diffInMinutes($exitTime);
            $durationHours = $durationMinutes / 60;

            // Calculate charges
            $baseRate = $rate?->hourly_rate ?? 50;
            $multiplier = $vehicle->type?->rate_multiplier ?? 1.0;
            $adjustedRate = $baseRate * $multiplier;
            
            $baseCharge = round($durationHours * $adjustedRate, 2);
            $peakCharge = 0; // Simplified for seeding
            $totalCharge = $baseCharge;

            $session = ParkingSession::create([
                'vehicle_id' => $vehicle->id,
                'zone_id' => $zone->id,
                'floor_id' => $zone->floors->count() > 0 ? $zone->floors->random()->id : null,
                'entry_gate_id' => $entryGate?->id,
                'exit_gate_id' => $exitGate?->id,
                'license_plate' => $vehicle->registration_number,
                'session_status' => 'completed',
                'vehicle_category' => $vehicle->type->name ?? 'Car',
                'parking_rate_id' => $rate?->id,
                'base_rate_per_hour' => $baseRate,
                'vehicle_multiplier' => $multiplier,
                'entry_time' => $entryTime,
                'exit_time' => $exitTime,
                'duration_minutes' => $durationMinutes,
                'total_hours' => round($durationHours, 2),
                'peak_hours' => 0,
                'base_charge' => $baseCharge,
                'peak_charge' => $peakCharge,
                'total_charge' => $totalCharge,
                'charging_status' => $i % 3 === 0 ? 'collected' : 'pending',
                'entry_metadata' => json_encode(['sensor' => 'Entry-' . rand(1, 3)]),
                'exit_metadata' => json_encode(['sensor' => 'Exit-' . rand(1, 2)]),
                'notes' => 'Seeded session ' . $i,
            ]);
            
            $sessionsCreated++;
        }

        // 3. Create extended sessions
        foreach (range(1, 3) as $i) {
            $vehicle = $vehicles->random();
            $zone = $zones->random();
            $entryGate = $zone->gates->count() > 0 ? $zone->gates->first() : null;
            $rate = $zone->rates->count() > 0 ? $zone->rates->first() : null;

            $entryTime = now()->subHours(rand(8, 14));
            $exitTime = $entryTime->copy()->addHours(rand(8, 14));
            $durationMinutes = $entryTime->diffInMinutes($exitTime);
            $durationHours = $durationMinutes / 60;

            $baseRate = $rate?->hourly_rate ?? 50;
            $multiplier = $vehicle->type?->rate_multiplier ?? 1.0;
            $adjustedRate = $baseRate * $multiplier;
            $baseCharge = round($durationHours * $adjustedRate, 2);

            ParkingSession::create([
                'vehicle_id' => $vehicle->id,
                'zone_id' => $zone->id,
                'floor_id' => $zone->floors->count() > 0 ? $zone->floors->random()->id : null,
                'entry_gate_id' => $entryGate?->id,
                'license_plate' => $vehicle->registration_number,
                'session_status' => 'extended',
                'vehicle_category' => $vehicle->type->name ?? 'Car',
                'parking_rate_id' => $rate?->id,
                'base_rate_per_hour' => $baseRate,
                'vehicle_multiplier' => $multiplier,
                'entry_time' => $entryTime,
                'exit_time' => $exitTime,
                'duration_minutes' => $durationMinutes,
                'total_hours' => round($durationHours, 2),
                'extension_count' => 1,
                'is_extended' => true,
                'base_charge' => $baseCharge,
                'total_charge' => $baseCharge,
                'charging_status' => 'pending',
                'entry_metadata' => json_encode(['sensor' => 'Extended-' . $i]),
                'notes' => 'Session extended by 1 hour',
            ]);
            
            $sessionsCreated++;
        }

        $this->command->info("Phase 3: {$sessionsCreated} parking sessions seeded successfully!");
        $this->command->info("  ✓ 5 active sessions");
        $this->command->info("  ✓ 8 completed sessions (3 collected, 5 pending)");
        $this->command->info("  ✓ 3 extended sessions");
    }
}
