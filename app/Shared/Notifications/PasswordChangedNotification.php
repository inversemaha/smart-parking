<?php

namespace App\Shared\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

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
            ->subject(__('notifications.password_changed_subject'))
            ->greeting(__('notifications.password_changed_greeting', ['name' => $notifiable->name]))
            ->line(__('notifications.password_changed_message'))
            ->line(__('notifications.password_changed_security'))
            ->action(__('notifications.contact_support'), config('app.url') . '/support')
            ->line(__('notifications.password_changed_footer'));
    }
}
