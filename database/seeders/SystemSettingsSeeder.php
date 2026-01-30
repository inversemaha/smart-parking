<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // BRTA Configuration
            [
                'key' => 'brta_enabled',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'brta',
                'description' => 'Enable/disable BRTA vehicle verification',
                'is_public' => false,
            ],
            [
                'key' => 'brta_timeout',
                'value' => '30',
                'type' => 'integer',
                'group' => 'brta',
                'description' => 'BRTA API timeout in seconds',
                'is_public' => false,
            ],

            // Booking Configuration
            [
                'key' => 'booking_expiry_duration',
                'value' => '15',
                'type' => 'integer',
                'group' => 'booking',
                'description' => 'Booking expiry duration in minutes',
                'is_public' => true,
            ],
            [
                'key' => 'max_advance_booking_days',
                'value' => '7',
                'type' => 'integer',
                'group' => 'booking',
                'description' => 'Maximum days in advance to allow booking',
                'is_public' => true,
            ],
            [
                'key' => 'allow_multiple_bookings',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'booking',
                'description' => 'Allow users to have multiple active bookings',
                'is_public' => true,
            ],

            // Payment Configuration
            [
                'key' => 'payment_timeout',
                'value' => '600',
                'type' => 'integer',
                'group' => 'payment',
                'description' => 'Payment timeout in seconds (10 minutes)',
                'is_public' => false,
            ],
            [
                'key' => 'sslcommerz_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'payment',
                'description' => 'Enable SSLCommerz payment gateway',
                'is_public' => true,
            ],
            [
                'key' => 'auto_refund_enabled',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'payment',
                'description' => 'Enable automatic refunds for failed bookings',
                'is_public' => false,
            ],

            // Session Configuration
            [
                'key' => 'session_timeout',
                'value' => '7200',
                'type' => 'integer',
                'group' => 'session',
                'description' => 'User session timeout in seconds (2 hours)',
                'is_public' => false,
            ],
            [
                'key' => 'max_concurrent_sessions',
                'value' => '3',
                'type' => 'integer',
                'group' => 'session',
                'description' => 'Maximum concurrent sessions per user',
                'is_public' => false,
            ],
            [
                'key' => 'force_logout_inactive_users',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'session',
                'description' => 'Force logout of inactive users',
                'is_public' => false,
            ],

            // Security Configuration
            [
                'key' => 'admin_ip_whitelist_enabled',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'security',
                'description' => 'Enable IP whitelist for admin access',
                'is_public' => false,
            ],
            [
                'key' => 'admin_ip_whitelist',
                'value' => '["127.0.0.1", "::1"]',
                'type' => 'json',
                'group' => 'security',
                'description' => 'Whitelisted IPs for admin access',
                'is_public' => false,
            ],
            [
                'key' => 'rate_limit_api',
                'value' => '60',
                'type' => 'integer',
                'group' => 'security',
                'description' => 'API rate limit per minute',
                'is_public' => false,
            ],

            // General Configuration
            [
                'key' => 'system_name',
                'value' => 'Smart Parking Management System',
                'type' => 'string',
                'group' => 'general',
                'description' => 'System name displayed in UI',
                'is_public' => true,
            ],
            [
                'key' => 'system_timezone',
                'value' => 'Asia/Dhaka',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Default system timezone',
                'is_public' => true,
            ],
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'general',
                'description' => 'Enable maintenance mode',
                'is_public' => true,
            ],
            [
                'key' => 'notifications_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'general',
                'description' => 'Enable system notifications',
                'is_public' => false,
            ],

            // Cache Configuration
            [
                'key' => 'cache_dashboard_stats',
                'value' => '300',
                'type' => 'integer',
                'group' => 'cache',
                'description' => 'Cache dashboard statistics for X seconds',
                'is_public' => false,
            ],
            [
                'key' => 'cache_slot_availability',
                'value' => '60',
                'type' => 'integer',
                'group' => 'cache',
                'description' => 'Cache slot availability for X seconds',
                'is_public' => false,
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
