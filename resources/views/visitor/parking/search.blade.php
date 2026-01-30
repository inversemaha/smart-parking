@extends('layouts.visitor')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Search Hero Section -->
    <div class="bg-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl font-bold">{{ __('Find Parking Near You') }}</h1>
                <p class="mt-2 text-xl text-blue-100">{{ __('Search and book parking spots in advance') }}</p>
            </div>

            <!-- Search Form -->
            <div class="max-w-4xl mx-auto">
                <form action="{{ route('visitor.parking.search') }}" method="GET" class="bg-white rounded-lg shadow-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Location') }}
                            </label>
                            <input type="text" name="location" id="location"
                                   value="{{ request('location') }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="{{ __('Enter location or address') }}">
                        </div>

                        <!-- Start Date/Time -->
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Start Time') }}
                            </label>
                            <input type="datetime-local" name="start_time" id="start_time"
                                   value="{{ request('start_time', now()->format('Y-m-d\TH:i')) }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- End Date/Time -->
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('End Time') }}
                            </label>
                            <input type="datetime-local" name="end_time" id="end_time"
                                   value="{{ request('end_time', now()->addHours(2)->format('Y-m-d\TH:i')) }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Search Button -->
                        <div class="flex items-end">
                            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                {{ __('Search') }}
                            </button>
                        </div>
                    </div>

                    <!-- Advanced Filters Toggle -->
                    <div class="mt-4">
                        <button type="button" onclick="toggleFilters()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            {{ __('Advanced Filters') }}
                            <svg id="filter-arrow" class="inline w-4 h-4 ml-1 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Advanced Filters -->
                    <div id="advanced-filters" class="mt-4 pt-4 border-t border-gray-200 hidden">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Price Range -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Price Range (per hour)') }}
                                </label>
                                <div class="flex space-x-2">
                                    <input type="number" name="min_price" placeholder="{{ __('Min') }}"
                                           value="{{ request('min_price') }}"
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <input type="number" name="max_price" placeholder="{{ __('Max') }}"
                                           value="{{ request('max_price') }}"
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <!-- Features -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Features') }}
                                </label>
                                <div class="space-y-2">
                                    @php
                                        $features = ['Covered', 'Security Camera', '24/7 Access', 'EV Charging', 'Disabled Access'];
                                        $selectedFeatures = request('features', []);
                                    @endphp
                                    @foreach($features as $feature)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="features[]" value="{{ $feature }}"
                                                   {{ in_array($feature, $selectedFeatures) ? 'checked' : '' }}
                                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700">{{ __($feature) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Distance -->
                            <div>
                                <label for="radius" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Distance (km)') }}
                                </label>
                                <select name="radius" id="radius" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">{{ __('Any distance') }}</option>
                                    <option value="1" {{ request('radius') == '1' ? 'selected' : '' }}>{{ __('Within 1 km') }}</option>
                                    <option value="5" {{ request('radius') == '5' ? 'selected' : '' }}>{{ __('Within 5 km') }}</option>
                                    <option value="10" {{ request('radius') == '10' ? 'selected' : '' }}>{{ __('Within 10 km') }}</option>
                                    <option value="25" {{ request('radius') == '25' ? 'selected' : '' }}>{{ __('Within 25 km') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Results Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(isset($locations))
            <!-- Results Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('Available Parking Locations') }}</h2>
                    <p class="text-gray-600">{{ __(':count locations found', ['count' => $locations->total()]) }}</p>
                </div>

                <!-- Sort Options -->
                <div class="mt-4 sm:mt-0">
                    <select name="sort" onchange="window.location.href='{{ request()->url() }}?' + new URLSearchParams({...Object.fromEntries(new URLSearchParams(window.location.search)), sort: this.value})"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="distance" {{ request('sort') == 'distance' ? 'selected' : '' }}>{{ __('Sort by Distance') }}</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>{{ __('Price: Low to High') }}</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>{{ __('Price: High to Low') }}</option>
                        <option value="availability" {{ request('sort') == 'availability' ? 'selected' : '' }}>{{ __('Most Available') }}</option>
                    </select>
                </div>
            </div>

            <!-- Location Cards -->
            @if($locations->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    @foreach($locations as $location)
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                            <!-- Location Image -->
                            @if($location->image)
                                <img src="{{ asset('storage/' . $location->image) }}" alt="{{ $location->name }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                            @endif

                            <div class="p-6">
                                <!-- Location Header -->
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-semibold text-gray-900">{{ $location->name }}</h3>
                                        <p class="text-gray-600 mt-1">{{ $location->address }}</p>
                                        @if(isset($location->distance))
                                            <p class="text-sm text-blue-600 mt-1">{{ number_format($location->distance, 1) }} {{ __('km away') }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-gray-900">{{ __('৳:amount', ['amount' => number_format($location->hourly_rate, 0)]) }}</p>
                                        <p class="text-sm text-gray-600">{{ __('per hour') }}</p>
                                    </div>
                                </div>

                                <!-- Availability -->
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                            <span class="text-sm text-gray-700">{{ $location->available_slots }} {{ __('available') }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-gray-300 rounded-full mr-2"></div>
                                            <span class="text-sm text-gray-700">{{ $location->total_slots }} {{ __('total') }}</span>
                                        </div>
                                    </div>

                                    @if($location->available_slots > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ __('Available') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ __('Full') }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Features -->
                                @if($location->features && count($location->features) > 0)
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @foreach(array_slice($location->features, 0, 3) as $feature)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ __($feature) }}
                                            </span>
                                        @endforeach
                                        @if(count($location->features) > 3)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                +{{ count($location->features) - 3 }} {{ __('more') }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <!-- Actions -->
                                <div class="flex space-x-3">
                                    <a href="{{ route('visitor.parking.show', $location) }}"
                                       class="flex-1 text-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                        {{ __('View Details') }}
                                    </a>
                                    @if($location->available_slots > 0)
                                        <a href="{{ route('visitor.bookings.create', ['location' => $location->id]) }}"
                                           class="flex-1 text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                            {{ __('Book Now') }}
                                        </a>
                                    @else
                                        <button disabled class="flex-1 text-center px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed">
                                            {{ __('Fully Booked') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $locations->appends(request()->query())->links() }}
                </div>
            @else
                <!-- No Results -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">{{ __('No parking locations found') }}</h3>
                    <p class="mt-1 text-gray-500">{{ __('Try adjusting your search criteria or expand your search area.') }}</p>
                </div>
            @endif
        @else
            <!-- Popular Locations -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Popular Parking Locations') }}</h2>
                <!-- Placeholder for popular locations when no search is performed -->
            </div>
        @endif
    </div>
</div>

<script>
function toggleFilters() {
    const filters = document.getElementById('advanced-filters');
    const arrow = document.getElementById('filter-arrow');

    if (filters.classList.contains('hidden')) {
        filters.classList.remove('hidden');
        arrow.classList.add('rotate-180');
    } else {
        filters.classList.add('hidden');
        arrow.classList.remove('rotate-180');
    }
}

// Auto-submit form when location is detected
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
        // Store coordinates for later use
        sessionStorage.setItem('user_lat', position.coords.latitude);
        sessionStorage.setItem('user_lng', position.coords.longitude);
    });
}
</script>
@endsection
