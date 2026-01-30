<?php

namespace App\Console\Commands;

use App\Jobs\CleanupExpiredBookings;
use App\Services\RedisCacheService;
use Illuminate\Console\Command;

/**
 * Schedule regular maintenance tasks
 */
class MaintenanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parking:maintenance {--type=all : Type of maintenance (cleanup|cache|all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run parking system maintenance tasks';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = $this->option('type');

        $this->info("Starting parking system maintenance: {$type}");

        try {
            if ($type === 'cleanup' || $type === 'all') {
                $this->cleanupExpiredBookings();
            }

            if ($type === 'cache' || $type === 'all') {
                $this->refreshCache();
            }

            $this->info('Maintenance completed successfully!');
            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Maintenance failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    /**
     * Cleanup expired bookings.
     */
    protected function cleanupExpiredBookings(): void
    {
        $this->info('Cleaning up expired bookings...');
        CleanupExpiredBookings::dispatch();
    }

    /**
     * Refresh cache data.
     */
    protected function refreshCache(): void
    {
        $this->info('Refreshing cache...');

        $cacheService = app(RedisCacheService::class);
        $cacheService->cacheAllSlotAvailability();

        $this->info('Cache refreshed');
    }
}
