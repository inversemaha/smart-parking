<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Smart Parking System Configuration
    |--------------------------------------------------------------------------
    */

    // API Security
    'api_keys' => [
        env('BRTA_API_KEY'),
        env('EXTERNAL_API_KEY'),
    ],

    // Admin IP Whitelist (CIDR notation supported)
    'admin_ip_whitelist' => [
        '127.0.0.1',
        '::1',
        // Add your office/admin IPs here
        // '192.168.1.0/24',
        // '10.0.0.0/8',
    ],

    // Rate Limiting
    'rate_limits' => [
        'api' => [
            'attempts' => 60,
            'decay' => 60, // 1 minute
        ],
        'auth' => [
            'attempts' => 5,
            'decay' => 300, // 5 minutes
        ],
        'payment' => [
            'attempts' => 3,
            'decay' => 600, // 10 minutes
        ],
        'brta' => [
            'attempts' => 10,
            'decay' => 3600, // 1 hour
        ],
    ],

    // Payment Gateway Settings
    'sslcommerz' => [
        'store_id' => env('SSLCOMMERZ_STORE_ID'),
        'store_password' => env('SSLCOMMERZ_STORE_PASSWORD'),
        'sandbox' => env('SSLCOMMERZ_SANDBOX', true),
    ],

    'bkash' => [
        'app_key' => env('BKASH_APP_KEY'),
        'app_secret' => env('BKASH_APP_SECRET'),
        'username' => env('BKASH_USERNAME'),
        'password' => env('BKASH_PASSWORD'),
        'sandbox' => env('BKASH_SANDBOX', true),
    ],

    'nagad' => [
        'merchant_id' => env('NAGAD_MERCHANT_ID'),
        'merchant_private_key' => env('NAGAD_MERCHANT_PRIVATE_KEY'),
        'pgp_public_key' => env('NAGAD_PGP_PUBLIC_KEY'),
        'sandbox' => env('NAGAD_SANDBOX', true),
    ],

    // BRTA Integration
    'brta' => [
        'base_url' => env('BRTA_BASE_URL', 'https://api.brta.gov.bd'),
        'api_key' => env('BRTA_API_KEY'),
        'timeout' => env('BRTA_TIMEOUT', 30),
        'retry_attempts' => env('BRTA_RETRY_ATTEMPTS', 3),
    ],

    // Business Rules
    'booking' => [
        'max_advance_days' => env('BOOKING_MAX_ADVANCE_DAYS', 30),
        'min_advance_minutes' => env('BOOKING_MIN_ADVANCE_MINUTES', 30),
        'max_duration_hours' => env('BOOKING_MAX_DURATION_HOURS', 24),
        'grace_period_minutes' => env('BOOKING_GRACE_PERIOD_MINUTES', 15),
        'cancellation_deadline_hours' => env('BOOKING_CANCELLATION_DEADLINE_HOURS', 1),
    ],

    // Pricing
    'pricing' => [
        'currency' => 'BDT',
        'tax_rate' => env('PARKING_TAX_RATE', 0.15), // 15% tax
        'late_fee_rate' => env('PARKING_LATE_FEE_RATE', 0.50), // 50% extra for overtime
        'cancellation_fee_rate' => env('PARKING_CANCELLATION_FEE_RATE', 0.10), // 10% cancellation fee
    ],

    // Notifications
    'notifications' => [
        'reminder_minutes_before_start' => 30,
        'reminder_minutes_before_end' => 15,
        'overdue_check_interval_minutes' => 10,
    ],

    // File Upload Limits
    'uploads' => [
        'max_file_size' => env('MAX_UPLOAD_SIZE', 5120), // KB
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'pdf'],
        'document_storage_path' => env('DOCUMENT_STORAGE_PATH', 'documents'),
    ],

    // Security
    'security' => [
        'max_login_attempts' => env('MAX_LOGIN_ATTEMPTS', 5),
        'lockout_duration_minutes' => env('LOCKOUT_DURATION_MINUTES', 15),
        'session_timeout_minutes' => env('SESSION_TIMEOUT_MINUTES', 120),
        'password_reset_expire_minutes' => env('PASSWORD_RESET_EXPIRE_MINUTES', 60),
    ],

    // Cache Settings
    'cache' => [
        'parking_availability_ttl' => env('CACHE_PARKING_AVAILABILITY_TTL', 300), // 5 minutes
        'brta_data_ttl' => env('CACHE_BRTA_DATA_TTL', 86400), // 24 hours
        'user_session_ttl' => env('CACHE_USER_SESSION_TTL', 7200), // 2 hours
        'payment_status_ttl' => env('CACHE_PAYMENT_STATUS_TTL', 1800), // 30 minutes
    ],

    // Audit & Logging
    'audit' => [
        'retention_days' => env('AUDIT_RETENTION_DAYS', 90),
        'log_failed_attempts' => env('AUDIT_LOG_FAILED_ATTEMPTS', true),
        'log_data_changes' => env('AUDIT_LOG_DATA_CHANGES', true),
        'sensitive_fields' => ['password', 'token', 'secret', 'key'],
    ],

    // System Limits
    'limits' => [
        'vehicles_per_user' => env('MAX_VEHICLES_PER_USER', 5),
        'active_bookings_per_user' => env('MAX_ACTIVE_BOOKINGS_PER_USER', 3),
        'booking_extension_limit' => env('BOOKING_EXTENSION_LIMIT', 2),
        'api_requests_per_minute' => env('API_REQUESTS_PER_MINUTE', 60),
    ],
];
