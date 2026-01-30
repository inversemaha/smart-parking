<?php

namespace App\Shared\Services;

use App\Domains\Parking\Models\ParkingLocation;
use App\Domains\Parking\Models\ParkingSlot;
use App\Domains\Booking\Models\Booking;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ParkingLocationService
{
    protected $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Get all parking locations with availability
     */
    public function getAllLocationsWithAvailability(): Collection
    {
        return Cache::remember('parking_locations_with_availability', 300, function () {
            return ParkingLocation::with(['slots' => function ($query) {
                $query->where('is_active', true);
            }])
            ->where('is_active', true)
            ->get()
            ->map(function ($location) {
                $location->total_slots = $location->slots->count();
                $location->available_slots = $this->getAvailableSlotCount($location->id);
                $location->occupancy_rate = $location->total_slots > 0
                    ? round((($location->total_slots - $location->available_slots) / $location->total_slots) * 100, 2)
                    : 0;
                return $location;
            });
        });
    }

    /**
     * Search parking locations
     */
    public function searchLocations(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = ParkingLocation::with(['slots'])
            ->where('is_active', true);

        // Location filter
        if (!empty($filters['location'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['location'] . '%')
                  ->orWhere('address', 'like', '%' . $filters['location'] . '%')
                  ->orWhere('city', 'like', '%' . $filters['location'] . '%');
            });
        }

        // Date/time filter
        if (!empty($filters['start_time']) && !empty($filters['end_time'])) {
            $startTime = Carbon::parse($filters['start_time']);
            $endTime = Carbon::parse($filters['end_time']);

            $query->whereHas('slots', function ($q) use ($startTime, $endTime) {
                $q->where('is_active', true)
                  ->whereDoesntHave('bookings', function ($bookingQuery) use ($startTime, $endTime) {
                      $bookingQuery->where(function ($timeQuery) use ($startTime, $endTime) {
                          $timeQuery->whereBetween('start_time', [$startTime, $endTime])
                                   ->orWhereBetween('end_time', [$startTime, $endTime])
                                   ->orWhere(function ($overlapQuery) use ($startTime, $endTime) {
                                       $overlapQuery->where('start_time', '<=', $startTime)
                                                   ->where('end_time', '>=', $endTime);
                                   });
                      })->whereIn('status', ['confirmed', 'active']);
                  });
            });
        }

        // Price range filter
        if (!empty($filters['min_price'])) {
            $query->where('hourly_rate', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('hourly_rate', '<=', $filters['max_price']);
        }

        // Features filter
        if (!empty($filters['features'])) {
            $features = is_array($filters['features']) ? $filters['features'] : explode(',', $filters['features']);
            foreach ($features as $feature) {
                $query->whereJsonContains('features', trim($feature));
            }
        }

        // Distance filter (if coordinates provided)
        if (!empty($filters['latitude']) && !empty($filters['longitude']) && !empty($filters['radius'])) {
            $lat = $filters['latitude'];
            $lon = $filters['longitude'];
            $radius = $filters['radius']; // in km

            $query->selectRaw("
                *, (
                    6371 * acos(
                        cos(radians(?)) * cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) * sin(radians(latitude))
                    )
                ) AS distance
            ", [$lat, $lon, $lat])
            ->having('distance', '<=', $radius)
            ->orderBy('distance');
        } else {
            $query->orderBy('name');
        }

        $locations = $query->paginate($perPage);

        // Add availability data
        $locations->getCollection()->transform(function ($location) {
            $location->total_slots = $location->slots->count();
            $location->available_slots = $this->getAvailableSlotCount($location->id);
            return $location;
        });

        return $locations;
    }

    /**
     * Get parking location details with availability
     */
    public function getLocationDetails(int $locationId): ?ParkingLocation
    {
        $location = ParkingLocation::with(['slots' => function ($query) {
            $query->where('is_active', true);
        }])
        ->where('is_active', true)
        ->find($locationId);

        if (!$location) {
            return null;
        }

        // Add availability information
        $location->total_slots = $location->slots->count();
        $location->available_slots = $this->getAvailableSlotCount($location->id);
        $location->occupancy_rate = $location->total_slots > 0
            ? round((($location->total_slots - $location->available_slots) / $location->total_slots) * 100, 2)
            : 0;

        // Add hourly availability for next 24 hours
        $location->hourly_availability = $this->getHourlyAvailability($location->id);

        return $location;
    }

    /**
     * Get available slot count for a location
     */
    public function getAvailableSlotCount(int $locationId, Carbon $startTime = null, Carbon $endTime = null): int
    {
        $cacheKey = 'available_slots_' . $locationId;
        if ($startTime && $endTime) {
            $cacheKey .= '_' . $startTime->timestamp . '_' . $endTime->timestamp;
        }

        return Cache::remember($cacheKey, 60, function () use ($locationId, $startTime, $endTime) {
            $query = ParkingSlot::where('parking_location_id', $locationId)
                ->where('is_active', true);

            if ($startTime && $endTime) {
                $query->whereDoesntHave('bookings', function ($bookingQuery) use ($startTime, $endTime) {
                    $bookingQuery->where(function ($timeQuery) use ($startTime, $endTime) {
                        $timeQuery->whereBetween('start_time', [$startTime, $endTime])
                                 ->orWhereBetween('end_time', [$startTime, $endTime])
                                 ->orWhere(function ($overlapQuery) use ($startTime, $endTime) {
                                     $overlapQuery->where('start_time', '<=', $startTime)
                                                 ->where('end_time', '>=', $endTime);
                                 });
                    })->whereIn('status', ['confirmed', 'active']);
                });
            }

            return $query->count();
        });
    }

    /**
     * Get hourly availability for next 24 hours
     */
    public function getHourlyAvailability(int $locationId): array
    {
        $cacheKey = 'hourly_availability_' . $locationId . '_' . now()->format('Y-m-d-H');

        return Cache::remember($cacheKey, 900, function () use ($locationId) { // 15 minutes cache
            $availability = [];
            $startTime = now();

            for ($i = 0; $i < 24; $i++) {
                $hour = $startTime->copy()->addHours($i);
                $hourEnd = $hour->copy()->addHour();

                $availableSlots = $this->getAvailableSlotCount($locationId, $hour, $hourEnd);

                $availability[] = [
                    'hour' => $hour->format('H:i'),
                    'timestamp' => $hour->timestamp,
                    'available_slots' => $availableSlots,
                    'date' => $hour->format('Y-m-d'),
                ];
            }

            return $availability;
        });
    }

    /**
     * Get nearest parking locations
     */
    public function getNearestLocations(float $latitude, float $longitude, int $limit = 10, float $radiusKm = 10): Collection
    {
        return ParkingLocation::selectRaw("
            *, (
                6371 * acos(
                    cos(radians(?)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                )
            ) AS distance
        ", [$latitude, $longitude, $latitude])
        ->with(['slots'])
        ->where('is_active', true)
        ->having('distance', '<=', $radiusKm)
        ->orderBy('distance')
        ->limit($limit)
        ->get()
        ->map(function ($location) {
            $location->total_slots = $location->slots->count();
            $location->available_slots = $this->getAvailableSlotCount($location->id);
            return $location;
        });
    }

    /**
     * Check slot availability for specific time period
     */
    public function checkSlotAvailability(int $locationId, Carbon $startTime, Carbon $endTime): array
    {
        $availableSlots = ParkingSlot::where('parking_location_id', $locationId)
            ->where('is_active', true)
            ->whereDoesntHave('bookings', function ($query) use ($startTime, $endTime) {
                $query->where(function ($timeQuery) use ($startTime, $endTime) {
                    $timeQuery->whereBetween('start_time', [$startTime, $endTime])
                             ->orWhereBetween('end_time', [$startTime, $endTime])
                             ->orWhere(function ($overlapQuery) use ($startTime, $endTime) {
                                 $overlapQuery->where('start_time', '<=', $startTime)
                                             ->where('end_time', '>=', $endTime);
                             });
                })->whereIn('status', ['confirmed', 'active']);
            })
            ->get();

        return [
            'available_slots' => $availableSlots,
            'count' => $availableSlots->count(),
            'slot_ids' => $availableSlots->pluck('id')->toArray(),
        ];
    }

    /**
     * Get parking statistics for location
     */
    public function getLocationStats(int $locationId): array
    {
        $cacheKey = 'location_stats_' . $locationId . '_' . now()->format('Y-m-d');

        return Cache::remember($cacheKey, 3600, function () use ($locationId) {
            $location = ParkingLocation::with('slots')->find($locationId);

            if (!$location) {
                return [];
            }

            $totalSlots = $location->slots->where('is_active', true)->count();
            $occupiedSlots = Booking::whereHas('slot', function ($query) use ($locationId) {
                $query->where('parking_location_id', $locationId);
            })
            ->where('status', 'active')
            ->count();

            $todayBookings = Booking::whereHas('slot', function ($query) use ($locationId) {
                $query->where('parking_location_id', $locationId);
            })
            ->whereDate('start_time', today())
            ->count();

            $avgOccupancyToday = $totalSlots > 0 ? round(($occupiedSlots / $totalSlots) * 100, 2) : 0;

            return [
                'total_slots' => $totalSlots,
                'occupied_slots' => $occupiedSlots,
                'available_slots' => $totalSlots - $occupiedSlots,
                'occupancy_rate' => $avgOccupancyToday,
                'todays_bookings' => $todayBookings,
                'revenue_today' => Booking::whereHas('slot', function ($query) use ($locationId) {
                    $query->where('parking_location_id', $locationId);
                })
                ->whereDate('start_time', today())
                ->where('status', 'completed')
                ->sum('total_amount'),
            ];
        });
    }

    /**
     * Clear cache for location
     */
    public function clearLocationCache(int $locationId): void
    {
        $patterns = [
            'parking_locations_with_availability',
            'available_slots_' . $locationId,
            'hourly_availability_' . $locationId . '_*',
            'location_stats_' . $locationId . '_*',
        ];

        foreach ($patterns as $pattern) {
            if (str_contains($pattern, '*')) {
                // Use cache tags or manual deletion for patterns
                Cache::flush(); // Simplified for now
            } else {
                Cache::forget($pattern);
            }
        }
    }

    /**
     * Get popular locations
     */
    public function getPopularLocations(int $limit = 5): Collection
    {
        return Cache::remember('popular_locations_' . $limit, 1800, function () use ($limit) {
            return ParkingLocation::withCount(['slots as total_bookings' => function ($query) {
                $query->join('bookings', 'parking_slots.id', '=', 'bookings.slot_id')
                      ->whereDate('bookings.created_at', '>=', now()->subDays(7));
            }])
            ->where('is_active', true)
            ->orderBy('total_bookings', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($location) {
                $location->available_slots = $this->getAvailableSlotCount($location->id);
                return $location;
            });
        });
    }

    /**
     * Get total number of active locations
     */
    public function getActiveLocationCount(): int
    {
        return Cache::remember('active_location_count', 3600, function () {
            return ParkingLocation::where('is_active', true)->count();
        });
    }

    /**
     * Get total number of slots across all locations
     */
    public function getTotalSlotCount(): int
    {
        return Cache::remember('total_slot_count', 3600, function () {
            return ParkingLocation::where('is_active', true)
                ->withCount('slots')
                ->get()
                ->sum('slots_count');
        });
    }

    /**
     * Get total available slots across all locations
     */
    public function getTotalAvailableSlotCount(): int
    {
        return Cache::remember('global_available_slot_count', 300, function () {
            $locations = ParkingLocation::where('is_active', true)->get();
            $totalAvailable = 0;

            foreach ($locations as $location) {
                $totalAvailable += $this->getAvailableSlotCount($location->id);
            }

            return $totalAvailable;
        });
    }

    /**
     * Get featured locations for homepage
     */
    public function getFeaturedLocations(int $limit = 6): Collection
    {
        return Cache::remember('featured_locations_' . $limit, 1800, function () use ($limit) {
            return ParkingLocation::with(['slots'])
                ->where('is_active', true)
                ->withCount(['slots as recent_bookings' => function ($query) {
                    $query->join('bookings', 'parking_slots.id', '=', 'bookings.slot_id')
                          ->whereDate('bookings.created_at', '>=', now()->subDays(30));
                }])
                ->orderBy('recent_bookings', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($location) {
                    $location->total_slots = $location->slots->count();
                    $location->available_slots = $this->getAvailableSlotCount($location->id);
                    return $location;
                });
        });
    }
}
