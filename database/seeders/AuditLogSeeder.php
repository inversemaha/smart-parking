<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Shared\Models\AuditLog;
use App\Domains\User\Models\User;
use App\Domains\Booking\Models\Booking;
use App\Domains\Parking\Models\ParkingLocation;
use App\Domains\Payment\Models\Payment;

class AuditLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔄 Creating audit logs...');

        // Skip if audit logs already exist
        if (AuditLog::count() > 0) {
            $this->command->info('ℹ️ Audit logs already exist, skipping...');
            return;
        }

        $this->createSystemAuditLogs();
        $this->createUserAuditLogs();
        $this->createBookingAuditLogs();
        $this->createPaymentAuditLogs();

        $this->command->info('✅ Audit log seeding completed.');
    }

    private function createSystemAuditLogs(): void
    {
        $admins = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['admin', 'super-admin']);
        })->get();

        foreach ($admins as $admin) {
            // Login audit logs
            for ($i = 0; $i < 15; $i++) {
                \DB::table('audit_logs')->insert([
                    'user_id' => $admin->id,
                    'event' => 'login',
                    'auditable_type' => 'App\\Domains\\User\\Models\\User',
                    'auditable_id' => $admin->id,
                    'old_values' => null,
                    'new_values' => json_encode([
                        'ip_address' => '192.168.1.' . rand(1, 255),
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                    ]),
                    'url' => '/admin/login',
                    'ip_address' => '192.168.1.' . rand(1, 255),
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'created_at' => now()->subDays(rand(1, 30))->subHours(rand(1, 24))
                ]);
            }

            // System configuration changes
            AuditLog::create([
                'user_id' => $admin->id,
                'event' => 'update',
                'auditable_type' => 'App\\Models\\SystemSetting',
                'auditable_id' => null,
                'old_values' => ['hourly_rate' => 45],
                'new_values' => ['hourly_rate' => 50],
                'url' => '/admin/settings/update',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subDays(5)
            ]);
        }

        $this->command->info('✅ System audit logs created successfully.');
    }

    private function createUserAuditLogs(): void
    {
        $visitors = User::whereHas('roles', function($query) {
            $query->where('name', 'user');
        })->get();

        foreach ($visitors as $visitor) {
            // Registration audit
            AuditLog::create([
                'user_id' => $visitor->id,
                'event' => 'create',
                'auditable_type' => 'App\\Models\\User',
                'auditable_id' => $visitor->id,
                'old_values' => null,
                'new_values' => [
                    'name' => $visitor->name,
                    'email' => $visitor->email,
                    'mobile' => $visitor->mobile
                ],
                'url' => '/visitor/register',
                'ip_address' => '192.168.1.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0 (Linux; Android 10; SM-G975F) AppleWebKit/537.36',
                'created_at' => $visitor->created_at
            ]);

            // Profile updates
            AuditLog::create([
                'user_id' => $visitor->id,
                'event' => 'update',
                'auditable_type' => 'App\\Models\\User',
                'auditable_id' => $visitor->id,
                'old_values' => ['profile' => ['address' => null]],
                'new_values' => ['profile' => ['address' => 'Dhaka, Bangladesh']],
                'url' => '/visitor/profile/update',
                'ip_address' => '192.168.1.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0 (Linux; Android 10; SM-G975F) AppleWebKit/537.36',
                'created_at' => $visitor->created_at->addDays(1)
            ]);

            // Recent login logs
            for ($i = 0; $i < rand(5, 12); $i++) {
                AuditLog::create([
                    'user_id' => $visitor->id,
                    'event' => 'login',
                    'auditable_type' => 'App\\Models\\User',
                    'auditable_id' => $visitor->id,
                    'old_values' => null,
                    'new_values' => [
                        'ip_address' => '192.168.1.' . rand(1, 255),
                        'user_agent' => collect([
                            'Mozilla/5.0 (Linux; Android 10; SM-G975F) AppleWebKit/537.36',
                            'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15',
                            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                        ])->random()
                    ],
                    'url' => '/visitor/login',
                    'ip_address' => '192.168.1.' . rand(1, 255),
                    'user_agent' => 'Mobile App v1.0.0',
                    'created_at' => now()->subDays(rand(1, 15))->subHours(rand(1, 24))
                ]);
            }
        }

        $this->command->info('✅ User audit logs created successfully.');
    }

    private function createBookingAuditLogs(): void
    {
        $bookings = Booking::with('user')->get();

        foreach ($bookings as $booking) {
            // Booking creation
            AuditLog::create([
                'user_id' => $booking->user_id,
                'event' => 'create',
                'auditable_type' => 'App\\Domains\\Booking\\Models\\Booking',
                'auditable_id' => $booking->id,
                'old_values' => null,
                'new_values' => [
                    'slot_id' => $booking->slot_id,
                    'start_time' => $booking->start_time,
                    'end_time' => $booking->end_time,
                    'total_amount' => $booking->total_amount,
                    'status' => 'confirmed'
                ],
                'url' => '/visitor/bookings/create',
                'ip_address' => '192.168.1.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0 (Linux; Android 10; SM-G975F) AppleWebKit/537.36',
                'created_at' => $booking->created_at
            ]);

            // Status changes
            if ($booking->status === 'active') {
                AuditLog::create([
                    'user_id' => $booking->user_id,
                    'event' => 'update',
                    'auditable_type' => 'App\\Domains\\Booking\\Models\\Booking',
                    'auditable_id' => $booking->id,
                    'old_values' => ['status' => 'confirmed'],
                    'new_values' => ['status' => 'active'],
                    'url' => '/api/bookings/' . $booking->id . '/check-in',
                    'ip_address' => '192.168.1.' . rand(1, 255),
                    'user_agent' => 'Mobile App v1.0.0',
                    'created_at' => $booking->start_time->addMinutes(5)
                ]);
            }

            if ($booking->status === 'completed') {
                AuditLog::create([
                    'user_id' => $booking->user_id,
                    'event' => 'update',
                    'auditable_type' => 'App\\Domains\\Booking\\Models\\Booking',
                    'auditable_id' => $booking->id,
                    'old_values' => ['status' => 'active'],
                    'new_values' => ['status' => 'completed'],
                    'url' => '/api/bookings/' . $booking->id . '/check-out',
                    'ip_address' => '192.168.1.' . rand(1, 255),
                    'user_agent' => 'Mobile App v1.0.0',
                    'created_at' => $booking->end_time->addMinutes(10)
                ]);
            }

            if ($booking->status === 'cancelled') {
                AuditLog::create([
                    'user_id' => $booking->user_id,
                    'event' => 'update',
                    'auditable_type' => 'App\\Domains\\Booking\\Models\\Booking',
                    'auditable_id' => $booking->id,
                    'old_values' => ['status' => 'confirmed'],
                    'new_values' => ['status' => 'cancelled', 'reason' => 'User cancelled'],
                    'url' => '/visitor/bookings/' . $booking->id . '/cancel',
                    'ip_address' => '192.168.1.' . rand(1, 255),
                    'user_agent' => 'Mozilla/5.0 (Linux; Android 10; SM-G975F) AppleWebKit/537.36',
                    'created_at' => $booking->start_time->subHours(2)
                ]);
            }
        }

        $this->command->info('✅ Booking audit logs created successfully.');
    }

    private function createPaymentAuditLogs(): void
    {
        $payments = Payment::with('user', 'booking')->get();

        foreach ($payments as $payment) {
            // Payment initiation
            AuditLog::create([
                'user_id' => $payment->user_id,
                'event' => 'create',
                'auditable_type' => 'App\\Domains\\Payment\\Models\\Payment',
                'auditable_id' => $payment->id,
                'old_values' => null,
                'new_values' => [
                    'amount' => $payment->amount,
                    'payment_method' => $payment->payment_method,
                    'status' => 'pending'
                ],
                'url' => '/visitor/payments/initiate',
                'ip_address' => '192.168.1.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0 (Linux; Android 10; SM-G975F) AppleWebKit/537.36',
                'created_at' => $payment->created_at
            ]);

            // Payment completion
            if ($payment->status === 'completed') {
                AuditLog::create([
                    'user_id' => $payment->user_id,
                    'event' => 'update',
                    'auditable_type' => 'App\\Domains\\Payment\\Models\\Payment',
                    'auditable_id' => $payment->id,
                    'old_values' => ['status' => 'pending'],
                    'new_values' => [
                        'status' => 'completed',
                        'transaction_id' => $payment->transaction_id,
                        'paid_at' => $payment->paid_at
                    ],
                    'url' => '/payments/callback/success',
                    'ip_address' => '103.4.145.245', // SSLCommerz IP
                    'user_agent' => 'SSLCommerz Gateway',
                    'created_at' => $payment->paid_at
                ]);
            }

            if ($payment->status === 'failed') {
                AuditLog::create([
                    'user_id' => $payment->user_id,
                    'event' => 'update',
                    'auditable_type' => 'App\\Domains\\Payment\\Models\\Payment',
                    'auditable_id' => $payment->id,
                    'old_values' => ['status' => 'pending'],
                    'new_values' => [
                        'status' => 'failed',
                        'failure_reason' => 'Insufficient funds'
                    ],
                    'url' => '/payments/callback/fail',
                    'ip_address' => '103.4.145.245', // SSLCommerz IP
                    'user_agent' => 'SSLCommerz Gateway',
                    'created_at' => $payment->updated_at
                ]);
            }
        }

        $this->command->info('✅ Payment audit logs created successfully.');
    }
}
