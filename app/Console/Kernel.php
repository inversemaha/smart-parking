<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Shared\Jobs\CleanupExpiredBookings;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Clean up expired bookings every 6 hours
        $schedule->job(new CleanupExpiredBookings())
            ->everyFourHours()
            ->onQueue('cleanup')
            ->name('cleanup-expired-bookings')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/scheduler.log'));

        // Generate daily reports at 11 PM
        $schedule->command('parking:generate-reports')
            ->dailyAt('23:00')
            ->name('generate-daily-reports')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/reports.log'));

        // Payment reconciliation at 11:30 PM
        $schedule->command('parking:reconcile-payments')
            ->dailyAt('23:30')
            ->name('reconcile-payments')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/payments.log'));

        // BRTA sync daily at 9 AM
        $schedule->command('parking:sync-brta --limit=100')
            ->dailyAt('09:00')
            ->name('sync-brta-daily')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/brta-sync.log'));

        // System health check every 15 minutes
        $schedule->command('parking:system-health')
            ->everyFifteenMinutes()
            ->name('system-health-check')
            ->appendOutputTo(storage_path('logs/health-check.log'));

        // Clear expired password reset tokens
        $schedule->command('auth:clear-resets')
            ->everyFifteenMinutes()
            ->name('clear-password-resets');

        // Prune old failed jobs (older than 7 days)
        $schedule->command('queue:prune-failed --hours=168')
            ->daily()
            ->name('prune-failed-jobs');

        // Clear old audit logs (older than 90 days)
        $schedule->call(function () {
            \App\Models\AuditLog::where('created_at', '<', now()->subDays(90))->delete();
        })->daily()
            ->name('cleanup-old-audit-logs')
            ->appendOutputTo(storage_path('logs/cleanup.log'));

        // Send booking reminders
        $schedule->call(function () {
            $bookings = \App\Models\Booking::where('status', 'confirmed')
                ->where('start_time', '>', now())
                ->where('start_time', '<=', now()->addMinutes(30))
                ->whereDoesntHave('notifications', function ($query) {
                    $query->where('data->reminder_type', 'start')
                        ->where('created_at', '>', now()->subHour());
                })->get();

            foreach ($bookings as $booking) {
                $booking->user->notify(
                    new \App\Shared\Notifications\BookingReminderNotification($booking, 'start')
                );
            }
        })->everyFiveMinutes()
            ->name('send-booking-start-reminders');

        // Send booking end reminders
        $schedule->call(function () {
            $bookings = \App\Models\Booking::where('status', 'active')
                ->where('end_time', '>', now())
                ->where('end_time', '<=', now()->addMinutes(15))
                ->whereDoesntHave('notifications', function ($query) {
                    $query->where('data->reminder_type', 'end')
                        ->where('created_at', '>', now()->subHour());
                })->get();

            foreach ($bookings as $booking) {
                $booking->user->notify(
                    new \App\Shared\Notifications\BookingReminderNotification($booking, 'end')
                );
            }
        })->everyTenMinutes()
            ->name('send-booking-end-reminders');

        // Monitor overdue bookings
        $schedule->call(function () {
            $overdueBookings = \App\Models\Booking::where('status', 'active')
                ->where('end_time', '<', now())
                ->get();

            foreach ($overdueBookings as $booking) {
                // Update booking status
                $booking->update(['status' => 'overdue']);

                // Send notification
                $booking->user->notify(
                    new \App\Shared\Notifications\BookingReminderNotification($booking, 'overdue')
                );
            }
        })->everyTenMinutes()
            ->name('monitor-overdue-bookings');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
