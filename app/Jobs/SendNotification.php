<?php

namespace App\Jobs;

use App\Domains\User\Models\User;
use App\Models\SystemSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

/**
 * Job to send notifications (email, SMS, etc.)
 */
class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected User $user;
    protected string $type;
    protected array $data;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, string $type, array $data = [])
    {
        $this->user = $user;
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Check if notifications are enabled
            $notificationsEnabled = SystemSetting::get('notifications_enabled', true);
            if (!$notificationsEnabled) {
                Log::info('Notifications are disabled system-wide');
                return;
            }

            switch ($this->type) {
                case 'booking_confirmation':
                    $this->sendBookingConfirmation();
                    break;

                case 'booking_expired':
                    $this->sendBookingExpired();
                    break;

                case 'payment_confirmation':
                    $this->sendPaymentConfirmation();
                    break;

                case 'payment_failed':
                    $this->sendPaymentFailed();
                    break;

                case 'vehicle_verified':
                    $this->sendVehicleVerified();
                    break;

                case 'vehicle_rejected':
                    $this->sendVehicleRejected();
                    break;

                case 'parking_reminder':
                    $this->sendParkingReminder();
                    break;

                default:
                    Log::warning("Unknown notification type: {$this->type}");
            }

        } catch (\Exception $e) {
            Log::error('Error sending notification: ' . $e->getMessage(), [
                'user_id' => $this->user->id,
                'type' => $this->type,
                'data' => $this->data
            ]);
            throw $e;
        }
    }

    /**
     * Send booking confirmation notification.
     */
    protected function sendBookingConfirmation(): void
    {
        $message = "Your parking booking #{$this->data['booking_id']} has been confirmed. " .
                  "Location: {$this->data['location']}. " .
                  "Valid until: {$this->data['expires_at']}.";

        $this->sendSMS($message);

        Log::info('Booking confirmation sent', [
            'user_id' => $this->user->id,
            'booking_id' => $this->data['booking_id']
        ]);
    }

    /**
     * Send booking expired notification.
     */
    protected function sendBookingExpired(): void
    {
        $message = "Your parking booking #{$this->data['booking_id']} has expired. " .
                  "Please make a new booking if needed.";

        $this->sendSMS($message);

        Log::info('Booking expiry notification sent', [
            'user_id' => $this->user->id,
            'booking_id' => $this->data['booking_id']
        ]);
    }

    /**
     * Send payment confirmation notification.
     */
    protected function sendPaymentConfirmation(): void
    {
        $message = "Payment of BDT {$this->data['amount']} confirmed for booking #{$this->data['booking_id']}. " .
                  "Transaction ID: {$this->data['transaction_id']}.";

        $this->sendSMS($message);

        Log::info('Payment confirmation sent', [
            'user_id' => $this->user->id,
            'payment_id' => $this->data['payment_id']
        ]);
    }

    /**
     * Send payment failed notification.
     */
    protected function sendPaymentFailed(): void
    {
        $message = "Payment failed for booking #{$this->data['booking_id']}. " .
                  "Please try again or contact support.";

        $this->sendSMS($message);

        Log::info('Payment failure notification sent', [
            'user_id' => $this->user->id,
            'payment_id' => $this->data['payment_id']
        ]);
    }

    /**
     * Send vehicle verification notification.
     */
    protected function sendVehicleVerified(): void
    {
        $message = "Your vehicle {$this->data['number_plate']} has been verified successfully. " .
                  "You can now make parking bookings.";

        $this->sendSMS($message);

        Log::info('Vehicle verification notification sent', [
            'user_id' => $this->user->id,
            'vehicle_id' => $this->data['vehicle_id']
        ]);
    }

    /**
     * Send vehicle rejection notification.
     */
    protected function sendVehicleRejected(): void
    {
        $message = "Your vehicle {$this->data['number_plate']} verification was rejected. " .
                  "Reason: {$this->data['reason']}. Please contact support.";

        $this->sendSMS($message);

        Log::info('Vehicle rejection notification sent', [
            'user_id' => $this->user->id,
            'vehicle_id' => $this->data['vehicle_id']
        ]);
    }

    /**
     * Send parking reminder notification.
     */
    protected function sendParkingReminder(): void
    {
        $message = "Reminder: Your parking booking #{$this->data['booking_id']} expires in 15 minutes. " .
                  "Please proceed to the parking location.";

        $this->sendSMS($message);

        Log::info('Parking reminder sent', [
            'user_id' => $this->user->id,
            'booking_id' => $this->data['booking_id']
        ]);
    }

    /**
     * Send SMS notification.
     */
    protected function sendSMS(string $message): void
    {
        if (!$this->user->mobile) {
            Log::warning('No mobile number for user', ['user_id' => $this->user->id]);
            return;
        }

        // TODO: Integrate with SMS provider (e.g., Twilio, local BD provider)
        // For now, just log the SMS
        Log::info('SMS notification', [
            'user_id' => $this->user->id,
            'mobile' => $this->user->mobile,
            'message' => $message
        ]);

        // Example integration (uncomment when SMS provider is configured):
        /*
        try {
            Http::post(config('services.sms.url'), [
                'api_key' => config('services.sms.api_key'),
                'to' => $this->user->mobile,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            Log::error('SMS sending failed: ' . $e->getMessage());
        }
        */
    }

    /**
     * Handle job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SendNotification job failed: ' . $exception->getMessage(), [
            'user_id' => $this->user->id,
            'type' => $this->type
        ]);
    }
}
