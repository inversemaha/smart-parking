<?php

namespace App\Domains\Parking\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Parking\Services\ParkingLocationService;
use App\Domains\Parking\Models\ParkingLocation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class VisitorParkingController extends Controller
{
    public function __construct(
        protected ParkingLocationService $parkingLocationService
    ) {
        // No authentication required for parking discovery
        $this->middleware('throttle:api')->only(['checkAvailability', 'apiCheckAvailability']);
    }

    /**
     * Show all parking locations
     */
    public function locations(Request $request): View
    {
        $locations = $this->parkingLocationService->getAllActiveLocations([
            'search' => $request->search,
            'city' => $request->city,
            'area' => $request->area,
            'sort' => $request->sort ?? 'name',
            'per_page' => 12
        ]);

        $cities = $this->parkingLocationService->getAvailableCities();
        $areas = $request->city ? $this->parkingLocationService->getAreasByCity($request->city) : [];

        return view('visitor.parking.locations', compact('locations', 'cities', 'areas'));
    }

    /**
     * Show location details
     */
    public function locationDetails(ParkingLocation $location): View
    {
        $location->load(['slots.slotType', 'amenities']);

        $slotTypes = $location->getAvailableSlotTypes();
        $nearbyLocations = $this->parkingLocationService->getNearbyLocations($location, 3);

        return view('visitor.parking.location-details', compact('location', 'slotTypes', 'nearbyLocations'));
    }

    /**
     * Check slot availability for a location
     */
    public function availability(Request $request, ParkingLocation $location): View
    {
        $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'vehicle_type' => ['required', 'in:car,motorcycle,cng,bus,truck'],
        ]);

        $startDateTime = Carbon::parse($request->date . ' ' . $request->start_time);
        $endDateTime = Carbon::parse($request->date . ' ' . $request->end_time);

        $availability = $this->parkingLocationService->getSlotAvailability(
            $location->id,
            $startDateTime,
            $endDateTime,
            $request->vehicle_type
        );

        return view('visitor.parking.availability', compact(
            'location',
            'availability',
            'startDateTime',
            'endDateTime'
        ));
    }

    /**
     * AJAX: Check availability
     */
    public function checkAvailability(Request $request): JsonResponse
    {
        $request->validate([
            'location_id' => ['required', 'exists:parking_locations,id'],
            'start_datetime' => ['required', 'date', 'after_or_equal:now'],
            'end_datetime' => ['required', 'date', 'after:start_datetime'],
            'vehicle_type' => ['required', 'in:car,motorcycle,cng,bus,truck'],
        ]);

        $startDateTime = Carbon::parse($request->start_datetime);
        $endDateTime = Carbon::parse($request->end_datetime);

        $availability = $this->parkingLocationService->getSlotAvailability(
            $request->location_id,
            $startDateTime,
            $endDateTime,
            $request->vehicle_type
        );

        $estimatedCost = $this->parkingLocationService->calculateEstimatedCost(
            $request->location_id,
            $startDateTime,
            $endDateTime,
            $request->vehicle_type
        );

        return response()->json([
            'success' => true,
            'data' => [
                'availability' => $availability,
                'estimated_cost' => $estimatedCost,
                'location' => ParkingLocation::find($request->location_id)
            ]
        ]);
    }

    // API Methods for Mobile App

    /**
     * API: Get all parking locations
     */
    public function apiLocations(Request $request): JsonResponse
    {
        $locations = $this->parkingLocationService->getAllActiveLocations([
            'search' => $request->search,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius ?? 10, // 10km radius
            'city' => $request->city,
            'area' => $request->area,
            'sort' => $request->sort ?? 'distance',
            'per_page' => $request->per_page ?? 20
        ]);

        return response()->json([
            'success' => true,
            'data' => $locations
        ]);
    }

    /**
     * API: Get location details
     */
    public function apiLocationDetails(ParkingLocation $location): JsonResponse
    {
        $location->load([
            'slots.slotType',
            'amenities',
            'operatingHours',
            'pricingRules'
        ]);

        $slotTypes = $location->getAvailableSlotTypes();
        $currentAvailability = $this->parkingLocationService->getCurrentAvailability($location->id);

        return response()->json([
            'success' => true,
            'data' => [
                'location' => $location,
                'slot_types' => $slotTypes,
                'current_availability' => $currentAvailability,
                'operating_hours' => $location->operatingHours,
                'pricing_rules' => $location->pricingRules,
            ]
        ]);
    }

    /**
     * API: Check availability
     */
    public function apiCheckAvailability(Request $request): JsonResponse
    {
        $request->validate([
            'location_id' => ['required', 'exists:parking_locations,id'],
            'start_datetime' => ['required', 'date', 'after_or_equal:now'],
            'end_datetime' => ['required', 'date', 'after:start_datetime'],
            'vehicle_type' => ['required', 'in:car,motorcycle,cng,bus,truck'],
        ]);

        $startDateTime = Carbon::parse($request->start_datetime);
        $endDateTime = Carbon::parse($request->end_datetime);

        $availability = $this->parkingLocationService->getSlotAvailability(
            $request->location_id,
            $startDateTime,
            $endDateTime,
            $request->vehicle_type
        );

        $estimatedCost = $this->parkingLocationService->calculateEstimatedCost(
            $request->location_id,
            $startDateTime,
            $endDateTime,
            $request->vehicle_type
        );

        $location = ParkingLocation::with(['operatingHours', 'pricingRules'])
                                  ->find($request->location_id);

        // Check if location is open during requested time
        $isLocationOpen = $this->parkingLocationService->isLocationOpenDuringTime(
            $location,
            $startDateTime,
            $endDateTime
        );

        return response()->json([
            'success' => true,
            'data' => [
                'location' => $location,
                'availability' => $availability,
                'estimated_cost' => $estimatedCost,
                'is_location_open' => $isLocationOpen,
                'booking_deadline' => $this->parkingLocationService->getBookingDeadline($location, $startDateTime),
                'cancellation_policy' => $location->cancellation_policy,
            ]
        ]);
    }

    /**
     * API: Search locations by proximity
     */
    public function apiSearchNearby(Request $request): JsonResponse
    {
        $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius' => ['integer', 'min:1', 'max:50'],
            'vehicle_type' => ['nullable', 'in:car,motorcycle,cng,bus,truck'],
            'start_datetime' => ['nullable', 'date', 'after_or_equal:now'],
            'end_datetime' => ['nullable', 'date', 'after:start_datetime'],
        ]);

        $nearbyLocations = $this->parkingLocationService->searchNearbyLocations(
            $request->latitude,
            $request->longitude,
            $request->radius ?? 5,
            [
                'vehicle_type' => $request->vehicle_type,
                'start_datetime' => $request->start_datetime,
                'end_datetime' => $request->end_datetime,
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $nearbyLocations
        ]);
    }

    /**
     * API: Get popular locations
     */
    public function apiPopularLocations(Request $request): JsonResponse
    {
        $popularLocations = $this->parkingLocationService->getPopularLocations(
            $request->limit ?? 10,
            $request->city
        );

        return response()->json([
            'success' => true,
            'data' => $popularLocations
        ]);
    }

    /**
     * API: Get location pricing
     */
    public function apiLocationPricing(ParkingLocation $location): JsonResponse
    {
        $pricingRules = $this->parkingLocationService->getLocationPricing($location->id);

        return response()->json([
            'success' => true,
            'data' => [
                'location' => $location,
                'pricing_rules' => $pricingRules,
                'sample_calculations' => $this->getSamplePricingCalculations($location)
            ]
        ]);
    }

    /**
     * Get sample pricing calculations for different durations
     */
    protected function getSamplePricingCalculations(ParkingLocation $location): array
    {
        $sampleCalculations = [];
        $durations = [1, 2, 4, 8, 12, 24]; // hours

        foreach ($durations as $hours) {
            $startTime = now();
            $endTime = $startTime->copy()->addHours($hours);

            foreach (['car', 'motorcycle'] as $vehicleType) {
                $cost = $this->parkingLocationService->calculateEstimatedCost(
                    $location->id,
                    $startTime,
                    $endTime,
                    $vehicleType
                );

                $sampleCalculations[] = [
                    'duration_hours' => $hours,
                    'vehicle_type' => $vehicleType,
                    'estimated_cost' => $cost
                ];
            }
        }

        return $sampleCalculations;
    }
}
