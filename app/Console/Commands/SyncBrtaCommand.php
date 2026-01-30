<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Domains\Vehicle\Models\Vehicle;
use App\Shared\Jobs\ProcessBrtaVerification;

class SyncBrtaCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'parking:sync-brta
                            {--limit=50 : Number of vehicles to process}
                            {--force : Force sync all vehicles}
                            {--registration= : Specific vehicle registration number}';

    /**
     * The console command description.
     */
    protected $description = 'Synchronize vehicle data with BRTA system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = $this->option('limit');
        $force = $this->option('force');
        $registration = $this->option('registration');

        if ($registration) {
            return $this->syncSpecificVehicle($registration);
        }

        $this->info('Starting BRTA synchronization...');

        $query = Vehicle::query();

        if (!$force) {
            $query->where('verification_status', 'pending');
        }

        $vehicles = $query->limit($limit)->get();

        if ($vehicles->isEmpty()) {
            $this->info('No vehicles found for BRTA sync.');
            return 0;
        }

        $bar = $this->output->createProgressBar($vehicles->count());
        $bar->start();

        $dispatched = 0;
        foreach ($vehicles as $vehicle) {
            ProcessBrtaVerification::dispatch($vehicle)->onQueue('brta');
            $dispatched++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Successfully dispatched BRTA verification for {$dispatched} vehicles!");

        return 0;
    }

    /**
     * Sync specific vehicle by registration number.
     */
    private function syncSpecificVehicle(string $registration): int
    {
        $vehicle = Vehicle::where('registration_number', $registration)->first();

        if (!$vehicle) {
            $this->error("Vehicle with registration {$registration} not found.");
            return 1;
        }

        $this->info("Syncing vehicle: {$registration}");

        ProcessBrtaVerification::dispatch($vehicle)->onQueue('brta');

        $this->info('BRTA verification job dispatched successfully!');
        return 0;
    }
}
