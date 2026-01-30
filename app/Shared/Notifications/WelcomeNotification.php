<?php

namespace App\Shared\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
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
            ->subject(__('notifications.welcome_subject'))
            ->greeting(__('notifications.welcome_greeting', ['name' => $notifiable->name]))
            ->line(__('notifications.welcome_message'))
            ->action(__('notifications.login_to_dashboard'), route('dashboard.index'))
            ->line(__('notifications.welcome_footer'));
    }
}
