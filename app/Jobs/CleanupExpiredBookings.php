<?php

namespace App\Jobs;

use App\Domains\Booking\Models\Booking;
use App\Models\SystemSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Job to cleanup expired bookings
 */
class CleanupExpiredBookings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Get booking expiry duration from system settings (default 15 minutes)
            $expiryMinutes = SystemSetting::get('booking_expiry_duration', 15);
            $expiryTime = Carbon::now()->subMinutes($expiryMinutes);

            // Find expired bookings
            $expiredBookings = Booking::with(['parkingSlot'])
                ->where('status', 'active')
                ->where('created_at', '<=', $expiryTime)
                ->whereDoesntHave('vehicleEntries') // No vehicle has entered yet
                ->get();

            $cleanedCount = 0;

            foreach ($expiredBookings as $booking) {
                // Update booking status to expired
                $booking->update([
                    'status' => 'expired',
                    'expired_at' => Carbon::now(),
                ]);

                // Free up the parking slot
                if ($booking->parkingSlot) {
                    $booking->parkingSlot->update([
                        'status' => 'available',
                        'current_booking_id' => null,
                    ]);

                    // Log slot history
                    $booking->parkingSlot->slotHistories()->create([
                        'booking_id' => $booking->id,
                        'status' => 'freed',
                        'action' => 'auto_cleanup_expired',
                        'performed_by' => null,
                        'notes' => 'Automatically freed due to booking expiry',
                    ]);
                }

                // TODO: Process refunds if payment was made
                // TODO: Send notification to user about expired booking

                $cleanedCount++;
            }

            Log::info("Cleaned up {$cleanedCount} expired bookings");

        } catch (\Exception $e) {
            Log::error('Error cleaning up expired bookings: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('CleanupExpiredBookings job failed: ' . $exception->getMessage());
    }
}
