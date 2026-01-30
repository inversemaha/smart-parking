<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\User\Models\User;
use App\Domains\Vehicle\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    /**
     * Create test vehicles for users
     */
    public function run(): void
    {
        $this->command->info('🔄 Creating test vehicles...');

        $visitors = User::whereHas('roles', function($query) {
            $query->where('name', 'user');
        })->get();

        if ($visitors->isEmpty()) {
            $this->command->warn('⚠️ No visitors found. Run UserSeeder first.');
            return;
        }

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
                'vehicle_type' => 'car'
            ]
        ];

        foreach ($visitors as $index => $visitor) {
            if (isset($vehicleData[$index])) {
                $regNumber = $vehicleData[$index]['registration_number'];

                if (!Vehicle::where('registration_number', $regNumber)->exists()) {
                    Vehicle::create([
                        'user_id' => $visitor->id,
                        'registration_number' => $regNumber,
                        'brand' => $vehicleData[$index]['brand'],
                        'model' => $vehicleData[$index]['model'],
                        'manufacture_year' => $vehicleData[$index]['manufacture_year'],
                        'color' => $vehicleData[$index]['color'],
                        'vehicle_type' => $vehicleData[$index]['vehicle_type'],
                        'verification_status' => 'verified',
                        'verified_at' => now()->subDays(rand(1, 30))
                    ]);

                    $this->command->info("✅ Created vehicle for {$visitor->name}: {$regNumber}");
                } else {
                    $this->command->info("ℹ️ Vehicle already exists: {$regNumber}");
                }
            }

            // Add a second vehicle for some users
            if ($index < 2) {
                $secondRegNumber = 'ঢাকা-মেট্রো-চ-' . rand(10, 99) . '-' . rand(1000, 9999);

                if (!Vehicle::where('registration_number', $secondRegNumber)->exists()) {
                    Vehicle::create([
                        'user_id' => $visitor->id,
                        'registration_number' => $secondRegNumber,
                        'brand' => 'Honda',
                        'model' => 'CB Shine',
                        'manufacture_year' => 2020,
                        'color' => 'Black',
                        'vehicle_type' => 'motorcycle',
                        'verification_status' => 'verified',
                        'verified_at' => now()->subDays(rand(1, 15))
                    ]);

                    $this->command->info("✅ Created second vehicle for {$visitor->name}: {$secondRegNumber}");
                }
            }
        }

        $this->command->info('✅ Vehicle seeding completed.');
    }
}
