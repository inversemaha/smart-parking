<?php

namespace App\Domains\Parking\Services;

use App\Domains\Parking\Models\ParkingLocation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ParkingLocationService extends \App\Shared\Services\ParkingLocationService
{
	public function getAllActiveLocations(array $filters = []): LengthAwarePaginator
	{
		return $this->searchLocations([
			'location' => $filters['search'] ?? null,
			'latitude' => $filters['latitude'] ?? null,
			'longitude' => $filters['longitude'] ?? null,
			'radius' => $filters['radius'] ?? null,
			'start_time' => $filters['start_datetime'] ?? null,
			'end_time' => $filters['end_datetime'] ?? null,
		], (int) ($filters['per_page'] ?? 15));
	}

	public function getAvailableCities(): array
	{
		return [];
	}

	public function getAreasByCity(string $city): array
	{
		return [];
	}

	public function getNearbyLocations(ParkingLocation $location, int $limit = 3): Collection
	{
		if ($location->latitude === null || $location->longitude === null) {
			return collect();
		}

		return $this->getNearestLocations((float) $location->latitude, (float) $location->longitude, $limit + 1)
			->reject(fn ($item) => $item->id === $location->id)
			->take($limit)
			->values();
	}

	public function getSlotAvailability(int $locationId, Carbon $startDateTime, Carbon $endDateTime, string $vehicleType): array
	{
		$result = $this->checkSlotAvailability($locationId, $startDateTime, $endDateTime);

		return [
			'count' => $result['count'],
			'slot_ids' => $result['slot_ids'],
		];
	}

	public function calculateEstimatedCost(int $locationId, Carbon $startDateTime, Carbon $endDateTime, string $vehicleType): float
	{
		$location = ParkingLocation::find($locationId);
		$hours = max(1, $startDateTime->diffInHours($endDateTime));

		return (float) ($hours * (($location?->hourly_rate) ?? 0));
	}

	public function getCurrentAvailability(int $locationId): array
	{
		return [
			'available_slots' => $this->getAvailableSlotCount($locationId),
		];
	}

	public function isLocationOpenDuringTime(ParkingLocation $location, Carbon $startDateTime, Carbon $endDateTime): bool
	{
		return $location->isOpenAt($startDateTime) && $location->isOpenAt($endDateTime);
	}

	public function getBookingDeadline(ParkingLocation $location, Carbon $startDateTime): string
	{
		return $startDateTime->copy()->subMinutes(30)->toDateTimeString();
	}

	public function searchNearbyLocations(float $latitude, float $longitude, float $radius = 5, array $filters = []): Collection
	{
		return $this->getNearestLocations($latitude, $longitude, 20, $radius);
	}

	public function getPopularLocations(int $limit = 10, ?string $city = null): Collection
	{
		return parent::getPopularLocations($limit);
	}

	public function getLocationPricing(int $locationId): array
	{
		$location = ParkingLocation::find($locationId);

		return [
			'hourly_rate' => (float) ($location?->hourly_rate ?? 0),
		];
	}
}
