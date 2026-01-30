<?php

namespace App\Shared\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('notifications.booking_confirmed_subject'))
            ->greeting(__('notifications.booking_confirmed_greeting', ['name' => $notifiable->name]))
            ->line(__('notifications.booking_confirmed_message', ['booking_id' => $this->booking->id]))
            ->line(__('notifications.booking_details', [
                'location' => $this->booking->parking_location ?? 'N/A',
                'start_time' => $this->booking->start_time ?? 'N/A',
                'end_time' => $this->booking->end_time ?? 'N/A'
            ]))
            ->action(__('notifications.view_booking'), route('bookings.show', $this->booking->id))
            ->line(__('notifications.booking_footer'));
    }
}
