<?php

namespace App\Shared\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;
    protected $reminderType;

    /**
     * Create a new notification instance.
     */
    public function __construct($booking, string $reminderType = 'start')
    {
        $this->booking = $booking;
        $this->reminderType = $reminderType; // 'start', 'end', 'overdue'
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

        $mailMessage = new MailMessage;

        switch ($this->reminderType) {
            case 'start':
                $mailMessage
                    ->subject(__('Parking Booking Starting Soon'))
                    ->greeting(__('Hello :name!', ['name' => $notifiable->name]))
                    ->line(__('Your parking booking is starting in 30 minutes.'))
                    ->line(__('Booking Details:'))
                    ->line(__('Parking Area: :area', ['area' => $this->booking->parkingSlot->parkingArea->name]))
                    ->line(__('Slot: :slot', ['slot' => $this->booking->parkingSlot->slot_number]))
                    ->line(__('Start Time: :time', ['time' => $this->booking->start_time->format('M d, Y H:i')]))
                    ->action(__('View Booking'), url('/dashboard/bookings/' . $this->booking->id));
                break;

            case 'end':
                $mailMessage
                    ->subject(__('Parking Booking Ending Soon'))
                    ->greeting(__('Hello :name!', ['name' => $notifiable->name]))
                    ->line(__('Your parking booking is ending in 15 minutes.'))
                    ->line(__('Please return to your vehicle to avoid overtime charges.'))
                    ->line(__('End Time: :time', ['time' => $this->booking->end_time->format('M d, Y H:i')]))
                    ->action(__('Extend Booking'), url('/dashboard/bookings/' . $this->booking->id . '/extend'));
                break;

            case 'overdue':
                $mailMessage
                    ->subject(__('Parking Booking Overdue'))
                    ->greeting(__('Hello :name!', ['name' => $notifiable->name]))
                    ->line(__('Your parking booking has exceeded the allocated time.'))
                    ->line(__('Overtime charges may apply.'))
                    ->line(__('Expected End: :time', ['time' => $this->booking->end_time->format('M d, Y H:i')]))
                    ->action(__('View Charges'), url('/dashboard/bookings/' . $this->booking->id));
                break;
        }

        return $mailMessage->line(__('Thank you for using our Smart Parking System!'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        $messages = [
            'start' => 'Your parking booking is starting in 30 minutes.',
            'end' => 'Your parking booking is ending in 15 minutes.',
            'overdue' => 'Your parking booking is overdue. Additional charges may apply.',
        ];

        return [
            'title' => 'Booking Reminder',
            'message' => $messages[$this->reminderType] ?? 'Booking reminder',
            'booking_id' => $this->booking->id,
            'reminder_type' => $this->reminderType,
            'action_url' => url('/dashboard/bookings/' . $this->booking->id),
        ];
    }
}
