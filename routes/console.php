<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Shared\Jobs\CleanupExpiredBookings;
use App\Domains\Payment\Services\PaymentService;
use App\Domains\Admin\Services\DashboardService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Smart Parking System Commands
Artisan::command('parking:cleanup-expired', function () {
    $this->info('Starting expired bookings cleanup...');
    CleanupExpiredBookings::dispatch();
    $this->info('Cleanup job dispatched successfully!');
})->purpose('Clean up expired bookings and free slots');

Artisan::command('parking:reconcile-payments', function () {
    $paymentService = app(PaymentService::class);
    $this->info('Starting payment reconciliation...');

    $result = $paymentService->reconcilePayments();

    $this->info("Payment reconciliation completed:");
    $this->info("- Processed: {$result['processed']}");
    $this->info("- Reconciled: {$result['reconciled']}");
    $this->info("- Failed: {$result['failed']}");
})->purpose('Reconcile payments with gateway records');

Artisan::command('parking:generate-reports', function () {
    $dashboardService = app(DashboardService::class);
    $this->info('Generating daily reports...');

    $dashboardService->generateDailyReports();

    $this->info('Daily reports generated successfully!');
})->purpose('Generate daily system reports');

Artisan::command('parking:sync-brta {--limit=50}', function () {
    $limit = $this->option('limit');
    $this->info("Syncing BRTA data for {$limit} pending vehicles...");

    // Get pending vehicles and dispatch BRTA verification jobs
    $vehicles = \App\Models\Vehicle::where('verification_status', 'pending')
        ->limit($limit)
        ->get();

    foreach ($vehicles as $vehicle) {
        \App\Shared\Jobs\ProcessBrtaVerification::dispatch($vehicle)
            ->onQueue('brta');
    }

    $this->info("Dispatched BRTA verification for {$vehicles->count()} vehicles!");
})->purpose('Sync vehicle data with BRTA');

Artisan::command('parking:system-health', function () {
    $this->info('=== Smart Parking System Health Check ===');

    // Check queue status
    $queueSize = \Illuminate\Support\Facades\Redis::llen('queues:smart_parking');
    $this->info("Queue size: {$queueSize} jobs");

    // Check database connection
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $this->info('Database: ✅ Connected');
    } catch (\Exception $e) {
        $this->error('Database: ❌ Connection failed');
    }

    // Check Redis connection
    try {
        \Illuminate\Support\Facades\Redis::ping();
        $this->info('Redis: ✅ Connected');
    } catch (\Exception $e) {
        $this->error('Redis: ❌ Connection failed');
    }

    // Check failed jobs
    $failedJobs = \Illuminate\Support\Facades\DB::table('failed_jobs')->count();
    $this->info("Failed jobs: {$failedJobs}");

    $this->info('=== Health Check Complete ===');
})->purpose('Check system health and connectivity');
