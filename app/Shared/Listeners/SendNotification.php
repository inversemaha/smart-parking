<?php

namespace App\Shared\Listeners;

use App\Shared\Events\VehicleCreated;
use App\Shared\Events\VehicleVerified;
use App\Shared\Events\BookingCreated;
use App\Shared\Events\PaymentCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle vehicle created event.
     */
    public function handleVehicleCreated(VehicleCreated $event)
    {
        // Send notification to user about vehicle creation and verification process
        Log::info('Vehicle created notification sent', [
            'vehicle_id' => $event->vehicle->id,
            'user_id' => $event->vehicle->user_id,
        ]);

        // TODO: Implement actual notification sending (email, SMS, push)
    }

    /**
     * Handle vehicle verified event.
     */
    public function handleVehicleVerified(VehicleVerified $event)
    {
        // Send notification to user about vehicle verification completion
        Log::info('Vehicle verification notification sent', [
            'vehicle_id' => $event->vehicle->id,
            'user_id' => $event->vehicle->user_id,
            'method' => $event->verificationMethod,
        ]);
    }

    /**
     * Handle booking created event.
     */
    public function handleBookingCreated(BookingCreated $event)
    {
        // Send booking confirmation notification
        Log::info('Booking created notification sent', [
            'booking_id' => $event->booking->id,
            'user_id' => $event->booking->user_id,
        ]);
    }

    /**
     * Handle payment completed event.
     */
    public function handlePaymentCompleted(PaymentCompleted $event)
    {
        // Send payment receipt notification
        Log::info('Payment completed notification sent', [
            'payment_id' => $event->payment->id,
            'user_id' => $event->payment->user_id,
            'amount' => $event->payment->amount,
        ]);
    }
}
