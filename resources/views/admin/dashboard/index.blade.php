@extends('layouts.admin')

@section('title', __('general.admin_dashboard'))
@section('page-title', __('general.admin_dashboard'))

@php
$breadcrumb = [
    ['title' => __('general.admin'), 'url' => route('admin.dashboard.index')],
    ['title' => __('general.dashboard')]
];

$vehicleVerificationRate = $stats['vehicles']['total'] > 0
    ? round(($stats['vehicles']['verified'] / $stats['vehicles']['total']) * 100)
    : 0;

$activeBookingRate = $stats['bookings']['total'] > 0
    ? round(($stats['bookings']['active'] / $stats['bookings']['total']) * 100)
    : 0;
@endphp

@section('content-class', 'mt-3')

@section('content')
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 2xl:col-span-9">
        <div class="grid grid-cols-12 gap-6">
            <!-- BEGIN: Notification -->
            <div class="col-span-12 mt-6 -mb-6 intro-y">
                <div class="alert alert-dismissible show box bg-primary text-white flex items-center mb-6" role="alert">
                    <span>Welcome to Smart Parking Admin Dashboard! Manage your parking system efficiently.</span>
                    <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
            <!-- END: Notification -->

            <!-- BEGIN: General Report -->
            <div class="col-span-12 lg:col-span-8 xl:col-span-6 mt-2">
                <div class="intro-y block sm:flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        General Report
                    </h2>
                    <select class="sm:ml-auto mt-3 sm:mt-0 sm:w-auto form-select box">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>
                <div class="report-box-2 intro-y mt-12 sm:mt-5">
                    <div class="box sm:flex">
                        <div class="px-8 py-12 flex flex-col justify-center flex-1">
                            <i data-lucide="dollar-sign" class="w-10 h-10 text-success"></i>
                            <div class="relative text-3xl font-medium mt-12 pl-4 ml-0.5">
                                <span class="absolute text-2xl font-medium top-0 left-0 -ml-0.5">$</span>
                                {{ number_format($stats['payments']['total_amount'], 0) }}
                            </div>
                            <div class="text-slate-500">Total revenue from parking services</div>
                            <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary relative justify-start rounded-full mt-12">
                                View Reports
                                <span class="w-8 h-8 absolute flex justify-center items-center bg-primary text-white rounded-full right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                </span>
                            </a>
                        </div>
                        <div class="px-8 py-12 flex flex-col justify-center flex-1 border-t sm:border-t-0 sm:border-l border-slate-200 dark:border-darkmode-300 border-dashed">
                            <div class="text-slate-500 text-xs">TOTAL USERS</div>
                            <div class="mt-1.5 flex items-center">
                                <div class="text-base font-medium">{{ $stats['users']['total'] }}</div>
                            </div>

                            <div class="text-slate-500 text-xs mt-5">TOTAL VEHICLES</div>
                            <div class="mt-1.5 flex items-center">
                                <div class="text-base font-medium">{{ $stats['vehicles']['total'] }}</div>
                                @if($stats['vehicles']['pending'] > 0)
                                    <div class="text-warning flex text-xs font-medium tooltip cursor-pointer ml-2" title="Pending verification">
                                        {{ $stats['vehicles']['pending'] }} <i data-lucide="alert-circle" class="w-4 h-4 ml-0.5"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="text-slate-500 text-xs mt-5">TOTAL BOOKINGS</div>
                            <div class="mt-1.5 flex items-center">
                                <div class="text-base font-medium">{{ $stats['bookings']['total'] }}</div>
                            </div>

                            <div class="text-slate-500 text-xs mt-5">NEW USERS (THIS WEEK)</div>
                            <div class="mt-1.5 flex items-center">
                                <div class="text-base font-medium">{{ $stats['users']['new_week'] }}</div>
                                <div class="text-success flex text-xs font-medium tooltip cursor-pointer ml-2" title="Growth this week">
                                    ↑ <i data-lucide="chevron-up" class="w-4 h-4 ml-0.5"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: General Report -->

            <!-- BEGIN: Active Sessions -->
            <div class="col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3 mt-2">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Active Sessions
                    </h2>
                </div>
                <div class="report-box-2 intro-y mt-5">
                    <div class="box p-5">
                        <div class="flex items-center">
                            Active parking sessions
                        </div>
                        <div class="text-2xl font-medium mt-2">{{ $stats['bookings']['active'] }}</div>
                        <div class="border-b border-slate-200 flex pb-2 mt-4">
                            <div class="text-slate-500 text-xs">Session completion rate</div>
                            @php
                                $completion_rate = $stats['bookings']['total'] > 0 ? round(($stats['bookings']['completed'] / $stats['bookings']['total']) * 100) : 0;
                            @endphp
                            <div class="text-success flex text-xs font-medium tooltip cursor-pointer ml-auto" title="Completion rate">
                                {{ $completion_rate }}% <i data-lucide="chevron-up" class="w-4 h-4 ml-0.5"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="text-slate-500 text-xs flex mb-2 pb-2">
                                <div>Today's Bookings</div>
                                <div class="ml-auto font-medium">{{ $stats['bookings']['today'] }}</div>
                            </div>
                            <div class="text-slate-500 text-xs flex">
                                <div>Completed</div>
                                <div class="ml-auto font-medium">{{ $stats['bookings']['completed'] }}</div>
                            </div>
                        </div>
                        <a href="{{ route('admin.reports.bookings') }}" class="btn btn-outline-secondary border-dashed w-full py-1 px-2 mt-4">Booking Report</a>
                    </div>
                </div>
            </div>
            <!-- END: Active Sessions -->

            <!-- BEGIN: Users Overview -->
            <div class="col-span-12 sm:col-span-6 lg:col-span-4 xl:col-span-3 mt-2 lg:mt-6 xl:mt-2">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        User Stats
                    </h2>
                </div>
                <div class="report-box-2 intro-y mt-5">
                    <div class="box p-5">
                        <div class="text-slate-500 text-sm mb-4">User Growth Overview</div>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="text-xs text-slate-500">Active Users</div>
                                <div class="text-lg font-medium">{{ $stats['users']['active'] }}</div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="text-xs text-slate-500">New This Week</div>
                                <div class="text-lg font-medium text-success">{{ $stats['users']['new_week'] }}</div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="text-xs text-slate-500">New Today</div>
                                <div class="text-lg font-medium text-primary">{{ $stats['users']['new_today'] }}</div>
                            </div>
                            <div class="flex items-center justify-between border-t pt-4">
                                <div class="text-xs text-slate-500">Total Users</div>
                                <div class="text-xl font-medium">{{ $stats['users']['total'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Users Overview -->

            <!-- BEGIN: Vehicle Status -->
            <div class="col-span-12 lg:col-span-8 mt-6">
                <div class="intro-y block sm:flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Vehicle Management
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="text-center">
                            <div class="text-3xl font-medium">{{ $stats['vehicles']['total'] }}</div>
                            <div class="text-slate-500 text-xs mt-2">Total Vehicles</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-medium text-success">{{ $stats['vehicles']['verified'] }}</div>
                            <div class="text-slate-500 text-xs mt-2">Verified</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-medium text-warning">{{ $stats['vehicles']['pending'] }}</div>
                            <div class="text-slate-500 text-xs mt-2">Pending</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-medium text-danger">{{ $stats['vehicles']['rejected'] }}</div>
                            <div class="text-slate-500 text-xs mt-2">Rejected</div>
                        </div>
                    </div>
                    @if($stats['vehicles']['pending'] > 0)
                        <div class="mt-6">
                            <a href="{{ route('admin.vehicles.pending') }}" class="btn btn-outline-primary">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-2"></i> Review {{ $stats['vehicles']['pending'] }} Pending Vehicle(s)
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            <!-- END: Vehicle Status -->

            <!-- BEGIN: Payment Processing -->
            <div class="col-span-12 xl:col-span-4 mt-6">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Payment Summary
                    </h2>
                </div>
                <div class="mt-5">
                    <div class="intro-y">
                        <div class="box px-5 py-4 mb-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-slate-500 text-xs">Total Revenue</div>
                                    <div class="text-2xl font-medium mt-1">${{ number_format($stats['payments']['total_amount'], 0) }}</div>
                                </div>
                                <i data-lucide="trending-up" class="w-10 h-10 text-success opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="intro-y">
                        <div class="box px-5 py-4 mb-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-slate-500 text-xs">This Month</div>
                                    <div class="text-2xl font-medium mt-1">${{ number_format($stats['payments']['month_amount'], 0) }}</div>
                                </div>
                                <div class="text-center">
                                    <div class="inline-flex items-center px-2 py-1 rounded text-xs bg-success/10 text-success font-medium">
                                        Active
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="intro-y">
                        <div class="box px-5 py-4 mb-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-slate-500 text-xs">Today's Revenue</div>
                                    <div class="text-2xl font-medium mt-1">${{ number_format($stats['payments']['today_amount'], 0) }}</div>
                                </div>
                                <i data-lucide="dollar-sign" class="w-10 h-10 text-primary opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    @if($stats['payments']['pending'] > 0)
                        <div class="intro-y">
                            <div class="box px-5 py-4 border-l-4 border-warning">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-slate-500 text-xs">Pending Verification</div>
                                        <div class="text-2xl font-medium mt-1">{{ $stats['payments']['pending'] }}</div>
                                    </div>
                                    <i data-lucide="clock" class="w-10 h-10 text-warning opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <!-- END: Payment Processing -->
        </div>
    </div>

    <div class="col-span-12 2xl:col-span-3">
        <div class="2xl:border-l -mb-10 pb-10">
            <div class="2xl:pl-6 grid grid-cols-12 gap-x-6 2xl:gap-x-0 gap-y-6">
                <!-- BEGIN: Important Notes -->
                <div class="col-span-12 md:col-span-6 xl:col-span-12 mt-3 2xl:mt-8">
                    <div class="intro-x flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-auto">
                            Important Notes
                        </h2>
                    </div>
                    <div class="mt-5 intro-x">
                        <div class="box">
                            <div class="space-y-3 p-5 max-h-96 overflow-y-auto">
                                @if($stats['vehicles']['pending'] > 0)
                                    <div class="pb-3">
                                        <div class="text-base font-medium truncate">Vehicle Verification Pending</div>
                                        <div class="text-slate-400 text-xs mt-1">{{ now()->format('H:i A') }}</div>
                                        <div class="text-slate-500 text-xs mt-2">{{ $stats['vehicles']['pending'] }} vehicle(s) are waiting for verification and approval.</div>
                                        <div class="font-medium flex mt-4 gap-2">
                                            <a href="{{ route('admin.vehicles.pending') }}" class="btn btn-secondary py-1 px-2 text-xs">Review</a>
                                        </div>
                                    </div>
                                    <div class="border-b"></div>
                                @endif

                                <div class="pb-3">
                                    <div class="text-base font-medium truncate">System Status</div>
                                    <div class="text-slate-400 text-xs mt-1">{{ now()->format('H:i A') }}</div>
                                    <div class="text-slate-500 text-xs mt-2">All systems are operating normally. Total active sessions: {{ $stats['bookings']['active'] }}</div>
                                </div>
                                <div class="border-b"></div>

                                <div class="pb-3">
                                    <div class="text-base font-medium truncate">Daily Report</div>
                                    <div class="text-slate-400 text-xs mt-1">{{ now()->format('H:i A') }}</div>
                                    <div class="text-slate-500 text-xs mt-2">Today's bookings: {{ $stats['bookings']['today'] }}, New users: {{ $stats['users']['new_today'] }}, Revenue: ${{ number_format($stats['payments']['today_amount'], 0) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Important Notes -->

                <!-- BEGIN: Quick Actions -->
                <div class="col-span-12 md:col-span-6 xl:col-span-12 mt-3">
                    <div class="intro-x flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-auto">
                            Quick Actions
                        </h2>
                    </div>
                    <div class="mt-5">
                        <div class="space-y-2">
                            @if(\Illuminate\Support\Facades\Route::has('admin.vehicles.pending') && $stats['vehicles']['pending'] > 0)
                                <a href="{{ route('admin.vehicles.pending') }}" class="intro-x block box px-4 py-3 hover:shadow-md transition">
                                    <div class="flex items-center">
                                        <i data-lucide="alert-triangle" class="w-5 h-5 text-warning mr-3"></i>
                                        <div>
                                            <div class="font-medium text-sm">Pending Vehicles</div>
                                            <div class="text-xs text-slate-500">{{ $stats['vehicles']['pending'] }} awaiting review</div>
                                        </div>
                                    </div>
                                </a>
                            @endif

                            @if(\Illuminate\Support\Facades\Route::has('admin.permissions.index'))
                                <a href="{{ route('admin.permissions.index') }}" class="intro-x block box px-4 py-3 hover:shadow-md transition">
                                    <div class="flex items-center">
                                        <i data-lucide="shield" class="w-5 h-5 text-primary mr-3"></i>
                                        <div>
                                            <div class="font-medium text-sm">Manage Permissions</div>
                                            <div class="text-xs text-slate-500">User access control</div>
                                        </div>
                                    </div>
                                </a>
                            @endif

                            @if(\Illuminate\Support\Facades\Route::has('admin.system.health'))
                                <a href="{{ route('admin.system.health') }}" class="intro-x block box px-4 py-3 hover:shadow-md transition">
                                    <div class="flex items-center">
                                        <i data-lucide="activity" class="w-5 h-5 text-success mr-3"></i>
                                        <div>
                                            <div class="font-medium text-sm">System Health</div>
                                            <div class="text-xs text-slate-500">Monitor system status</div>
                                        </div>
                                    </div>
                                </a>
                            @endif

                            @if(\Illuminate\Support\Facades\Route::has('admin.reports.index'))
                                <a href="{{ route('admin.reports.index') }}" class="intro-x block box px-4 py-3 hover:shadow-md transition">
                                    <div class="flex items-center">
                                        <i data-lucide="bar-chart-2" class="w-5 h-5 text-info mr-3"></i>
                                        <div>
                                            <div class="font-medium text-sm">View Reports</div>
                                            <div class="text-xs text-slate-500">Detailed analytics</div>
                                        </div>
                                    </div>
                                </a>
                            @endif

                            @if(\Illuminate\Support\Facades\Route::has('admin.permissions.roles'))
                                <a href="{{ route('admin.permissions.roles') }}" class="intro-x block box px-4 py-3 hover:shadow-md transition">
                                    <div class="flex items-center">
                                        <i data-lucide="users" class="w-5 h-5 text-secondary mr-3"></i>
                                        <div>
                                            <div class="font-medium text-sm">Manage Roles</div>
                                            <div class="text-xs text-slate-500">Role-based access</div>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- END: Quick Actions -->
            </div>
        </div>
    </div>
</div>

@endsection