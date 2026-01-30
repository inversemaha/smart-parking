<?php

namespace App\Shared\Notifications;

use App\Domains\User\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VehicleVerifiedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $vehicle;
    protected $verificationType;

    /**
     * Create a new notification instance.
     */
    public function __construct($vehicle, string $verificationType = 'manual')
    {
        $this->vehicle = $vehicle;
        $this->verificationType = $verificationType;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $locale = $notifiable->preferred_language ?? app()->getLocale();

        app()->setLocale($locale);

        return (new MailMessage)
            ->subject(__('Vehicle Verification Completed'))
            ->greeting(__('Hello :name!', ['name' => $notifiable->name]))
            ->line(__('Your vehicle registration has been successfully verified.'))
            ->line(__('Vehicle Details:'))
            ->line(__('Registration Number: :number', ['number' => $this->vehicle->registration_number]))
            ->line(__('Vehicle Type: :type', ['type' => $this->vehicle->vehicle_type]))
            ->line(__('Verification Type: :type', ['type' => ucfirst($this->verificationType)]))
            ->action(__('View Vehicle'), url('/dashboard/vehicles/' . $this->vehicle->id))
            ->line(__('Thank you for using our Smart Parking System!'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'title' => 'Vehicle Verified',
            'message' => 'Your vehicle ' . $this->vehicle->registration_number . ' has been verified successfully.',
            'vehicle_id' => $this->vehicle->id,
            'verification_type' => $this->verificationType,
            'action_url' => url('/dashboard/vehicles/' . $this->vehicle->id),
        ];
    }
}
