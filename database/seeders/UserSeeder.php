<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\User\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Create test users for the system
     */
    public function run(): void
    {
        $this->command->info('🔄 Creating test users...');

        // Create test visitors (Regular Users)
        $visitors = [
            [
                'name' => 'Ahmed Rahman',
                'email' => 'ahmed@example.com',
                'phone' => '01711111111',
            ],
            [
                'name' => 'Fatima Khan',
                'email' => 'fatima@example.com',
                'phone' => '01722222222',
            ],
            [
                'name' => 'Karim Islam',
                'email' => 'karim@example.com',
                'phone' => '01733333333',
            ],
            [
                'name' => 'Rashida Begum',
                'email' => 'rashida@example.com',
                'phone' => '01744444444',
            ],
            [
                'name' => 'Mahmud Hassan',
                'email' => 'mahmud@example.com',
                'phone' => '01755555555',
            ]
        ];

        foreach ($visitors as $visitorData) {
            if (!User::where('email', $visitorData['email'])->exists()) {
                $visitor = User::create([
                    'name' => $visitorData['name'],
                    'email' => $visitorData['email'],
                    'phone' => $visitorData['phone'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('password123'),
                    'is_active' => true,
                ]);
                $visitor->assignRole('user');
                $this->command->info("✅ Created user: {$visitorData['email']}");
            } else {
                $this->command->info("ℹ️ User already exists: {$visitorData['email']}");
            }
        }

        // Create additional admin users if needed (avoiding conflicts)
        $adminUsers = [
            [
                'name' => 'Test Admin',
                'email' => 'test.admin@parking.com',
                'phone' => '01700000111',
                'role' => 'admin'
            ],
            [
                'name' => 'Test Operator',
                'email' => 'test.operator@parking.com',
                'phone' => '01700000222',
                'role' => 'operator'
            ]
        ];

        foreach ($adminUsers as $userData) {
            if (!User::where('email', $userData['email'])->exists()) {
                $user = User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'phone' => $userData['phone'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('password123'),
                    'is_active' => true,
                ]);
                $user->assignRole($userData['role']);
                $this->command->info("✅ Created {$userData['role']}: {$userData['email']}");
            } else {
                $this->command->info("ℹ️ {$userData['role']} already exists: {$userData['email']}");
            }
        }

        $this->command->info('✅ User seeding completed.');
    }
}
