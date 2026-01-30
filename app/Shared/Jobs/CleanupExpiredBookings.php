<?php

namespace App\Shared\Jobs;

use App\Domains\Booking\Repositories\BookingRepository;
use App\Domains\Parking\Repositories\ParkingSlotRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CleanupExpiredBookings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;

    /**
     * Execute the job.
     */
    public function handle(
        BookingRepository $bookingRepository,
        ParkingSlotRepository $parkingSlotRepository
    ) {
        try {
            // Get overdue bookings
            $overdueBookings = $bookingRepository->getOverdueBookings();

            $processedCount = 0;

            foreach ($overdueBookings as $booking) {
                // Mark booking as expired
                $booking->update([
                    'status' => 'expired',
                    'expired_at' => now(),
                ]);

                // Release the parking slot
                if ($booking->parkingSlot) {
                    $booking->parkingSlot->update(['status' => 'available']);
                }

                // Create exit record if not exists
                if (!$booking->vehicleExits()->exists()) {
                    $booking->vehicleExits()->create([
                        'vehicle_id' => $booking->vehicle_id,
                        'exit_time' => $booking->end_time,
                        'exit_type' => 'auto_expired',
                        'notes' => 'Auto-expired due to timeout',
                    ]);
                }

                $processedCount++;
            }

            Log::info('Cleanup expired bookings completed', [
                'processed_count' => $processedCount,
                'execution_time' => now()->toDateTimeString(),
            ]);

        } catch (\Exception $e) {
            Log::error('Cleanup expired bookings job failed', [
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
        Log::error('Cleanup expired bookings job failed permanently', [
            'error' => $exception->getMessage(),
        ]);
    }
}
