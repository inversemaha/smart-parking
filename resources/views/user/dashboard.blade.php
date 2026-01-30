@extends('layouts.user')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('general.dashboard') }}</h1>
                <p class="text-gray-600 mt-1">{{ __('user.welcome_back') }}, {{ Auth::user()->name }}!</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg">
                    <span class="text-sm font-medium">{{ __('vehicles.total_vehicles') }}: {{ $totalVehicles }}</span>
                </div>
                <div class="bg-green-50 text-green-700 px-4 py-2 rounded-lg">
                    <span class="text-sm font-medium">{{ __('booking.active_bookings') }}: {{ $activeBookings }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Book Parking -->
        <a href="{{ route('bookings.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white p-6 rounded-lg shadow-sm transition-colors group">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold">{{ __('parking.book_slot') }}</h3>
                    <p class="text-blue-100 text-sm">{{ __('parking.find_reserve_slot') }}</p>
                </div>
            </div>
        </a>

        <!-- My Vehicles -->
        <a href="{{ route('vehicles.index') }}" class="bg-green-600 hover:bg-green-700 text-white p-6 rounded-lg shadow-sm transition-colors group">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold">{{ __('vehicles.my_vehicles') }}</h3>
                    <p class="text-green-100 text-sm">{{ __('vehicles.manage_vehicles') }}</p>
                </div>
            </div>
        </a>

        <!-- My Bookings -->
        <a href="{{ route('bookings.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white p-6 rounded-lg shadow-sm transition-colors group">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold">{{ __('bookings.my_bookings') }}</h3>
                    <p class="text-purple-100 text-sm">{{ __('bookings.view_history') }}</p>
                </div>
            </div>
        </a>

        <!-- Payments -->
        <a href="{{ route('payments.index') }}" class="bg-orange-600 hover:bg-orange-700 text-white p-6 rounded-lg shadow-sm transition-colors group">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold">{{ __('payments.payments') }}</h3>
                    <p class="text-orange-100 text-sm">{{ __('payments.payment_history') }}</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Bookings -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('bookings.recent_bookings') }}</h3>
                    <a href="{{ route('bookings.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">{{ __('general.view_all') }}</a>
                </div>
            </div>
            <div class="p-6">
                @if($recentBookings->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentBookings as $booking)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">{{ $booking->parking_slot->area->name ?? 'N/A' }} - {{ __('parking.slot') }} {{ $booking->parking_slot->slot_number ?? 'N/A' }}</p>
                                        <p class="text-sm text-gray-500">{{ $booking->start_time->format('M j, Y g:i A') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                        @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ __(ucfirst($booking->status)) }}
                                    </span>
                                    <p class="text-sm font-medium text-gray-900 mt-1">৳{{ number_format($booking->total_amount, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('bookings.no_bookings') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ __('bookings.start_booking_now') }}</p>
                        <div class="mt-6">
                            <a href="{{ route('bookings.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                {{ __('parking.book_slot') }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- My Vehicles -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('vehicles.my_vehicles') }}</h3>
                    <a href="{{ route('vehicles.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">{{ __('general.view_all') }}</a>
                </div>
            </div>
            <div class="p-6">
                @if($recentVehicles->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentVehicles as $vehicle)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">{{ $vehicle->registration_number }}</p>
                                        <p class="text-sm text-gray-500">{{ $vehicle->type }} - {{ $vehicle->make }} {{ $vehicle->model }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($vehicle->verification_status === 'verified') bg-green-100 text-green-800
                                        @elseif($vehicle->verification_status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ __(ucfirst($vehicle->verification_status)) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
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
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">{{ __('general.total_bookings') }}</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $totalBookings }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">{{ __('payments.total_spent') }}</dt>
                        <dd class="text-lg font-semibold text-gray-900">৳{{ number_format($totalSpent, 2) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">{{ __('parking.hours_parked') }}</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ number_format($totalHours, 1) }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
