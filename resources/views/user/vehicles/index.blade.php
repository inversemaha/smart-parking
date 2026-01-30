@extends('layouts.user')

@section('title', __('vehicles.my_vehicles'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('vehicles.my_vehicles') }}</h1>
                <p class="text-gray-600 mt-1">{{ __('vehicles.manage_vehicles') }}</p>
            </div>
            <a href="{{ route('vehicles.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                {{ __('vehicles.add_vehicle') }}
            </a>
        </div>
    </div>

    <!-- Vehicles Grid -->
    @if($vehicles->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($vehicles as $vehicle)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $vehicle->registration_number }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($vehicle->verification_status === 'verified') bg-green-100 text-green-800
                                @elseif($vehicle->verification_status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ __('vehicles.' . $vehicle->verification_status) }}
                            </span>
                        </div>

                        <div class="space-y-2 text-sm text-gray-600">
                            <p><span class="font-medium">{{ __('vehicles.type') }}:</span> {{ __('vehicles.' . $vehicle->type) }}</p>
                            <p><span class="font-medium">{{ __('vehicles.make') }}:</span> {{ $vehicle->make }} {{ $vehicle->model }}</p>
                            <p><span class="font-medium">{{ __('vehicles.year') }}:</span> {{ $vehicle->year }}</p>
                            <p><span class="font-medium">{{ __('vehicles.color') }}:</span> {{ $vehicle->color }}</p>
                        </div>

                        <div class="mt-6 flex space-x-3">
                            <a href="{{ route('vehicles.show', $vehicle) }}" class="flex-1 bg-blue-50 text-blue-700 text-center py-2 px-3 rounded-md text-sm font-medium hover:bg-blue-100 transition-colors">
                                {{ __('general.view') }}
                            </a>
                            <a href="{{ route('vehicles.edit', $vehicle) }}" class="flex-1 bg-gray-50 text-gray-700 text-center py-2 px-3 rounded-md text-sm font-medium hover:bg-gray-100 transition-colors">
                                {{ __('general.edit') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($vehicles->hasPages())
            <div class="mt-8">
                {{ $vehicles->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('vehicles.no_vehicles') }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ __('vehicles.add_vehicle_desc') }}</p>
            <div class="mt-6">
                <a href="{{ route('vehicles.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    {{ __('vehicles.add_vehicle') }}
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
