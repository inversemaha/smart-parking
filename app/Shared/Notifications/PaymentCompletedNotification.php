<?php

namespace App\Shared\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $payment;

    /**
     * Create a new notification instance.
     */
    public function __construct($payment)
    {
        $this->payment = $payment;
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
        $locale = $notifiable->preferred_language ?? app()->getLocale();\n        \n        app()->setLocale($locale);\n        \n        return (new MailMessage)\n            ->subject(__('Payment Confirmation'))\n            ->greeting(__('Hello :name!', ['name' => $notifiable->name]))\n            ->line(__('Your payment has been processed successfully.'))\n            ->line(__('Payment Details:'))\n            ->line(__('Transaction ID: :id', ['id' => $this->payment->transaction_id]))\n            ->line(__('Amount: :amount :currency', [\n                'amount' => number_format($this->payment->amount, 2),\n                'currency' => $this->payment->currency\n            ]))\n            ->line(__('Payment Method: :method', ['method' => ucfirst($this->payment->gateway)]))\n            ->line(__('Date: :date', ['date' => $this->payment->created_at->format('M d, Y H:i')]))\n            ->action(__('View Receipt'), url('/dashboard/payments/' . $this->payment->id . '/receipt'))\n            ->line(__('Thank you for your payment!'));\n    }\n\n    /**\n     * Get the array representation of the notification.\n     */\n    public function toArray($notifiable): array\n    {\n        return [\n            'title' => 'Payment Completed',\n            'message' => 'Your payment of ' . $this->payment->amount . ' ' . $this->payment->currency . ' has been processed successfully.',\n            'payment_id' => $this->payment->id,\n            'transaction_id' => $this->payment->transaction_id,\n            'amount' => $this->payment->amount,\n            'currency' => $this->payment->currency,\n            'action_url' => url('/dashboard/payments/' . $this->payment->id . '/receipt'),\n        ];\n    }\n}
