@extends('layouts.app')

@section('page-title', __('admin.title'))

@push('styles')
<style>
    .admin-sidebar {
        min-height: calc(100vh - 64px);
    }
    .admin-nav-item {
        @apply flex items-center px-4 py-3 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors;
    }
    .admin-nav-item.active {
        @apply bg-blue-50 text-blue-700 border-r-2 border-blue-700;
    }
    .admin-nav-icon {
        @apply w-5 h-5 mr-3;
    }
    .stats-card {
        @apply bg-white rounded-lg shadow-sm border border-gray-200 p-6;
    }
    .stats-icon {
        @apply w-12 h-12 rounded-lg flex items-center justify-center;
    }
</style>
@endpush

@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <div class="w-64 bg-white shadow-sm admin-sidebar">
        <div class="p-6">
            <h2 class="text-xl font-semibold text-gray-800">{{ __('admin.title') }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ __('admin.overview') }}</p>
        </div>

        <nav class="px-4 pb-4">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard.index') }}"
               class="admin-nav-item {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                <i data-lucide="home" class="admin-nav-icon"></i>
                {{ __('admin.navigation.dashboard') }}
            </a>

            <!-- User Management -->
            <div class="mt-6">
                <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('admin.navigation.users') }}</h3>
                <div class="mt-2 space-y-1">
                    @can('viewUsers', \App\Policies\AdminPolicy::class)
                    <a href="{{ route('admin.users.index') }}"
                       class="admin-nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <i data-lucide="user-check" class="admin-nav-icon"></i>
                        {{ __('admin.users.title') }}
                    </a>
                    @endcan

                    @can('manageRoles', \App\Policies\AdminPolicy::class)
                    <a href="{{ route('admin.permissions.roles') }}"
                       class="admin-nav-item {{ request()->routeIs('admin.permissions.roles*') ? 'active' : '' }}">
                        <i data-lucide="shield" class="admin-nav-icon"></i>
                        {{ __('admin.permissions.roles') }}
                    </a>
                    @endcan

                    @can('managePermissions', \App\Policies\AdminPolicy::class)
                    <a href="{{ route('admin.permissions.index') }}"
                       class="admin-nav-item {{ request()->routeIs('admin.permissions.index*') ? 'active' : '' }}">
                        <i data-lucide="key" class="admin-nav-icon"></i>
                        {{ __('admin.permissions.title') }}
                    </a>
                    @endcan
                </div>
            </div>

            <!-- Vehicle Management -->
            <div class="mt-6">
                <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('admin.navigation.vehicles') }}</h3>
                <div class="mt-2 space-y-1">
                    @can('verifyVehicles', \App\Policies\AdminPolicy::class)
                    <a href="{{ route('admin.vehicles.pending') }}"
                       class="admin-nav-item {{ request()->routeIs('admin.vehicles*') ? 'active' : '' }}">
                        <i data-lucide="car" class="admin-nav-icon"></i>
                        {{ __('admin.vehicles.title') }}
                    </a>
                    @endcan
                </div>
            </div>

            <!-- Parking Management -->
            <div class="mt-6">
                <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('admin.navigation.parking') }}</h3>
                <div class="mt-2 space-y-1">
                    @can('manageParkingLocations', \App\Policies\AdminPolicy::class)
                    <a href="{{ route('admin.parking.locations') }}"
                       class="admin-nav-item {{ request()->routeIs('admin.parking*') ? 'active' : '' }}">
                        <i data-lucide="map-pin" class="admin-nav-icon"></i>
                        {{ __('admin.parking.title') }}
                    </a>
                    @endcan
                </div>
            </div>

            <!-- Booking Management -->
            <div class="mt-6">
                <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('admin.navigation.bookings') }}</h3>
                <div class="mt-2 space-y-1">
                    @can('viewBookings', \App\Policies\AdminPolicy::class)
                    <a href="{{ route('admin.bookings.index') }}"
                       class="admin-nav-item {{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">
                        <i data-lucide="calendar" class="admin-nav-icon"></i>
                        {{ __('admin.bookings.title') }}
                    </a>
                    @endcan
                </div>
            </div>

            <!-- Payment Management -->
            <div class="mt-6">
                <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('admin.navigation.payments') }}</h3>
                <div class="mt-2 space-y-1">
                    @can('viewPayments', \App\Policies\AdminPolicy::class)
                    <a href="{{ route('admin.payments.index') }}"
                       class="admin-nav-item {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
                        <i data-lucide="credit-card" class="admin-nav-icon"></i>
                        {{ __('admin.payments.title') }}
                    </a>
                    @endcan
                </div>
            </div>

            <!-- Reports -->
            <div class="mt-6">
                <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('admin.navigation.reports') }}</h3>
                <div class="mt-2 space-y-1">
                    @can('viewReports', \App\Policies\AdminPolicy::class)
                    <a href="{{ route('admin.reports.index') }}"
                       class="admin-nav-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                        <i data-lucide="bar-chart-3" class="admin-nav-icon"></i>
                        {{ __('admin.reports.title') }}
                    </a>
                    @endcan

                    @can('viewAuditLogs', \App\Policies\AdminPolicy::class)
                    <a href="{{ route('admin.audit.index') }}"
                       class="admin-nav-item {{ request()->routeIs('admin.audit*') ? 'active' : '' }}">
                        <i data-lucide="activity" class="admin-nav-icon"></i>
                        {{ __('admin.audit.title') }}
                    </a>
                    @endcan
                </div>
            </div>

            <!-- System Management -->
            <div class="mt-6">
                <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('admin.navigation.system') }}</h3>
                <div class="mt-2 space-y-1">
                    @can('viewSystemSettings', \App\Policies\AdminPolicy::class)
                    <a href="{{ route('admin.settings.index') }}"
                       class="admin-nav-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                        <i data-lucide="settings" class="admin-nav-icon"></i>
                        {{ __('admin.settings.title') }}
                    </a>
                    @endcan

                    @can('viewSystemMonitoring', \App\Policies\AdminPolicy::class)
                    <a href="{{ route('admin.system.health') }}"
                       class="admin-nav-item {{ request()->routeIs('admin.system*') ? 'active' : '' }}">
                        <i data-lucide="monitor" class="admin-nav-icon"></i>
                        {{ __('admin.system.title') }}
                    </a>
                    @endcan
                </div>
            </div>

            <!-- Emergency -->
            @can('performEmergencyOperations', \App\Policies\AdminPolicy::class)
            <div class="mt-6 border-t pt-4">
                <a href="{{ route('admin.emergency.index') }}"
                   class="admin-nav-item text-red-600 hover:text-red-700 hover:bg-red-50 {{ request()->routeIs('admin.emergency*') ? 'active bg-red-50' : '' }}">
                    <i data-lucide="alert-triangle" class="admin-nav-icon"></i>
                    {{ __('admin.emergency.title') }}
                </a>
            </div>
            @endcan
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 min-h-screen">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">@yield('admin-title', __('admin.dashboard'))</h1>
                    @if (View::hasSection('admin-subtitle'))
                        <p class="text-sm text-gray-600 mt-1">@yield('admin-subtitle')</p>
                    @endif
                </div>

                <!-- Language Switcher -->
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <select id="language-switcher"
                                class="form-select text-sm border-gray-300 rounded-lg"
                                onchange="switchLanguage(this.value)">
                            <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English</option>
                            <option value="bn" {{ app()->getLocale() === 'bn' ? 'selected' : '' }}>বাংলা</option>
                        </select>
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                            <i data-lucide="user" class="w-4 h-4 text-gray-600"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="p-6">
            @yield('admin-content')
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchLanguage(locale) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ url('/language') }}/${locale}`;

    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';

    form.appendChild(csrfToken);
    document.body.appendChild(form);
    form.submit();
}

// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush
@endsection
