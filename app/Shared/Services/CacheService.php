<?php

namespace App\Shared\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class CacheService
{
    /**
     * Cache keys for different data types.
     */
    const CACHE_KEYS = [
        'parking_availability' => 'parking:availability:',
        'user_session' => 'user:session:',
        'brta_data' => 'brta:vehicle:',
        'payment_status' => 'payment:status:',
        'booking_timeline' => 'booking:timeline:',
        'api_rate_limit' => 'api:rate_limit:',
        'system_stats' => 'system:stats',
        'vehicle_verification' => 'vehicle:verification:',
    ];

    /**
     * Cache TTLs from config.
     */
    protected function getTTL(string $type): int
    {
        return config("parking.cache.{$type}_ttl", 3600); // Default 1 hour
    }

    /**
     * Cache parking area availability.
     */
    public function cacheParkingAvailability(int $parkingAreaId, array $data): bool
    {
        $key = self::CACHE_KEYS['parking_availability'] . $parkingAreaId;
        $ttl = $this->getTTL('parking_availability');

        return Cache::put($key, $data, $ttl);
    }

    /**
     * Get cached parking availability.
     */
    public function getParkingAvailability(int $parkingAreaId): ?array
    {
        $key = self::CACHE_KEYS['parking_availability'] . $parkingAreaId;
        return Cache::get($key);
    }

    /**
     * Cache user session data.
     */
    public function cacheUserSession(int $userId, array $sessionData): bool
    {
        $key = self::CACHE_KEYS['user_session'] . $userId;
        $ttl = $this->getTTL('user_session');

        return Cache::put($key, $sessionData, $ttl);
    }

    /**
     * Get cached user session.
     */
    public function getUserSession(int $userId): ?array
    {
        $key = self::CACHE_KEYS['user_session'] . $userId;
        return Cache::get($key);
    }

    /**
     * Cache BRTA vehicle data.
     */
    public function cacheBrtaData(string $registrationNumber, array $data): bool
    {
        $key = self::CACHE_KEYS['brta_data'] . strtoupper($registrationNumber);
        $ttl = $this->getTTL('brta_data');

        return Cache::put($key, $data, $ttl);
    }

    /**
     * Get cached BRTA data.
     */
    public function getBrtaData(string $registrationNumber): ?array
    {
        $key = self::CACHE_KEYS['brta_data'] . strtoupper($registrationNumber);
        return Cache::get($key);
    }

    /**
     * Cache payment status.
     */
    public function cachePaymentStatus(string $transactionId, array $status): bool
    {
        $key = self::CACHE_KEYS['payment_status'] . $transactionId;
        $ttl = $this->getTTL('payment_status');

        return Cache::put($key, $status, $ttl);
    }

    /**
     * Get cached payment status.
     */
    public function getPaymentStatus(string $transactionId): ?array
    {
        $key = self::CACHE_KEYS['payment_status'] . $transactionId;
        return Cache::get($key);
    }

    /**
     * Cache booking timeline.
     */
    public function cacheBookingTimeline(int $bookingId, array $timeline): bool
    {
        $key = self::CACHE_KEYS['booking_timeline'] . $bookingId;
        $ttl = 3600; // 1 hour

        return Cache::put($key, $timeline, $ttl);
    }

    /**
     * Get cached booking timeline.
     */
    public function getBookingTimeline(int $bookingId): ?array
    {
        $key = self::CACHE_KEYS['booking_timeline'] . $bookingId;
        return Cache::get($key);
    }

    /**
     * Cache system statistics.
     */
    public function cacheSystemStats(array $stats): bool
    {
        $key = self::CACHE_KEYS['system_stats'];
        $ttl = 300; // 5 minutes

        return Cache::put($key, $stats, $ttl);
    }

    /**
     * Get cached system stats.
     */
    public function getSystemStats(): ?array
    {
        $key = self::CACHE_KEYS['system_stats'];
        return Cache::get($key);
    }

    /**
     * Cache vehicle verification status.
     */
    public function cacheVehicleVerification(int $vehicleId, array $verification): bool
    {
        $key = self::CACHE_KEYS['vehicle_verification'] . $vehicleId;
        $ttl = 1800; // 30 minutes

        return Cache::put($key, $verification, $ttl);
    }

    /**
     * Get cached vehicle verification.
     */
    public function getVehicleVerification(int $vehicleId): ?array
    {
        $key = self::CACHE_KEYS['vehicle_verification'] . $vehicleId;
        return Cache::get($key);
    }

    /**
     * Invalidate parking availability cache.
     */
    public function invalidateParkingAvailability(int $parkingAreaId): bool
    {
        $key = self::CACHE_KEYS['parking_availability'] . $parkingAreaId;
        return Cache::forget($key);
    }

    /**
     * Invalidate user session cache.
     */
    public function invalidateUserSession(int $userId): bool
    {
        $key = self::CACHE_KEYS['user_session'] . $userId;
        return Cache::forget($key);
    }

    /**
     * Invalidate all cache for a specific pattern.
     */
    public function invalidatePattern(string $pattern): int
    {
        $keys = Redis::keys($pattern);
        $deleted = 0;

        foreach ($keys as $key) {
            if (Cache::forget($key)) {
                $deleted++;
            }
        }

        return $deleted;
    }

    /**
     * Get cache statistics.
     */
    public function getCacheStats(): array
    {
        try {
            $info = Redis::info('memory');

            return [
                'used_memory' => $info['used_memory'] ?? 0,
                'used_memory_human' => $info['used_memory_human'] ?? '0B',
                'used_memory_peak' => $info['used_memory_peak'] ?? 0,
                'used_memory_peak_human' => $info['used_memory_peak_human'] ?? '0B',
                'total_connections_received' => $info['total_connections_received'] ?? 0,
                'connected_clients' => $info['connected_clients'] ?? 0,
                'total_commands_processed' => $info['total_commands_processed'] ?? 0,
            ];
        } catch (\Exception $e) {
            return [
                'error' => 'Unable to retrieve cache statistics',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Warm up commonly used cache data.
     */
    public function warmupCache(): array
    {
        $results = [];

        try {
            // Cache system stats
            $stats = $this->generateSystemStats();
            $this->cacheSystemStats($stats);
            $results['system_stats'] = 'success';

            // Cache parking availability for all areas
            $parkingAreas = \App\Models\ParkingArea::active()->get();
            foreach ($parkingAreas as $area) {
                $availability = $this->generateParkingAvailability($area->id);
                $this->cacheParkingAvailability($area->id, $availability);
            }
            $results['parking_availability'] = count($parkingAreas) . ' areas cached';

            // Cache recent BRTA verifications
            $recentVehicles = \App\Models\Vehicle::where('verified_at', '>', now()->subDays(1))
                ->limit(100)
                ->get();

            foreach ($recentVehicles as $vehicle) {
                if ($vehicle->brta_data) {
                    $this->cacheBrtaData($vehicle->registration_number, $vehicle->brta_data);
                }
            }
            $results['brta_data'] = count($recentVehicles) . ' vehicles cached';

        } catch (\Exception $e) {
            $results['error'] = $e->getMessage();
        }

        return $results;
    }

    /**
     * Clear all application cache.
     */
    public function clearAllCache(): array
    {
        $results = [];

        try {
            // Clear application cache
            \Artisan::call('cache:clear');
            $results['application_cache'] = 'cleared';

            // Clear config cache
            \Artisan::call('config:clear');
            $results['config_cache'] = 'cleared';

            // Clear route cache
            \Artisan::call('route:clear');
            $results['route_cache'] = 'cleared';

            // Clear view cache
            \Artisan::call('view:clear');
            $results['view_cache'] = 'cleared';

            // Clear specific application caches
            foreach (self::CACHE_KEYS as $type => $prefix) {
                $deleted = $this->invalidatePattern($prefix . '*');
                $results[$type] = $deleted . ' keys deleted';
            }

        } catch (\Exception $e) {
            $results['error'] = $e->getMessage();
        }

        return $results;
    }

    /**
     * Generate system stats for caching.
     */
    protected function generateSystemStats(): array
    {
        return [
            'total_users' => \App\Models\User::count(),
            'active_bookings' => \App\Models\Booking::where('status', 'active')->count(),
            'total_vehicles' => \App\Models\Vehicle::count(),
            'verified_vehicles' => \App\Models\Vehicle::where('verification_status', 'verified')->count(),
            'total_payments' => \App\Models\Payment::where('status', 'completed')->count(),
            'total_revenue' => \App\Models\Payment::where('status', 'completed')->sum('amount'),
            'available_slots' => \App\Models\ParkingSlot::where('status', 'available')->count(),
            'occupied_slots' => \App\Models\ParkingSlot::where('status', 'occupied')->count(),
            'cached_at' => now()->toISOString(),
        ];
    }

    /**
     * Generate parking availability data.
     */
    protected function generateParkingAvailability(int $parkingAreaId): array
    {
        $area = \App\Models\ParkingArea::find($parkingAreaId);

        if (!$area) {
            return [];
        }

        $totalSlots = $area->parking_slots_count;
        $availableSlots = $area->parking_slots()->where('status', 'available')->count();
        $occupiedSlots = $area->parking_slots()->where('status', 'occupied')->count();
        $maintenanceSlots = $area->parking_slots()->where('status', 'maintenance')->count();

        return [
            'area_id' => $parkingAreaId,
            'total_slots' => $totalSlots,
            'available_slots' => $availableSlots,
            'occupied_slots' => $occupiedSlots,
            'maintenance_slots' => $maintenanceSlots,
            'occupancy_rate' => $totalSlots > 0 ? round(($occupiedSlots / $totalSlots) * 100, 2) : 0,
            'availability_rate' => $totalSlots > 0 ? round(($availableSlots / $totalSlots) * 100, 2) : 0,
            'cached_at' => now()->toISOString(),
        ];
    }
}
