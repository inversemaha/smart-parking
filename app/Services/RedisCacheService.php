<?php

namespace App\Services;

use App\Models\ParkingSlot;
use App\Models\ParkingLocation;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

/**
 * Redis Cache Service
 *
 * Manages caching for real-time parking data, user sessions,
 * and frequently accessed system data.
 */
class RedisCacheService
{
    private const SLOT_AVAILABILITY_PREFIX = 'parking:location:';
    private const USER_SESSION_PREFIX = 'user:session:';
    private const DASHBOARD_STATS_KEY = 'dashboard:stats';
    private const SYSTEM_SETTINGS_PREFIX = 'settings:';

    /**
     * Cache slot availability for a parking location.
     */
    public function cacheSlotAvailability(int $locationId): void
    {
        try {
            $location = ParkingLocation::with(['parkingSlots'])->find($locationId);

            if (!$location) {
                return;
            }

            $availableSlots = $location->parkingSlots()
                ->where('status', 'available')
                ->pluck('id')
                ->toArray();

            $occupiedSlots = $location->parkingSlots()
                ->where('status', 'occupied')
                ->pluck('id')
                ->toArray();

            $cacheData = [
                'total_slots' => $location->parkingSlots->count(),
                'available_slots' => $availableSlots,
                'occupied_slots' => $occupiedSlots,
                'available_count' => count($availableSlots),
                'occupied_count' => count($occupiedSlots),
                'last_updated' => now()->toISOString(),
            ];

            $cacheKey = self::SLOT_AVAILABILITY_PREFIX . $locationId;
            $ttl = SystemSetting::get('cache_slot_availability', 60); // Default 60 seconds

            Redis::setex($cacheKey, $ttl, json_encode($cacheData));

            Log::debug('Cached slot availability', [
                'location_id' => $locationId,
                'data' => $cacheData
            ]);

        } catch (\Exception $e) {
            Log::error('Error caching slot availability: ' . $e->getMessage(), [
                'location_id' => $locationId
            ]);
        }
    }

    /**
     * Get cached slot availability for a location.
     */
    public function getSlotAvailability(int $locationId): ?array
    {
        try {
            $cacheKey = self::SLOT_AVAILABILITY_PREFIX . $locationId;
            $data = Redis::get($cacheKey);

            return $data ? json_decode($data, true) : null;

        } catch (\Exception $e) {
            Log::error('Error getting cached slot availability: ' . $e->getMessage(), [
                'location_id' => $locationId
            ]);
            return null;
        }
    }

    /**
     * Cache all parking locations availability.
     */
    public function cacheAllSlotAvailability(): void
    {
        $locations = ParkingLocation::all();

        foreach ($locations as $location) {
            $this->cacheSlotAvailability($location->id);
        }
    }

    /**
     * Invalidate slot availability cache for a location.
     */
    public function invalidateSlotAvailability(int $locationId): void
    {
        try {
            $cacheKey = self::SLOT_AVAILABILITY_PREFIX . $locationId;
            Redis::del($cacheKey);

            Log::debug('Invalidated slot availability cache', [
                'location_id' => $locationId
            ]);

        } catch (\Exception $e) {
            Log::error('Error invalidating slot availability cache: ' . $e->getMessage(), [
                'location_id' => $locationId
            ]);
        }
    }

    /**
     * Cache user session data.
     */
    public function cacheUserSession(int $userId, array $sessionData): void
    {
        try {
            $cacheKey = self::USER_SESSION_PREFIX . $userId;
            $ttl = SystemSetting::get('session_timeout', 7200); // Default 2 hours

            Redis::setex($cacheKey, $ttl, json_encode($sessionData));

        } catch (\Exception $e) {
            Log::error('Error caching user session: ' . $e->getMessage(), [
                'user_id' => $userId
            ]);
        }
    }

    /**
     * Get cached user session data.
     */
    public function getUserSession(int $userId): ?array
    {
        try {
            $cacheKey = self::USER_SESSION_PREFIX . $userId;
            $data = Redis::get($cacheKey);

            return $data ? json_decode($data, true) : null;

        } catch (\Exception $e) {
            Log::error('Error getting cached user session: ' . $e->getMessage(), [
                'user_id' => $userId
            ]);
            return null;
        }
    }

    /**
     * Remove user session from cache.
     */
    public function removeUserSession(int $userId): void
    {
        try {
            $cacheKey = self::USER_SESSION_PREFIX . $userId;
            Redis::del($cacheKey);

        } catch (\Exception $e) {
            Log::error('Error removing user session cache: ' . $e->getMessage(), [
                'user_id' => $userId
            ]);
        }
    }

    /**
     * Cache dashboard statistics.
     */
    public function cacheDashboardStats(array $stats): void
    {
        try {
            $ttl = SystemSetting::get('cache_dashboard_stats', 300); // Default 5 minutes
            Redis::setex(self::DASHBOARD_STATS_KEY, $ttl, json_encode($stats));

        } catch (\Exception $e) {
            Log::error('Error caching dashboard stats: ' . $e->getMessage());
        }
    }

    /**
     * Get cached dashboard statistics.
     */
    public function getDashboardStats(): ?array
    {
        try {
            $data = Redis::get(self::DASHBOARD_STATS_KEY);
            return $data ? json_decode($data, true) : null;

        } catch (\Exception $e) {
            Log::error('Error getting cached dashboard stats: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Cache system setting.
     */
    public function cacheSetting(string $key, $value): void
    {
        try {
            $cacheKey = self::SYSTEM_SETTINGS_PREFIX . $key;
            Redis::setex($cacheKey, 3600, json_encode($value)); // 1 hour TTL

        } catch (\Exception $e) {
            Log::error('Error caching system setting: ' . $e->getMessage(), [
                'key' => $key
            ]);
        }
    }

    /**
     * Get cached system setting.
     */
    public function getSetting(string $key)
    {
        try {
            $cacheKey = self::SYSTEM_SETTINGS_PREFIX . $key;
            $data = Redis::get($cacheKey);

            return $data ? json_decode($data, true) : null;

        } catch (\Exception $e) {
            Log::error('Error getting cached system setting: ' . $e->getMessage(), [
                'key' => $key
            ]);
            return null;
        }
    }

    /**
     * Invalidate system setting cache.
     */
    public function invalidateSetting(string $key): void
    {
        try {
            $cacheKey = self::SYSTEM_SETTINGS_PREFIX . $key;
            Redis::del($cacheKey);

        } catch (\Exception $e) {
            Log::error('Error invalidating system setting cache: ' . $e->getMessage(), [
                'key' => $key
            ]);
        }
    }

    /**
     * Get real-time parking summary for all locations.
     */
    public function getParkingSummary(): array
    {
        try {
            $locations = ParkingLocation::all();
            $summary = [];

            foreach ($locations as $location) {
                $availability = $this->getSlotAvailability($location->id);

                if (!$availability) {
                    // Cache miss - generate fresh data
                    $this->cacheSlotAvailability($location->id);
                    $availability = $this->getSlotAvailability($location->id);
                }

                $summary[] = [
                    'location_id' => $location->id,
                    'location_name' => $location->name,
                    'total_slots' => $availability['total_slots'] ?? 0,
                    'available_count' => $availability['available_count'] ?? 0,
                    'occupied_count' => $availability['occupied_count'] ?? 0,
                    'occupancy_rate' => $availability['total_slots'] > 0
                        ? round(($availability['occupied_count'] / $availability['total_slots']) * 100, 2)
                        : 0,
                    'last_updated' => $availability['last_updated'] ?? null,
                ];
            }

            return $summary;

        } catch (\Exception $e) {
            Log::error('Error getting parking summary: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Clear all cache data.
     */
    public function clearAllCache(): void
    {
        try {
            // Clear slot availability cache
            $locations = ParkingLocation::pluck('id');
            foreach ($locations as $locationId) {
                $this->invalidateSlotAvailability($locationId);
            }

            // Clear dashboard stats
            Redis::del(self::DASHBOARD_STATS_KEY);

            // Clear system settings cache
            $keys = Redis::keys(self::SYSTEM_SETTINGS_PREFIX . '*');
            if (!empty($keys)) {
                Redis::del($keys);
            }

            Log::info('All cache cleared');

        } catch (\Exception $e) {
            Log::error('Error clearing all cache: ' . $e->getMessage());
        }
    }

    /**
     * Get cache health status.
     */
    public function getCacheHealth(): array
    {
        try {
            $info = Redis::info();

            return [
                'connected' => true,
                'memory_usage' => $info['used_memory_human'] ?? 'Unknown',
                'connected_clients' => $info['connected_clients'] ?? 'Unknown',
                'keyspace_hits' => $info['keyspace_hits'] ?? 0,
                'keyspace_misses' => $info['keyspace_misses'] ?? 0,
                'hit_rate' => $this->calculateHitRate($info),
            ];

        } catch (\Exception $e) {
            return [
                'connected' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Calculate cache hit rate.
     */
    private function calculateHitRate(array $info): float
    {
        $hits = $info['keyspace_hits'] ?? 0;
        $misses = $info['keyspace_misses'] ?? 0;
        $total = $hits + $misses;

        return $total > 0 ? round(($hits / $total) * 100, 2) : 0;
    }
}
