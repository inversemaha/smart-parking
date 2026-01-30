<?php

namespace App\Shared\Services;

use App\Domains\User\Models\User;
use App\Shared\Notifications\WelcomeNotification;
use App\Shared\Notifications\PasswordChangedNotification;
use App\Shared\Notifications\BookingConfirmedNotification;
use App\Shared\Notifications\PaymentReceivedNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send welcome notification to new user
     */
    public function sendWelcomeNotification(User $user): void
    {
        try {
            $user->notify(new WelcomeNotification());
        } catch (\Exception $e) {
            Log::error('Failed to send welcome notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send password changed notification
     */
    public function sendPasswordChangedNotification(User $user): void
    {
        try {
            $user->notify(new PasswordChangedNotification());
        } catch (\Exception $e) {
            Log::error('Failed to send password changed notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send booking confirmation notification
     */
    public function sendBookingConfirmation(User $user, $booking): void
    {
        try {
            $user->notify(new BookingConfirmedNotification($booking));
        } catch (\Exception $e) {
            Log::error('Failed to send booking confirmation', [
                'user_id' => $user->id,
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send payment received notification
     */
    public function sendPaymentReceived(User $user, $payment): void
    {
        try {
            $user->notify(new PaymentReceivedNotification($payment));
        } catch (\Exception $e) {
            Log::error('Failed to send payment notification', [
                'user_id' => $user->id,
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get user notifications
     */
    public function getUserNotifications(int $userId, int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        $user = User::find($userId);
        if (!$user) {
            return collect();
        }

        return $user->notifications()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $userId, string $notificationId): bool
    {
        $user = User::find($userId);
        if (!$user) {
            return false;
        }

        $notification = $user->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
            return true;
        }

        return false;
    }

    /**
     * Mark all notifications as read for user
     */
    public function markAllAsRead(int $userId): int
    {
        $user = User::find($userId);
        if (!$user) {
            return 0;
        }

        return $user->unreadNotifications()->update(['read_at' => now()]);
    }

    /**
     * Get unread notification count
     */
    public function getUnreadCount(int $userId): int
    {
        $user = User::find($userId);
        if (!$user) {
            return 0;
        }

        return $user->unreadNotifications()->count();
    }

    /**
     * Send emergency broadcast notification
     */
    public function sendEmergencyBroadcast(string $message, array $userIds = []): void
    {
        try {
            $query = User::where('is_active', true);

            if (!empty($userIds)) {
                $query->whereIn('id', $userIds);
            }

            $users = $query->get();

            foreach ($users as $user) {
                // Send via email
                Mail::raw($message, function ($mail) use ($user) {
                    $mail->to($user->email)
                         ->subject(__('notifications.emergency_broadcast'));
                });

                // Could also send SMS here if integrated
            }

            Log::info('Emergency broadcast sent', [
                'message' => $message,
                'recipient_count' => $users->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send emergency broadcast', [
                'message' => $message,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send system maintenance notification
     */
    public function sendMaintenanceNotification(string $message, \DateTime $startTime, \DateTime $endTime): void
    {
        try {
            $users = User::where('is_active', true)->get();

            foreach ($users as $user) {
                Mail::raw($message, function ($mail) use ($user, $startTime, $endTime) {
                    $mail->to($user->email)
                         ->subject(__('notifications.maintenance_scheduled', [
                             'start' => $startTime->format('Y-m-d H:i'),
                             'end' => $endTime->format('Y-m-d H:i')
                         ]));
                });
            }

            Log::info('Maintenance notification sent', [
                'recipient_count' => $users->count(),
                'start_time' => $startTime,
                'end_time' => $endTime
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send maintenance notification', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send custom notification to specific users
     */
    public function sendCustomNotification(array $userIds, string $title, string $message): void
    {
        try {
            $users = User::whereIn('id', $userIds)->get();

            foreach ($users as $user) {
                Mail::raw($message, function ($mail) use ($user, $title) {
                    $mail->to($user->email)->subject($title);
                });
            }

            Log::info('Custom notification sent', [
                'recipient_count' => $users->count(),
                'title' => $title
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send custom notification', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
