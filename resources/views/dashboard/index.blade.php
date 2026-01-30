@extends('layouts.app')

@section('title', 'Dashboard Overview')
@section('page-title', 'Overview')

@php
$breadcrumb = [
    ['title' => 'Dashboard', 'url' => route('dashboard.index')],
    ['title' => 'Overview']
];
@endphp

@section('content-class', 'mt-6')

@section('content')
<!-- BEGIN: General Report -->
<div class="mt-8">
    <div class="flex h-10 items-center">
        <h2 class="me-5 truncate text-lg font-medium">Parking Overview</h2>
        <a class="text-primary ms-auto flex items-center gap-3" href="#">
            <i data-lucide="refresh-ccw" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
            Refresh
        </a>
    </div>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <!-- Total Revenue Card -->
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md">
                <div class="flex">
                    <i data-lucide="dollar-sign" class="size-4 stroke-(--color) fill-(--color)/25 h-7 w-7 stroke-1 drop-shadow [--color:var(--color-primary)]"></i>
                    <div class="ms-auto">
                        <div class="bg-(--color)/20 border-(--color)/60 text-(--color) flex cursor-pointer items-center rounded-full border px-2 py-px text-xs tooltip pl-2 pr-1 [--color:var(--color-success)]" data-content="15% Higher than last month">
                            15%
                            <i data-lucide="chevron-up" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 ms-0.5"></i>
                        </div>
                    </div>
                </div>
                <div class="mt-6 text-2xl font-medium leading-8">$12,450.50</div>
                <div class="mt-1.5 text-xs uppercase opacity-70">Total Revenue</div>
            </div>
        </div>

        <!-- Active Vehicles Card -->
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md">
                <div class="flex">
                    <i data-lucide="car" class="size-4 stroke-(--color) fill-(--color)/25 h-7 w-7 stroke-1 [--color:var(--color-pending)]"></i>
                    <div class="ms-auto">
                        <div class="bg-(--color)/20 border-(--color)/60 text-(--color) flex cursor-pointer items-center rounded-full border px-2 py-px text-xs tooltip pl-2 pr-1 [--color:var(--color-success)]" data-content="8% Higher than last month">
                            8%
                            <i data-lucide="chevron-up" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 ms-0.5"></i>
                        </div>
                    </div>
                </div>
                <div class="mt-6 text-2xl font-medium leading-8">342</div>
                <div class="mt-1.5 text-xs uppercase opacity-70">Active Vehicles</div>
            </div>
        </div>

        <!-- Available Spaces Card -->
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md">
                <div class="flex">
                    <i data-lucide="map-pin" class="size-4 stroke-(--color) fill-(--color)/25 h-7 w-7 stroke-1 [--color:var(--color-warning)]"></i>
                    <div class="ms-auto">
                        <div class="bg-(--color)/20 border-(--color)/60 text-(--color) flex cursor-pointer items-center rounded-full border px-2 py-px text-xs tooltip pl-2 pr-1 [--color:var(--color-danger)]" data-content="12% Lower than last month">
                            12%
                            <i data-lucide="chevron-down" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 ms-0.5"></i>
                        </div>
                    </div>
                </div>
                <div class="mt-6 text-2xl font-medium leading-8">28</div>
                <div class="mt-1.5 text-xs uppercase opacity-70">Available Spaces</div>
            </div>
        </div>

        <!-- Total Transactions Card -->
        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md">
                <div class="flex">
                    <i data-lucide="credit-card" class="size-4 stroke-(--color) fill-(--color)/25 h-7 w-7 stroke-1 [--color:var(--color-success)]"></i>
                    <div class="ms-auto">
                        <div class="bg-(--color)/20 border-(--color)/60 text-(--color) flex cursor-pointer items-center rounded-full border px-2 py-px text-xs tooltip pl-2 pr-1 [--color:var(--color-success)]" data-content="25% Higher than last month">
                            25%
                            <i data-lucide="chevron-up" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 ms-0.5"></i>
                        </div>
                    </div>
                </div>
                <div class="mt-6 text-2xl font-medium leading-8">1,247</div>
                <div class="mt-1.5 text-xs uppercase opacity-70">Total Transactions</div>
            </div>
        </div>
    </div>
</div>
<!-- END: General Report -->

<div class="grid grid-cols-12 gap-6 mt-8">
    <!-- Revenue Report -->
    <div class="col-span-12 lg:col-span-6">
        <div class="block h-10 items-center sm:flex">
            <h2 class="me-5 truncate text-lg font-medium">Revenue Report</h2>
            <div class="relative mt-3 sm:ms-auto sm:mt-0">
                <i data-lucide="calendar" class="size-4 stroke-[1.5] stroke-(--color) fill-(--color)/25 [--color:var(--color-foreground)]/50 absolute inset-y-0 start-0 z-10 my-auto ms-4"></i>
                <input type="text" class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box pl-11 sm:w-64" placeholder="Select date range">
            </div>
        </div>
        <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md mt-5 p-5">
            <div class="flex flex-col md:flex-row md:items-center">
                <div class="flex">
                    <div>
                        <div class="text-lg font-medium">$8,450.21</div>
                        <div class="mt-1.5 text-xs uppercase opacity-70">This Month</div>
                    </div>
                    <div class="border-foreground/20 mx-4 h-12 w-px border border-r border-dotted xl:mx-6"></div>
                    <div class="text-foreground/80">
                        <div class="text-lg font-medium">$7,230.15</div>
                        <div class="mt-1.5 text-xs uppercase opacity-70">Last Month</div>
                    </div>
                </div>
            </div>
            <div class="relative before:content-[''] before:block before:absolute before:w-16 before:start-0 before:top-0 before:bottom-0 before:ms-11 before:mb-7 before:bg-gradient-to-r before:from-background before:via-background/80 before:to-transparent after:content-[''] after:block after:absolute after:w-16 after:end-0 after:top-0 after:bottom-0 after:mb-7 after:bg-gradient-to-l after:from-background after:via-background/80 after:to-transparent">
                <div class="w-auto h-[275px] flex items-center justify-center text-foreground/50">
                    <div class="text-center">
                        <i data-lucide="bar-chart-3" class="size-16 mx-auto opacity-30"></i>
                        <div class="mt-2 text-sm">Revenue Chart Placeholder</div>
                        <div class="mt-1 text-xs opacity-70">Chart will be implemented with actual data</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Parking Space Utilization -->
    <div class="col-span-12 lg:col-span-6">
        <div class="flex h-10 items-center">
            <h2 class="me-5 truncate text-lg font-medium">Space Utilization</h2>
            <a class="text-primary ms-auto truncate" href="#">View Details</a>
        </div>
        <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md mt-5 p-5">
            <div class="w-auto h-[275px] flex items-center justify-center text-foreground/50">
                <div class="text-center">
                    <i data-lucide="pie-chart" class="size-16 mx-auto opacity-30"></i>
                    <div class="mt-2 text-sm">Utilization Chart Placeholder</div>
                    <div class="mt-1 text-xs opacity-70">Pie chart showing space usage</div>
                </div>
            </div>
            <div class="mx-auto mt-8 w-52 sm:w-auto">
                <div class="flex items-center">
                    <div class="bg-(--color)/20 border-(--color)/60 me-3 size-2 rounded-full border [--color:var(--color-primary)]"></div>
                    <span class="truncate">Occupied Spaces</span>
                    <span class="ms-auto">72%</span>
                </div>
                <div class="mt-4 flex items-center">
                    <div class="bg-(--color)/20 border-(--color)/60 me-3 size-2 rounded-full border [--color:var(--color-warning)]"></div>
                    <span class="truncate">Available Spaces</span>
                    <span class="ms-auto">28%</span>
                </div>
                <div class="mt-4 flex items-center">
                    <div class="bg-(--color)/20 border-(--color)/60 me-3 size-2 rounded-full border [--color:var(--color-danger)]"></div>
                    <span class="truncate">Maintenance</span>
                    <span class="ms-auto">5%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Vehicles -->
    <div class="col-span-12 xl:col-span-8">
        <div class="flex h-10 items-center">
            <h2 class="me-5 truncate text-lg font-medium">Recent Vehicle Entries</h2>
            <a class="text-primary ms-auto" href="#">View All</a>
        </div>
        <div class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md mt-5">
            <div class="overflow-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-foreground/10">
                            <th class="px-5 py-4 text-left font-medium text-foreground/70">Vehicle</th>
                            <th class="px-5 py-4 text-left font-medium text-foreground/70">License Plate</th>
                            <th class="px-5 py-4 text-left font-medium text-foreground/70">Space</th>
                            <th class="px-5 py-4 text-left font-medium text-foreground/70">Entry Time</th>
                            <th class="px-5 py-4 text-left font-medium text-foreground/70">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-foreground/5">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="bg-primary/20 text-primary flex size-10 items-center justify-center rounded-lg">
                                        <i data-lucide="car" class="size-5"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium">Toyota Camry</div>
                                        <div class="text-xs opacity-70">Sedan</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 font-medium">ABC-123</td>
                            <td class="px-5 py-4">A-15</td>
                            <td class="px-5 py-4 text-foreground/70">10:30 AM</td>
                            <td class="px-5 py-4">
                                <span class="bg-success/20 text-success px-2 py-1 rounded-full text-xs">Active</span>
                            </td>
                        </tr>
                        <tr class="border-b border-foreground/5">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="bg-warning/20 text-warning flex size-10 items-center justify-center rounded-lg">
                                        <i data-lucide="truck" class="size-5"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium">Ford F-150</div>
                                        <div class="text-xs opacity-70">Truck</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 font-medium">XYZ-789</td>
                            <td class="px-5 py-4">B-08</td>
                            <td class="px-5 py-4 text-foreground/70">09:45 AM</td>
                            <td class="px-5 py-4">
                                <span class="bg-success/20 text-success px-2 py-1 rounded-full text-xs">Active</span>
                            </td>
                        </tr>
                        <tr class="border-b border-foreground/5">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="bg-danger/20 text-danger flex size-10 items-center justify-center rounded-lg">
                                        <i data-lucide="bus" class="size-5"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium">Honda Civic</div>
                                        <div class="text-xs opacity-70">Compact</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 font-medium">DEF-456</td>
                            <td class="px-5 py-4">C-22</td>
                            <td class="px-5 py-4 text-foreground/70">08:15 AM</td>
                            <td class="px-5 py-4">
                                <span class="bg-warning/20 text-warning px-2 py-1 rounded-full text-xs">Expired</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-span-12 xl:col-span-4">
        <div class="flex h-10 items-center">
            <h2 class="me-5 truncate text-lg font-medium">Quick Actions</h2>
        </div>
        <div class="mt-5 space-y-4">
            <!-- Add Vehicle Button -->
            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md cursor-pointer hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="bg-primary/20 text-primary flex size-12 items-center justify-center rounded-lg">
                        <i data-lucide="plus-circle" class="size-6"></i>
                    </div>
                    <div class="ml-4">
                        <div class="font-medium">Add New Vehicle</div>
                        <div class="text-sm opacity-70">Register a new parking entry</div>
                    </div>
                    <i data-lucide="chevron-right" class="ml-auto size-5 opacity-50"></i>
                </div>
            </div>

            <!-- View Reports Button -->
            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md cursor-pointer hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="bg-warning/20 text-warning flex size-12 items-center justify-center rounded-lg">
                        <i data-lucide="file-text" class="size-6"></i>
                    </div>
                    <div class="ml-4">
                        <div class="font-medium">Generate Reports</div>
                        <div class="text-sm opacity-70">View detailed analytics</div>
                    </div>
                    <i data-lucide="chevron-right" class="ml-auto size-5 opacity-50"></i>
                </div>
            </div>

            <!-- Manage Spaces Button -->
            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md cursor-pointer hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="bg-success/20 text-success flex size-12 items-center justify-center rounded-lg">
                        <i data-lucide="map-pin" class="size-6"></i>
                    </div>
                    <div class="ml-4">
                        <div class="font-medium">Manage Spaces</div>
                        <div class="text-sm opacity-70">Configure parking areas</div>
                    </div>
                    <i data-lucide="chevron-right" class="ml-auto size-5 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add any dashboard-specific JavaScript here
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Dashboard loaded successfully');

        // Auto-refresh functionality (optional)
        // setInterval(function() {
        //     // Refresh dashboard data
        // }, 60000); // Every minute
    });
</script>
@endpush
