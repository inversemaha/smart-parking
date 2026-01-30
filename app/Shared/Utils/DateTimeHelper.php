<?php

namespace App\Shared\Utils;

class DateTimeHelper
{
    /**
     * Get Bangladesh timezone.
     */
    public static function bangladeshTimezone(): string
    {
        return 'Asia/Dhaka';
    }

    /**
     * Convert UTC time to Bangladesh time.
     */
    public static function toBangladeshTime($datetime): \Carbon\Carbon
    {
        return \Carbon\Carbon::parse($datetime)->setTimezone(self::bangladeshTimezone());
    }

    /**
     * Convert Bangladesh time to UTC.
     */
    public static function toUtc($datetime): \Carbon\Carbon
    {
        return \Carbon\Carbon::parse($datetime, self::bangladeshTimezone())->utc();
    }

    /**
     * Get current Bangladesh time.
     */
    public static function nowInBangladesh(): \Carbon\Carbon
    {
        return \Carbon\Carbon::now(self::bangladeshTimezone());
    }

    /**
     * Format time for display in Bangladesh locale.
     */
    public static function formatForDisplay($datetime, string $format = 'M d, Y h:i A'): string
    {
        return self::toBangladeshTime($datetime)->format($format);
    }

    /**
     * Get time difference in human readable format.
     */
    public static function humanDiff($datetime): string
    {
        return self::toBangladeshTime($datetime)->diffForHumans();
    }

    /**
     * Check if time is in business hours (8 AM to 10 PM Bangladesh time).
     */
    public static function isBusinessHours($datetime = null): bool
    {
        $time = $datetime ? self::toBangladeshTime($datetime) : self::nowInBangladesh();
        $hour = $time->hour;

        return $hour >= 8 && $hour < 22;
    }

    /**
     * Get next business hour if current time is outside business hours.
     */
    public static function nextBusinessHour($datetime = null): \Carbon\Carbon
    {
        $time = $datetime ? self::toBangladeshTime($datetime) : self::nowInBangladesh();

        if ($time->hour >= 22) {
            // After 10 PM, next business hour is 8 AM next day
            return $time->addDay()->setTime(8, 0, 0);
        } elseif ($time->hour < 8) {
            // Before 8 AM, next business hour is 8 AM same day
            return $time->setTime(8, 0, 0);
        }

        // Already in business hours
        return $time;
    }

    /**
     * Calculate duration between two times in hours.
     */
    public static function calculateDurationInHours($startTime, $endTime): float
    {
        $start = \Carbon\Carbon::parse($startTime);
        $end = \Carbon\Carbon::parse($endTime);

        return $end->diffInMinutes($start) / 60;
    }

    /**
     * Round time to nearest 15 minutes.
     */
    public static function roundToNearestQuarter($datetime): \Carbon\Carbon
    {
        $time = \Carbon\Carbon::parse($datetime);
        $minutes = $time->minute;

        if ($minutes <= 7) {
            $roundedMinutes = 0;
        } elseif ($minutes <= 22) {
            $roundedMinutes = 15;
        } elseif ($minutes <= 37) {
            $roundedMinutes = 30;
        } elseif ($minutes <= 52) {
            $roundedMinutes = 45;
        } else {
            $roundedMinutes = 0;
            $time->addHour();
        }

        return $time->setMinute($roundedMinutes)->setSecond(0);
    }

    /**
     * Get booking slots for a given date (15-minute intervals).
     */
    public static function getBookingSlots(\Carbon\Carbon $date): array
    {
        $slots = [];
        $startHour = 6; // 6 AM
        $endHour = 23; // 11 PM

        for ($hour = $startHour; $hour <= $endHour; $hour++) {
            for ($minute = 0; $minute < 60; $minute += 15) {
                $time = $date->copy()->setTime($hour, $minute, 0);

                $slots[] = [
                    'time' => $time->toTimeString(),
                    'display' => $time->format('h:i A'),
                    'datetime' => $time->toISOString(),
                    'available' => true, // This will be determined by availability check
                ];
            }
        }

        return $slots;
    }

    /**
     * Check if a time slot is valid for booking.
     */
    public static function isValidBookingTime(\Carbon\Carbon $datetime): bool
    {
        $now = self::nowInBangladesh();

        // Must be at least 30 minutes in the future
        if ($datetime->lt($now->addMinutes(30))) {
            return false;
        }

        // Must be within business hours
        if (!self::isBusinessHours($datetime)) {
            return false;
        }

        // Must be within next 30 days
        if ($datetime->gt($now->addDays(30))) {
            return false;
        }

        return true;
    }

    /**
     * Format duration in human readable format.
     */
    public static function formatDuration(float $hours): string
    {
        if ($hours < 1) {
            return round($hours * 60) . ' minutes';
        }

        $wholeHours = floor($hours);
        $minutes = round(($hours - $wholeHours) * 60);

        if ($minutes === 0) {
            return $wholeHours . ' hour' . ($wholeHours > 1 ? 's' : '');
        }

        return $wholeHours . ' hour' . ($wholeHours > 1 ? 's' : '') . ' ' . $minutes . ' minute' . ($minutes > 1 ? 's' : '');
    }
}
