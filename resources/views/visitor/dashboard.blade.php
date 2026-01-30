@extends('layouts.visitor')

@section('title', __('general.visitor_dashboard'))
@section('page-title', __('general.dashboard'))

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-primary to-blue-600 rounded-2xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">
                    {{ app()->getLocale() === 'bn' ? 'স্বাগতম' : 'Welcome' }}, {{ $user->name }}!
                </h1>
                <p class="text-primary-100">
                    {{ __('general.dashboard_welcome_message') }}
                </p>
            </div>
            <div class="text-right">
                <div class="text-3xl mb-2">🚗</div>
                <div class="text-sm opacity-80">
                    {{ __('general.member_since', ['date' => $user->created_at->format('M Y')]) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Vehicles -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('vehicles.total') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_vehicles'] }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/30 rounded-full p-3">
                    <i data-lucide="car" class="size-6 text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('visitor.vehicles.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                    {{ __('vehicles.manage') }} →
                </a>
            </div>
        </div>

        <!-- Active Bookings -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('bookings.active') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['active_bookings'] }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900/30 rounded-full p-3">
                    <i data-lucide="calendar-check" class="size-6 text-green-600 dark:text-green-400"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('visitor.bookings.index', ['status' => 'active']) }}" class="text-sm text-green-600 dark:text-green-400 hover:underline">
                    {{ __('bookings.view_active') }} →
                </a>
            </div>
        </div>

        <!-- Total Bookings -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('bookings.total') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_bookings'] }}</p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900/30 rounded-full p-3">
                    <i data-lucide="calendar" class="size-6 text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('visitor.bookings.index') }}" class="text-sm text-purple-600 dark:text-purple-400 hover:underline">
                    {{ __('bookings.view_all') }} →
                </a>
            </div>
        </div>

        <!-- Total Spent -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('payments.total_spent') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">৳{{ number_format($stats['total_spent']) }}</p>
                </div>
                <div class="bg-orange-100 dark:bg-orange-900/30 rounded-full p-3">
                    <i data-lucide="wallet" class="size-6 text-orange-600 dark:text-orange-400"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('visitor.payments.index') }}" class="text-sm text-orange-600 dark:text-orange-400 hover:underline">
                    {{ __('payments.view_history') }} →
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('general.quick_actions') }}
            </h3>

            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('visitor.bookings.create') }}" class="flex flex-col items-center p-4 rounded-lg border-2 border-dashed border-primary hover:bg-primary/5 transition-colors">
                    <div class="bg-primary/10 rounded-full p-3 mb-2">
                        <i data-lucide="plus-circle" class="size-6 text-primary"></i>
                    </div>
                    <span class="text-sm font-medium text-primary">{{ __('bookings.new_booking') }}</span>
                </a>

                <a href="{{ route('visitor.vehicles.create') }}" class="flex flex-col items-center p-4 rounded-lg border-2 border-dashed border-blue-500 hover:bg-blue-50 transition-colors">
                    <div class="bg-blue-100 rounded-full p-3 mb-2">
                        <i data-lucide="car" class="size-6 text-blue-600"></i>
                    </div>
                    <span class="text-sm font-medium text-blue-600">{{ __('vehicles.add_vehicle') }}</span>
                </a>

                <a href="{{ route('visitor.parking.locations') }}" class="flex flex-col items-center p-4 rounded-lg border-2 border-dashed border-green-500 hover:bg-green-50 transition-colors">
                    <div class="bg-green-100 rounded-full p-3 mb-2">
                        <i data-lucide="map-pin" class="size-6 text-green-600"></i>
                    </div>
                    <span class="text-sm font-medium text-green-600">{{ __('parking.find_parking') }}</span>
                </a>

                <a href="{{ route('visitor.profile.index') }}" class="flex flex-col items-center p-4 rounded-lg border-2 border-dashed border-gray-500 hover:bg-gray-50 transition-colors">
                    <div class="bg-gray-100 rounded-full p-3 mb-2">
                        <i data-lucide="user" class="size-6 text-gray-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-600">{{ __('user.profile') }}</span>
                </a>
            </div>
        </div>

        <!-- Upcoming Bookings -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ __('bookings.upcoming') }}
                </h3>
                <a href="{{ route('visitor.bookings.index') }}" class="text-sm text-primary hover:underline">
                    {{ __('general.view_all') }}
                </a>
            </div>

            @if($upcomingBookings && $upcomingBookings->count() > 0)
                <div class="space-y-3">
                    @foreach($upcomingBookings as $booking)
                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="bg-primary/10 rounded-full p-2 mr-3">
                                <i data-lucide="calendar" class="size-4 text-primary"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ $booking->location->name }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $booking->start_datetime->format('M d, Y H:i') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ __('bookings.status_' . $booking->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6">
                    <div class="text-4xl mb-2">📅</div>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">{{ __('bookings.no_upcoming') }}</p>
                    <a href="{{ route('visitor.bookings.create') }}" class="btn btn--sm btn--primary">
                        {{ __('bookings.book_now') }}
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Activity & Vehicles -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Bookings -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ __('bookings.recent') }}
                </h3>
                <a href="{{ route('visitor.bookings.index') }}" class="text-sm text-primary hover:underline">
                    {{ __('general.view_all') }}
                </a>
            </div>

            @if($recentBookings && $recentBookings->count() > 0)
                <div class="space-y-3">
                    @foreach($recentBookings as $booking)
                        <div class="flex items-center p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            <div class="mr-3">
                                @if($booking->status === 'completed')
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                @elseif($booking->status === 'active')
                                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                @elseif($booking->status === 'cancelled')
                                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                @else
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ $booking->location->name }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $booking->created_at->diffForHumans() }} • ৳{{ number_format($booking->total_cost) }}
                                </p>
                            </div>
                            <a href="{{ route('visitor.bookings.show', $booking) }}" class="text-sm text-primary hover:underline">
                                {{ __('general.view') }}
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6">
                    <div class="text-4xl mb-2">🚗</div>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">{{ __('bookings.no_recent') }}</p>
                    <a href="{{ route('visitor.bookings.create') }}" class="btn btn--sm btn--primary">
                        {{ __('bookings.create_first') }}
                    </a>
                </div>
            @endif
        </div>

        <!-- My Vehicles -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ __('vehicles.my_vehicles') }}
                </h3>
                <a href="{{ route('visitor.vehicles.index') }}" class="text-sm text-primary hover:underline">
                    {{ __('general.view_all') }}
                </a>
            </div>

            @if($vehicles && $vehicles->count() > 0)
                <div class="space-y-3">
                    @foreach($vehicles->take(3) as $vehicle)
                        <div class="flex items-center p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            <div class="bg-gray-100 dark:bg-gray-600 rounded-full p-2 mr-3">
                                @switch($vehicle->type)
                                    @case('car')
                                        <i data-lucide="car" class="size-4 text-gray-600 dark:text-gray-300"></i>
                                        @break
                                    @case('motorcycle')
                                        <i data-lucide="bike" class="size-4 text-gray-600 dark:text-gray-300"></i>
                                        @break
                                    @default
                                        <i data-lucide="truck" class="size-4 text-gray-600 dark:text-gray-300"></i>
                                @endswitch
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $vehicle->make }} {{ $vehicle->model }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $vehicle->license_plate }}
                                    @if($vehicle->is_default)
                                        • <span class="text-primary">{{ __('vehicles.default') }}</span>
                                    @endif
                                </p>
                            </div>
                            @if($vehicle->verification_status === 'verified')
                                <div class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                    {{ __('vehicles.verified') }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6">
                    <div class="text-4xl mb-2">🚙</div>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">{{ __('vehicles.no_vehicles') }}</p>
                    <a href="{{ route('visitor.vehicles.create') }}" class="btn btn--sm btn--primary">
                        {{ __('vehicles.add_first') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-refresh stats every 30 seconds
setInterval(function() {
    // You can add AJAX calls here to refresh dashboard data
    // For now, we'll just log to console
    console.log('Dashboard refresh check');
}, 30000);
</script>
@endpush
@endsection
