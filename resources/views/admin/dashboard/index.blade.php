@extends('layouts.app')

@section('title', __('general.admin_dashboard'))
@section('page-title', __('general.admin_dashboard'))

@php
$breadcrumb = [
    ['title' => __('general.admin'), 'url' => route('admin.dashboard.index')],
    ['title' => __('general.dashboard')]
];
@endphp

@section('content')
<!-- Stats Overview -->
<div class="grid grid-cols-12 gap-6 mb-6">
    <!-- Total Users -->
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-medium text-slate-800">{{ number_format($stats['users']['total']) }}</div>
                    <div class="text-slate-500 text-sm">{{ __('general.total_users') }}</div>
                </div>
            </div>
            <div class="mt-3 text-sm">
                <span class="text-green-600">+{{ $stats['users']['new_today'] }}</span>
                <span class="text-slate-500 ml-1">{{ __('general.today') }}</span>
            </div>
        </div>
    </div>

    <!-- Total Vehicles -->
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="car" class="w-6 h-6 text-green-600"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-medium text-slate-800">{{ number_format($stats['vehicles']['total']) }}</div>
                    <div class="text-slate-500 text-sm">{{ __('general.total_vehicles') }}</div>
                </div>
            </div>
            <div class="mt-3 text-sm">
                <span class="text-orange-600">{{ $stats['vehicles']['pending'] }}</span>
                <span class="text-slate-500 ml-1">{{ __('general.pending_verification') }}</span>
            </div>
        </div>
    </div>

    <!-- Total Bookings -->
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="calendar" class="w-6 h-6 text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-medium text-slate-800">{{ number_format($stats['bookings']['total']) }}</div>
                    <div class="text-slate-500 text-sm">{{ __('general.total_bookings') }}</div>
                </div>
            </div>
            <div class="mt-3 text-sm">
                <span class="text-blue-600">{{ $stats['bookings']['active'] }}</span>
                <span class="text-slate-500 ml-1">{{ __('general.active') }}</span>
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
        <div class="box p-5">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="dollar-sign" class="w-6 h-6 text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-medium text-slate-800">৳{{ number_format($stats['payments']['total_amount'], 2) }}</div>
                    <div class="text-slate-500 text-sm">{{ __('general.total_revenue') }}</div>
                </div>
            </div>
            <div class="mt-3 text-sm">
                <span class="text-green-600">৳{{ number_format($stats['payments']['today_amount'], 2) }}</span>
                <span class="text-slate-500 ml-1">{{ __('general.today') }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions & Recent Activity -->
<div class="grid grid-cols-12 gap-6">
    <!-- Quick Actions -->
    <div class="col-span-12 xl:col-span-4">
        <div class="box p-6">
            <h3 class="text-lg font-medium mb-6">{{ __('general.quick_actions') }}</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.vehicles.pending') }}" class="flex items-center justify-between p-4 hover:bg-slate-50 rounded-lg border border-slate-200">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="clock" class="w-5 h-5 text-orange-600"></i>
                        </div>
                        <div class="ml-3">
                            <div class="font-medium">{{ __('general.pending_vehicles') }}</div>
                            <div class="text-sm text-slate-500">{{ $stats['vehicles']['pending'] }} {{ __('general.items') }}</div>
                        </div>
                    </div>
                    <i data-lucide="arrow-right" class="w-4 h-4 text-slate-400"></i>
                </a>

                <a href="{{ route('admin.permissions.index') }}" class="flex items-center justify-between p-4 hover:bg-slate-50 rounded-lg border border-slate-200">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="shield" class="w-5 h-5 text-blue-600"></i>
                        </div>
                        <div class="ml-3">
                            <div class="font-medium">{{ __('general.manage_permissions') }}</div>
                            <div class="text-sm text-slate-500">{{ __('general.roles_and_permissions') }}</div>
                        </div>
                    </div>
                    <i data-lucide="arrow-right" class="w-4 h-4 text-slate-400"></i>
                </a>

                <a href="{{ route('admin.system.health') }}" class="flex items-center justify-between p-4 hover:bg-slate-50 rounded-lg border border-slate-200">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="activity" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <div class="ml-3">
                            <div class="font-medium">{{ __('general.system_health') }}</div>
                            <div class="text-sm text-slate-500">{{ __('general.check_system_status') }}</div>
                        </div>
                    </div>
                    <i data-lucide="arrow-right" class="w-4 h-4 text-slate-400"></i>
                </a>

                <a href="{{ route('admin.reports.index') }}" class="flex items-center justify-between p-4 hover:bg-slate-50 rounded-lg border border-slate-200">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="bar-chart-3" class="w-5 h-5 text-purple-600"></i>
                        </div>
                        <div class="ml-3">
                            <div class="font-medium">{{ __('general.reports') }}</div>
                            <div class="text-sm text-slate-500">{{ __('general.view_analytics') }}</div>
                        </div>
                    </div>
                    <i data-lucide="arrow-right" class="w-4 h-4 text-slate-400"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity & System Status -->
    <div class="col-span-12 xl:col-span-8">
        <div class="grid grid-cols-12 gap-6">
            <!-- System Status -->
            <div class="col-span-12 lg:col-span-6">
                <div class="box p-6">
                    <h3 class="text-lg font-medium mb-6">{{ __('general.system_status') }}</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">{{ __('general.database') }}</span>
                            <span class="flex items-center text-green-600">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                {{ __('general.healthy') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">{{ __('general.cache') }}</span>
                            <span class="flex items-center text-green-600">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                {{ __('general.connected') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">{{ __('general.queue') }}</span>
                            <span class="flex items-center text-green-600">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                {{ __('general.operational') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">{{ __('general.storage') }}</span>
                            <span class="flex items-center text-green-600">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                85% {{ __('general.available') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="col-span-12 lg:col-span-6">
                <div class="box p-6">
                    <h3 class="text-lg font-medium mb-6">{{ __('general.today_summary') }}</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">{{ __('general.new_registrations') }}</span>
                            <span class="font-medium">{{ $stats['users']['new_today'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">{{ __('general.bookings_today') }}</span>
                            <span class="font-medium">{{ $stats['bookings']['today'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">{{ __('general.revenue_today') }}</span>
                            <span class="font-medium">৳{{ number_format($stats['payments']['today_amount'], 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">{{ __('general.pending_payments') }}</span>
                            <span class="font-medium text-orange-600">{{ $stats['payments']['pending'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart placeholder -->
        <div class="box p-6 mt-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium">{{ __('general.revenue_trend') }}</h3>
                <select class="form-select w-32">
                    <option value="7">{{ __('general.last_7_days') }}</option>
                    <option value="30">{{ __('general.last_30_days') }}</option>
                    <option value="90">{{ __('general.last_90_days') }}</option>
                </select>
            </div>
            <div class="h-64 flex items-center justify-center bg-slate-50 rounded-lg">
                <div class="text-center text-slate-500">
                    <i data-lucide="bar-chart-3" class="w-12 h-12 mx-auto mb-2"></i>
                    <p>{{ __('general.chart_will_be_displayed_here') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Auto refresh stats every 30 seconds
setInterval(function() {
    // Implement auto refresh logic
}, 30000);

// Initialize chart if needed
document.addEventListener('DOMContentLoaded', function() {
    // Chart initialization code here
});
</script>
@endpush
