@php
    $isAdmin = auth()->check() && auth()->user()->hasAnyRole(['admin', 'super-admin']);
@endphp

<!-- Sidebar -->
<div class="side-menu side-menu--rubick group bg-background/80 dark:bg-foreground/[.01] border-transparent relative inset-y-0 left-0 z-30 max-h-screen w-[275px] min-w-0 border-r duration-300 ease-in-out transition-[width,margin] xl:translate-x-0 xl:z-auto fixed xl:static -translate-x-full [&.side-menu--collapsed]:xl:w-[110px] [&.side-menu--collapsed]:xl:[&.side-menu--on-hover]:w-[275px]">

    <!-- Logo -->
    <div class="flex h-[70px] items-center border-b border-foreground/[.15] z-10 relative px-7">
        <a href="{{ $isAdmin ? route('admin.dashboard.index') : route('dashboard.index') }}" class="flex items-center gap-3">
            <div class="bg-primary/20 flex h-8 w-8 items-center justify-center rounded-lg">
                <i data-lucide="car" class="h-4 w-4 fill-primary/20 stroke-primary"></i>
            </div>
            <div class="side-menu__content">
                <div class="text-lg font-semibold">Smart Parking</div>
                <div class="text-xs opacity-70">{{ $isAdmin ? 'Admin Panel' : 'User Dashboard' }}</div>
            </div>
        </a>
    </div>

    <!-- Navigation Menu -->
    <nav class="scrollbar-hidden side-menu__content overflow-y-auto h-full py-5">

        @if($isAdmin)
            <!-- Admin Menu Items -->

            <!-- Dashboard Section -->
            <div class="side-menu__item">
                <a href="{{ route('admin.dashboard.index') }}"
                   class="side-menu__link {{ request()->routeIs('admin.dashboard*') ? 'side-menu__link--active' : '' }}">
                    <i data-lucide="layout-dashboard" class="side-menu__icon"></i>
                    <div class="side-menu__title">Dashboard</div>
                </a>
            </div>

            <!-- User Management Section -->
            @can('manage_users')
            <div class="side-menu__item">
                <a href="javascript:;" class="side-menu__link">
                    <i data-lucide="users" class="side-menu__icon"></i>
                    <div class="side-menu__title">User Management</div>
                    <i data-lucide="chevron-down" class="side-menu__chevron"></i>
                </a>
                <ul class="side-menu__sub-menu">
                    <li><a href="{{ route('admin.users.index') }}" class="side-menu__sub-link">Users</a></li>
                    @can('manage_roles')
                    <li><a href="{{ route('admin.permissions.roles') }}" class="side-menu__sub-link">Roles</a></li>
                    @endcan
                    @can('manage_permissions')
                    <li><a href="{{ route('admin.permissions.index') }}" class="side-menu__sub-link">Permissions</a></li>
                    <li><a href="{{ route('admin.permissions.users') }}" class="side-menu__sub-link">User Roles</a></li>
                    @endcan
                    @can('view_user_sessions')
                    <li><a href="{{ route('admin.user-sessions.index') }}" class="side-menu__sub-link">User Sessions</a></li>
                    <li><a href="{{ route('admin.user-devices.index') }}" class="side-menu__sub-link">User Devices</a></li>
                    @endcan
                    @can('view_access_logs')
                    <li><a href="{{ route('admin.access-logs.index') }}" class="side-menu__sub-link">Access Logs</a></li>
                    @endcan
                </ul>
            </div>
            @endcan

            <!-- Parking Management Section -->
            @can('manage_parking')
            <div class="side-menu__item">
                <a href="javascript:;" class="side-menu__link">
                    <i data-lucide="map-pin" class="side-menu__icon"></i>
                    <div class="side-menu__title">Parking Management</div>
                    <i data-lucide="chevron-down" class="side-menu__chevron"></i>
                </a>
                <ul class="side-menu__sub-menu">
                    <li><a href="{{ route('admin.locations.index') }}" class="side-menu__sub-link">Parking Locations</a></li>
                    <li><a href="{{ route('admin.parking-slots.index') }}" class="side-menu__sub-link">Parking Slots</a></li>
                    <li><a href="{{ route('admin.slot-types.index') }}" class="side-menu__sub-link">Slot Types</a></li>
                    <li><a href="{{ route('admin.live-status.index') }}" class="side-menu__sub-link">Live Slot Status</a></li>
                </ul>
            </div>
            @endcan

            <!-- Booking Management Section -->
            @can('manage_bookings')
            <div class="side-menu__item">
                <a href="javascript:;" class="side-menu__link">
                    <i data-lucide="calendar-check" class="side-menu__icon"></i>
                    <div class="side-menu__title">Booking Management</div>
                    <i data-lucide="chevron-down" class="side-menu__chevron"></i>
                </a>
                <ul class="side-menu__sub-menu">
                    <li><a href="{{ route('admin.bookings.active') }}" class="side-menu__sub-link">Active Bookings</a></li>
                    <li><a href="{{ route('admin.bookings.expired') }}" class="side-menu__sub-link">Expired Bookings</a></li>
                    <li><a href="{{ route('admin.bookings.history') }}" class="side-menu__sub-link">Booking History</a></li>
                </ul>
            </div>
            @endcan

            <!-- Vehicle Management Section -->
            @can('manage_vehicles')
            <div class="side-menu__item">
                <a href="javascript:;" class="side-menu__link">
                    <i data-lucide="car" class="side-menu__icon"></i>
                    <div class="side-menu__title">Vehicle Management</div>
                    <i data-lucide="chevron-down" class="side-menu__chevron"></i>
                </a>
                <ul class="side-menu__sub-menu">
                    <li><a href="{{ route('admin.vehicles.index') }}" class="side-menu__sub-link">All Vehicles</a></li>
                    <li><a href="{{ route('admin.vehicles.verification') }}" class="side-menu__sub-link">Manual Vehicle Verification</a></li>
                    <li><a href="{{ route('admin.vehicles.brta-logs') }}" class="side-menu__sub-link">BRTA Verification Logs</a></li>
                    <li><a href="{{ route('admin.vehicles.search') }}" class="side-menu__sub-link">Vehicle Search</a></li>
                </ul>
            </div>
            @endcan

            <!-- Gate Management Section -->
            @can('operate.gates')
            <div class="side-menu__item">
                <a href="javascript:;" class="side-menu__link">
                    <i data-lucide="lock-keyhole" class="side-menu__icon"></i>
                    <div class="side-menu__title">Gate Management</div>
                    <i data-lucide="chevron-down" class="side-menu__chevron"></i>
                </a>
                <ul class="side-menu__sub-menu">
                    <li><a href="{{ route('admin.gates.index') }}" class="side-menu__sub-link">Gates</a></li>
                    <li><a href="{{ route('admin.gate-entries.index') }}" class="side-menu__sub-link">Vehicle Entry Logs</a></li>
                    <li><a href="{{ route('admin.gate-exits.index') }}" class="side-menu__sub-link">Vehicle Exit Logs</a></li>
                </ul>
            </div>
            @endcan

            <!-- Payments & Finance Section -->
            @can('manage_payments')
            <div class="side-menu__item">
                <a href="javascript:;" class="side-menu__link">
                    <i data-lucide="credit-card" class="side-menu__icon"></i>
                    <div class="side-menu__title">Payments & Finance</div>
                    <i data-lucide="chevron-down" class="side-menu__chevron"></i>
                </a>
                <ul class="side-menu__sub-menu">
                    <li><a href="{{ route('admin.payments.index') }}" class="side-menu__sub-link">Payments</a></li>
                    <li><a href="{{ route('admin.payments.sslcommerz') }}" class="side-menu__sub-link">SSLCommerz Transactions</a></li>
                    <li><a href="{{ route('admin.payments.reconciliation') }}" class="side-menu__sub-link">Daily Reconciliation</a></li>
                    <li><a href="{{ route('admin.payments.refunds') }}" class="side-menu__sub-link">Refunds</a></li>
                </ul>
            </div>
            @endcan

            <!-- Reports & Analytics Section -->
            @can('view_reports')
            <div class="side-menu__item">
                <a href="javascript:;" class="side-menu__link">
                    <i data-lucide="chart-bar" class="side-menu__icon"></i>
                    <div class="side-menu__title">Reports & Analytics</div>
                    <i data-lucide="chevron-down" class="side-menu__chevron"></i>
                </a>
                <ul class="side-menu__sub-menu">
                    <li><a href="{{ route('admin.reports.usage') }}" class="side-menu__sub-link">Parking Usage</a></li>
                    <li><a href="{{ route('admin.reports.utilization') }}" class="side-menu__sub-link">Slot Utilization</a></li>
                    <li><a href="{{ route('admin.reports.entry-exit') }}" class="side-menu__sub-link">Vehicle Entry/Exit</a></li>
                    <li><a href="{{ route('admin.reports.payment') }}" class="side-menu__sub-link">Payment Report</a></li>
                    <li><a href="{{ route('admin.reports.export') }}" class="side-menu__sub-link">Export (CSV / PDF)</a></li>
                </ul>
            </div>
            @endcan

            <!-- System Configuration Section -->
            @can('manage_settings')
            <div class="side-menu__item">
                <a href="javascript:;" class="side-menu__link">
                    <i data-lucide="settings" class="side-menu__icon"></i>
                    <div class="side-menu__title">System Configuration</div>
                    <i data-lucide="chevron-down" class="side-menu__chevron"></i>
                </a>
                <ul class="side-menu__sub-menu">
                    <li><a href="{{ route('admin.settings.general') }}" class="side-menu__sub-link">General Settings</a></li>
                    <li><a href="{{ route('admin.settings.brta') }}" class="side-menu__sub-link">BRTA Settings</a></li>
                    <li><a href="{{ route('admin.settings.payment-gateway') }}" class="side-menu__sub-link">Payment Gateway Settings</a></li>
                    <li><a href="{{ route('admin.settings.booking-rules') }}" class="side-menu__sub-link">Booking Rules</a></li>
                    <li><a href="{{ route('admin.settings.security') }}" class="side-menu__sub-link">Session & Security Settings</a></li>
                </ul>
            </div>
            @endcan

            <!-- Logs & Monitoring Section -->
            @can('view_logs')
            <div class="side-menu__item">
                <a href="javascript:;" class="side-menu__link">
                    <i data-lucide="activity" class="side-menu__icon"></i>
                    <div class="side-menu__title">Logs & Monitoring</div>
                    <i data-lucide="chevron-down" class="side-menu__chevron"></i>
                </a>
                <ul class="side-menu__sub-menu">
                    <li><a href="{{ route('admin.logs.audit') }}" class="side-menu__sub-link">Audit Logs</a></li>
                    <li><a href="{{ route('admin.logs.failed-jobs') }}" class="side-menu__sub-link">Failed Jobs</a></li>
                    <li><a href="{{ route('admin.logs.errors') }}" class="side-menu__sub-link">System Errors</a></li>
                </ul>
            </div>
            @endcan

        @else
            <!-- User Menu Items -->

            <!-- Dashboard Section -->
            <div class="side-menu__item">
                <a href="{{ route('user.dashboard') }}"
                   class="side-menu__link {{ request()->routeIs('user.dashboard*') ? 'side-menu__link--active' : '' }}">
                    <i data-lucide="layout-dashboard" class="side-menu__icon"></i>
                    <div class="side-menu__title">Dashboard</div>
                </a>
            </div>

            <!-- My Bookings Section -->
            <div class="side-menu__item">
                <a href="javascript:;" class="side-menu__link">
                    <i data-lucide="calendar-check" class="side-menu__icon"></i>
                    <div class="side-menu__title">My Bookings</div>
                    <i data-lucide="chevron-down" class="side-menu__chevron"></i>
                </a>
                <ul class="side-menu__sub-menu">
                    <li><a href="{{ route('user.bookings.index') }}" class="side-menu__sub-link">All Bookings</a></li>
                    <li><a href="{{ route('user.bookings.active') }}" class="side-menu__sub-link">Active Bookings</a></li>
                    <li><a href="{{ route('user.bookings.history') }}" class="side-menu__sub-link">Booking History</a></li>
                </ul>
            </div>

            <!-- Find Parking Section -->
            <div class="side-menu__item">
                <a href="{{ route('user.parking.search') }}"
                   class="side-menu__link {{ request()->routeIs('user.parking.search*') ? 'side-menu__link--active' : '' }}">
                    <i data-lucide="search" class="side-menu__icon"></i>
                    <div class="side-menu__title">Find Parking</div>
                </a>
            </div>

            <!-- My Vehicles Section -->
            <div class="side-menu__item">
                <a href="javascript:;" class="side-menu__link">
                    <i data-lucide="car" class="side-menu__icon"></i>
                    <div class="side-menu__title">My Vehicles</div>
                    <i data-lucide="chevron-down" class="side-menu__chevron"></i>
                </a>
                <ul class="side-menu__sub-menu">
                    <li><a href="{{ route('user.vehicles.index') }}" class="side-menu__sub-link">Vehicle List</a></li>
                    <li><a href="{{ route('user.vehicles.create') }}" class="side-menu__sub-link">Add Vehicle</a></li>
                </ul>
            </div>

            <!-- Payment History Section -->
            <div class="side-menu__item">
                <a href="{{ route('user.payments.index') }}"
                   class="side-menu__link {{ request()->routeIs('user.payments*') ? 'side-menu__link--active' : '' }}">
                    <i data-lucide="credit-card" class="side-menu__icon"></i>
                    <div class="side-menu__title">Payment History</div>
                </a>
            </div>

            <!-- Support Section -->
            <div class="side-menu__item">
                <a href="{{ route('user.support.index') }}"
                   class="side-menu__link {{ request()->routeIs('user.support*') ? 'side-menu__link--active' : '' }}">
                    <i data-lucide="help-circle" class="side-menu__icon"></i>
                    <div class="side-menu__title">Support</div>
                </a>
            </div>

        @endif

    </nav>

    <!-- User Account Section -->
    <div class="side-menu__account group/profile absolute inset-x-0 bottom-0 mx-4 mb-8 transition-[width] group-[.side-menu--collapsed.side-menu--on-hover]:block group-[.side-menu--collapsed]:justify-center xl:group-[.side-menu--collapsed]:flex">
        <div class="bg-background/10 border-background/20 dark:bg-foreground/[.02] dark:border-foreground/[.09] flex cursor-pointer items-center rounded-full border p-2.5 opacity-80 backdrop-blur-2xl transition hover:opacity-100">
            <div class="border-background/20 dark:border-foreground/20 relative h-10 w-10 flex-none overflow-hidden rounded-full border-4">
                <img class="absolute top-0 h-full w-full object-cover" src="{{ asset('backend/assets/images/fakers/profile-11.jpg') }}" alt="User Profile">
            </div>
            <div class="ms-3 flex w-full items-center overflow-hidden transition-opacity group-[.side-menu--collapsed.side-menu--on-hover]:ms-3 group-[.side-menu--collapsed.side-menu--on-hover]:w-full group-[.side-menu--collapsed.side-menu--on-hover]:opacity-100 xl:group-[.side-menu--collapsed]:ms-0 xl:group-[.side-menu--collapsed]:w-0 xl:group-[.side-menu--collapsed]:opacity-0">
                <div class="w-28">
                    <div class="w-full truncate font-medium">{{ auth()->check() ? auth()->user()->name : 'User' }}</div>
                    <div class="w-full truncate text-xs opacity-60">{{ $isAdmin ? 'Administrator' : 'User' }}</div>
                </div>
                <i data-lucide="move-right" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 me-4 ms-auto opacity-50"></i>
            </div>
        </div>

        <!-- User Profile Dropdown -->
        <div class="hidden group-hover/profile:block">
            <div class="box p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:z-[-1] after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md text-foreground before:shadow-foreground/5 absolute bottom-0 left-[100%] z-50 ml-2 flex w-64 flex-col gap-2.5 px-6 py-5 before:rounded-2xl before:shadow-xl before:backdrop-blur after:rounded-2xl">
                <div class="flex flex-col gap-0.5">
                    <div class="font-medium">{{ auth()->check() ? auth()->user()->name : 'User' }}</div>
                    <div class="mt-0.5 text-xs opacity-70">{{ $isAdmin ? 'System Administrator' : 'Registered User' }}</div>
                </div>
                <div class="bg-foreground/5 h-px"></div>
                <div class="flex flex-col gap-0.5">
                    <a class="hover:bg-foreground/5 -mx-3 flex gap-2.5 rounded-lg px-4 py-1.5" href="{{ route('profile.index') }}">
                        <i data-lucide="users" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                        Profile
                    </a>
                    <a class="hover:bg-foreground/5 -mx-3 flex gap-2.5 rounded-lg px-4 py-1.5" href="{{ route('profile.index') }}#security">
                        <i data-lucide="shield-alert" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                        Security
                    </a>
                    <a class="hover:bg-foreground/5 -mx-3 flex gap-2.5 rounded-lg px-4 py-1.5" href="{{ route('profile.index') }}#password">
                        <i data-lucide="file-lock" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                        Change Password
                    </a>
                    @if(!$isAdmin)
                    <a class="hover:bg-foreground/5 -mx-3 flex gap-2.5 rounded-lg px-4 py-1.5" href="#" onclick="alert('Support feature coming soon!')">
                        <i data-lucide="file-question" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                        Help
                    </a>
                    @endif
                </div>
                <div class="bg-foreground/5 h-px"></div>
                <div class="flex flex-col gap-0.5">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="hover:bg-foreground/5 -mx-3 flex gap-2.5 rounded-lg px-4 py-1.5" href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i data-lucide="power" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                            Logout
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
