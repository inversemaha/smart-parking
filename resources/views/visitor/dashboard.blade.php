@extends('layouts.visitor')

@section('title', __('general.visitor_dashboard'))
@section('page-title', __('general.dashboard'))
@section('content-class', 'mt-6')

@section('content')
<div class="mt-8">
    <div class="flex h-10 items-center">
        <h2 class="me-5 truncate text-lg font-medium">{{ __('general.dashboard') }} - {{ $user->name }}</h2>
        <a class="text-primary ms-auto flex items-center gap-3" href="{{ route('visitor.dashboard') }}">
            <i data-lucide="refresh-ccw" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
            {{ __('general.reload_data') }}
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="mt-5 grid grid-cols-12 gap-6">
        <!-- Total Vehicles -->
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="box p-5">
                <div class="flex">
                    <i data-lucide="car" class="size-7 stroke-1 [--color:var(--color-primary)] stroke-(--color) fill-(--color)/25"></i>
                    <div class="ms-auto text-success rounded-full px-2 py-px text-xs">{{ $stats['total_vehicles'] }}</div>
                </div>
                <div class="mt-6 text-2xl font-medium leading-8">{{ $stats['total_vehicles'] }}</div>
                <div class="mt-1.5 text-xs uppercase opacity-70">{{ __('vehicles.total') }}</div>
            </div>
        </div>

        <!-- Active Bookings -->
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="box p-5">
                <div class="flex">
                    <i data-lucide="calendar-check" class="size-7 stroke-1 [--color:var(--color-success)] stroke-(--color) fill-(--color)/25"></i>
                    <div class="ms-auto text-primary rounded-full px-2 py-px text-xs">{{ $stats['active_bookings'] }}</div>
                </div>
                <div class="mt-6 text-2xl font-medium leading-8">{{ $stats['active_bookings'] }}</div>
                <div class="mt-1.5 text-xs uppercase opacity-70">{{ __('bookings.active') }}</div>
            </div>
        </div>

        <!-- Total Bookings -->
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="box p-5">
                <div class="flex">
                    <i data-lucide="calendar" class="size-7 stroke-1 [--color:var(--color-warning)] stroke-(--color) fill-(--color)/25"></i>
                    <div class="ms-auto text-warning rounded-full px-2 py-px text-xs">{{ $stats['total_bookings'] }}</div>
                </div>
                <div class="mt-6 text-2xl font-medium leading-8">{{ $stats['total_bookings'] }}</div>
                <div class="mt-1.5 text-xs uppercase opacity-70">{{ __('bookings.total') }}</div>
            </div>
        </div>

        <!-- Total Spent -->
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="box p-5">
                <div class="flex">
                    <i data-lucide="wallet" class="size-7 stroke-1 [--color:var(--color-success)] stroke-(--color) fill-(--color)/25"></i>
                    <div class="ms-auto text-success rounded-full px-2 py-px text-xs">৳{{ number_format($stats['total_spent'], 0) }}</div>
                </div>
                <div class="mt-6 text-2xl font-medium leading-8">৳{{ number_format($stats['total_spent'], 0) }}</div>
                <div class="mt-1.5 text-xs uppercase opacity-70">{{ __('payments.total_spent') }}</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Upcoming -->
    <div class="mt-8 grid grid-cols-12 gap-6">
        <!-- Quick Actions -->
        <div class="col-span-12 xl:col-span-5">
            <div class="box p-5">
                <div class="flex h-10 items-center">
                    <h2 class="truncate text-lg font-medium">{{ __('general.quick_actions') }}</h2>
                </div>

                <div class="mt-3 space-y-3">
                    <a href="{{ route('visitor.bookings.create') }}" class="flex items-center gap-3 rounded-xl border border-foreground/10 p-3 hover:bg-foreground/[.03]">
                        <i data-lucide="plus-circle" class="size-5 text-primary"></i>
                        <span class="text-sm">{{ __('bookings.new_booking') }}</span>
                    </a>
                    <a href="{{ route('visitor.vehicles.create') }}" class="flex items-center gap-3 rounded-xl border border-foreground/10 p-3 hover:bg-foreground/[.03]">
                        <i data-lucide="car" class="size-5 text-primary"></i>
                        <span class="text-sm">{{ __('vehicles.add_vehicle') }}</span>
                    </a>
                    <a href="{{ route('visitor.parking.locations') }}" class="flex items-center gap-3 rounded-xl border border-foreground/10 p-3 hover:bg-foreground/[.03]">
                        <i data-lucide="map-pin" class="size-5 text-success"></i>
                        <span class="text-sm">{{ __('parking.find_parking') }}</span>
                    </a>
                    <a href="{{ route('visitor.profile.index') }}" class="flex items-center gap-3 rounded-xl border border-foreground/10 p-3 hover:bg-foreground/[.03]">
                        <i data-lucide="user" class="size-5 text-warning"></i>
                        <span class="text-sm">{{ __('user.profile') }}</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Upcoming Bookings -->
        <div class="col-span-12 xl:col-span-7">
            <div class="box p-5">
                <div class="flex h-10 items-center border-b border-foreground/10 pb-5">
                    <h2 class="truncate text-lg font-medium">{{ __('bookings.upcoming') }}</h2>
                    <a class="text-primary ms-auto text-sm" href="{{ route('visitor.bookings.index') }}">
                        {{ __('general.view_all') }}
                    </a>
                </div>

                @if($upcomingBookings && $upcomingBookings->count() > 0)
                    <div class="mt-5 space-y-3">
                        @foreach($upcomingBookings as $booking)
                            <div class="flex items-center gap-3 rounded-lg border border-foreground/10 p-3">
                                <div class="flex-shrink">
                                    <i data-lucide="calendar" class="size-5 text-primary"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium truncate">{{ $booking->location->name ?? 'N/A' }}</div>
                                    <div class="text-xs opacity-70">{{ $booking->start_datetime->format('M d, Y H:i') ?? 'N/A' }}</div>
                                </div>
                                <div class="flex-shrink">
                                    <span class="inline-block px-2 py-1 rounded-full text-xs font-medium {{ $booking->status === 'confirmed' ? 'bg-success/20 text-success' : 'bg-warning/20 text-warning' }}">
                                        {{ __('bookings.status_' . $booking->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="mt-5 py-8 text-center">
                        <i data-lucide="calendar" class="size-12 mx-auto opacity-30 mb-2"></i>
                        <p class="text-sm opacity-70">{{ __('bookings.no_upcoming') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Bookings & Vehicles -->
    <div class="mt-8 grid grid-cols-12 gap-6">
        <!-- Recent Bookings -->
        <div class="col-span-12 lg:col-span-6">
            <div class="box p-5">
                <div class="flex h-10 items-center border-b border-foreground/10 pb-5">
                    <h2 class="truncate text-lg font-medium">{{ __('bookings.recent') }}</h2>
                    <a class="text-primary ms-auto text-sm" href="{{ route('visitor.bookings.index') }}">
                        {{ __('general.view_all') }}
                    </a>
                </div>

                @if($recentBookings && $recentBookings->count() > 0)
                    <div class="mt-5 space-y-2">
                        @foreach($recentBookings as $booking)
                            <div class="flex items-center gap-3 rounded-lg p-2 hover:bg-foreground/[.03]">
                                <div class="flex-shrink">
                                    @if($booking->status === 'completed')
                                        <div class="size-3 rounded-full bg-success"></div>
                                    @elseif($booking->status === 'active')
                                        <div class="size-3 rounded-full bg-primary"></div>
                                    @elseif($booking->status === 'cancelled')
                                        <div class="size-3 rounded-full bg-danger"></div>
                                    @else
                                        <div class="size-3 rounded-full bg-warning"></div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium truncate">{{ $booking->location->name ?? 'N/A' }}</div>
                                    <div class="text-xs opacity-70">{{ $booking->created_at->diffForHumans() }} • ৳{{ number_format($booking->total_cost ?? 0) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="mt-5 py-8 text-center">
                        <i data-lucide="book-open" class="size-12 mx-auto opacity-30 mb-2"></i>
                        <p class="text-sm opacity-70">{{ __('bookings.no_recent') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- My Vehicles -->
        <div class="col-span-12 lg:col-span-6">
            <div class="box p-5">
                <div class="flex h-10 items-center border-b border-foreground/10 pb-5">
                    <h2 class="truncate text-lg font-medium">{{ __('vehicles.my_vehicles') }}</h2>
                    <a class="text-primary ms-auto text-sm" href="{{ route('visitor.vehicles.index') }}">
                        {{ __('general.view_all') }}
                    </a>
                </div>

                @if($vehicles && $vehicles->count() > 0)
                    <div class="mt-5 space-y-2">
                        @foreach($vehicles->take(5) as $vehicle)
                            <div class="flex items-center gap-3 rounded-lg p-2 hover:bg-foreground/[.03]">
                                <div class="flex-shrink">
                                    <i data-lucide="car" class="size-5 opacity-70"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium truncate">{{ $vehicle->make }} {{ $vehicle->model }}</div>
                                    <div class="text-xs opacity-70">{{ $vehicle->license_plate }}</div>
                                </div>
                                @if($vehicle->verification_status === 'verified')
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-success/20 text-success flex-shrink">✓</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="mt-5 py-8 text-center">
                        <i data-lucide="car" class="size-12 mx-auto opacity-30 mb-2"></i>
                        <p class="text-sm opacity-70">{{ __('vehicles.no_vehicles') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection