@extends('layouts.visitor')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('My Vehicles') }}</h1>
                    <p class="text-gray-600 mt-1">{{ __('Manage your registered vehicles') }}</p>
                </div>
                <a href="{{ route('visitor.vehicles.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('Add Vehicle') }}
                </a>
            </div>
        </div>

        <!-- Vehicles List -->
        <div class="bg-white shadow rounded-lg">
            @if($vehicles->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($vehicles as $vehicle)
                        <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div class="flex items-center space-x-4 flex-1">
                                <!-- Vehicle Icon -->
                                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </div>

                                <!-- Vehicle Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <p class="text-lg font-semibold text-gray-900">{{ $vehicle->license_plate }}</p>
                                        @if($vehicle->is_default)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ __('Default') }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-gray-600">{{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->year }})</p>
                                    <div class="flex items-center text-sm text-gray-500 mt-1">
                                        <span class="mr-4">{{ __('Color') }}: {{ $vehicle->color }}</span>
                                        <span>{{ __('Type') }}: {{ ucfirst($vehicle->vehicle_type) }}</span>
                                    </div>
                                    @if($vehicle->notes)
                                        <p class="text-sm text-gray-500 mt-1">{{ $vehicle->notes }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-col sm:flex-row gap-2 min-w-fit">
                                <!-- Set as Default -->
                                @if(!$vehicle->is_default)
                                    <form action="{{ route('visitor.vehicles.set-default', $vehicle) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                            {{ __('Set Default') }}
                                        </button>
                                    </form>
                                @endif

                                <!-- Edit -->
                                <a href="{{ route('visitor.vehicles.edit', $vehicle) }}" class="text-sm text-gray-600 hover:text-gray-800 font-medium">
                                    {{ __('Edit') }}
                                </a>

                                <!-- Delete -->
                                <form action="{{ route('visitor.vehicles.destroy', $vehicle) }}" method="POST" class="inline"
                                      onsubmit="return confirm('{{ __('Are you sure you want to delete this vehicle?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">
                                        {{ __('Delete') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">{{ __('No vehicles registered') }}</h3>
                    <p class="mt-1 text-gray-500">{{ __('Get started by adding your first vehicle.') }}</p>
                    <div class="mt-6">
                        <a href="{{ route('visitor.vehicles.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            {{ __('Add Your First Vehicle') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
