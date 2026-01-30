<?php

namespace Database\Seeders;

use App\Domains\User\Models\Permission;
use Illuminate\Database\Seeder;

class GatePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $permissions = [
            // Gate Operation Permissions
            [
                'name' => 'operate.gates',
                'module' => 'gate',
                'description' => 'Can operate parking gates for vehicle entry/exit',
                'is_active' => true,
            ],
            [
                'name' => 'view.gate.logs',
                'module' => 'gate',
                'description' => 'Can view gate operation logs and history',
                'is_active' => true,
            ],
            [
                'name' => 'manage.gate.settings',
                'module' => 'gate',
                'description' => 'Can configure gate settings and parameters',
                'is_active' => true,
            ],

            // Additional Sidebar Permissions
            [
                'name' => 'manage_bookings',
                'module' => 'booking',
                'description' => 'Can manage parking bookings',
                'is_active' => true,
            ],
            [
                'name' => 'view_logs',
                'module' => 'system',
                'description' => 'Can view system logs and monitoring data',
                'is_active' => true,
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        $this->command->info('Gate and additional permissions seeded successfully!');
    }
}
