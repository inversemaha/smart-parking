<?php

namespace App\Shared\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $payment;

    public function __construct($payment)
    {
        $this->payment = $payment;
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
            ->subject(__('notifications.payment_received_subject'))
            ->greeting(__('notifications.payment_received_greeting', ['name' => $notifiable->name]))
            ->line(__('notifications.payment_received_message', ['amount' => $this->payment->amount ?? 0]))
            ->line(__('notifications.payment_details', [
                'payment_id' => $this->payment->id,
                'method' => $this->payment->payment_method ?? 'N/A',
                'transaction_id' => $this->payment->transaction_id ?? 'N/A'
            ]))
            ->action(__('notifications.view_payment'), route('payments.show', $this->payment->id))
            ->line(__('notifications.payment_footer'));
    }
}
