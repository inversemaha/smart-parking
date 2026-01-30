<?php

namespace App\Shared\Jobs;

use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Vehicle\Services\BrtaService;
use App\Shared\Events\VehicleVerified;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessBrtaVerification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Vehicle $vehicle;
    public int $tries = 3;
    public int $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
    }

    /**
     * Execute the job.
     */
    public function handle(BrtaService $brtaService)
    {
        try {
            $result = $brtaService->verifyVehicle($this->vehicle);

            if ($result) {
                // Fire vehicle verified event
                event(new VehicleVerified($this->vehicle, 'brta'));

                Log::info('BRTA verification completed successfully', [
                    'vehicle_id' => $this->vehicle->id,
                    'registration_number' => $this->vehicle->registration_number,
                ]);
            } else {
                Log::warning('BRTA verification failed', [
                    'vehicle_id' => $this->vehicle->id,
                    'registration_number' => $this->vehicle->registration_number,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('BRTA verification job failed', [
                'vehicle_id' => $this->vehicle->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception)
    {
        Log::error('BRTA verification job failed permanently', [
            'vehicle_id' => $this->vehicle->id,
            'error' => $exception->getMessage(),
        ]);

        // Mark vehicle verification as failed
        $this->vehicle->update([
            'verification_status' => 'failed',
            'verification_notes' => 'BRTA verification failed: ' . $exception->getMessage(),
        ]);
    }
}
