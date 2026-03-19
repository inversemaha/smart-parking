@php
    $isAuthenticated = auth()->check();
    $isAdmin = $isAuthenticated && auth()->user()->hasAnyRole(['admin', 'super-admin', 'manager']);
    $safeRoute = function (string $name, array $params = []) {
        return \Illuminate\Support\Facades\Route::has($name) ? route($name, $params) : '#';
    };

    $visitorDashboardRoute = \Illuminate\Support\Facades\Route::has('visitor.dashboard') ? 'visitor.dashboard' : 'dashboard.index';
    $visitorVehiclesRoute = \Illuminate\Support\Facades\Route::has('visitor.vehicles.index') ? 'visitor.vehicles.index' : 'vehicles.index';
    $visitorBookingsRoute = \Illuminate\Support\Facades\Route::has('visitor.bookings.index') ? 'visitor.bookings.index' : 'bookings.index';
    $visitorPaymentsRoute = \Illuminate\Support\Facades\Route::has('visitor.payments.index') ? 'visitor.payments.index' : 'payments.index';
    $visitorProfileRoute = \Illuminate\Support\Facades\Route::has('visitor.profile.index') ? 'visitor.profile.index' : 'profile.index';
@endphp

<div class="side-menu side-menu--rubick group bg-background/80 dark:bg-foreground/[.01] border-transparent relative inset-y-0 left-0 z-30 max-h-screen w-[275px] min-w-0 border-r duration-300 ease-in-out transition-[width,margin] xl:translate-x-0 xl:z-auto fixed xl:static -translate-x-full [&.side-menu--collapsed]:xl:w-[110px] [&.side-menu--collapsed]:xl:[&.side-menu--on-hover]:w-[275px]">
    <div class="flex h-[70px] items-center border-b border-foreground/[.15] z-10 relative px-7">
        <a href="{{ $isAdmin ? $safeRoute('admin.dashboard.index') : $safeRoute($visitorDashboardRoute) }}" class="flex items-center gap-3">
            <div class="bg-primary/20 flex h-8 w-8 items-center justify-center rounded-lg">
                <i data-lucide="car" class="h-4 w-4 fill-primary/20 stroke-primary"></i>
            </div>
            <div class="side-menu__content">
                <div class="text-lg font-semibold">Smart Parking</div>
                <div class="text-xs opacity-70">{{ $isAdmin ? 'Admin Panel' : 'Visitor Panel' }}</div>
            </div>
        </a>
    </div>

    <nav class="scrollbar-hidden side-menu__content overflow-y-auto h-full py-5">
        @if($isAdmin)
            <div class="side-menu__item">
                <a href="{{ $safeRoute('admin.dashboard.index') }}" class="side-menu__link {{ request()->routeIs('admin.dashboard*') ? 'side-menu__link--active' : '' }}">
                    <i data-lucide="layout-dashboard" class="side-menu__icon"></i>
                    <div class="side-menu__title">Dashboard</div>
                </a>
            </div>

            <div class="side-menu__item">
                <a href="{{ $safeRoute('admin.permissions.index') }}" class="side-menu__link {{ request()->routeIs('admin.permissions*') ? 'side-menu__link--active' : '' }}">
                    <i data-lucide="shield" class="side-menu__icon"></i>
                    <div class="side-menu__title">Permissions</div>
                </a>
            </div>

            <div class="side-menu__item">
                <a href="{{ $safeRoute('admin.permissions.roles') }}" class="side-menu__link {{ request()->routeIs('admin.permissions.roles*', 'admin.roles*') ? 'side-menu__link--active' : '' }}">
                    <i data-lucide="key-round" class="side-menu__icon"></i>
                    <div class="side-menu__title">Roles</div>
                </a>
            </div>

            <div class="side-menu__item">
                <a href="{{ $safeRoute('admin.vehicles.pending') }}" class="side-menu__link {{ request()->routeIs('admin.vehicles*') ? 'side-menu__link--active' : '' }}">
                    <i data-lucide="car" class="side-menu__icon"></i>
                    <div class="side-menu__title">Vehicles</div>
                </a>
            </div>

            <div class="side-menu__item">
                <a href="{{ $safeRoute('admin.reports.index') }}" class="side-menu__link {{ request()->routeIs('admin.reports*') ? 'side-menu__link--active' : '' }}">
                    <i data-lucide="chart-column" class="side-menu__icon"></i>
                    <div class="side-menu__title">Reports</div>
                </a>
            </div>

            <div class="side-menu__item">
                <a href="{{ $safeRoute('admin.system.health') }}" class="side-menu__link {{ request()->routeIs('admin.system*') ? 'side-menu__link--active' : '' }}">
                    <i data-lucide="activity" class="side-menu__icon"></i>
                    <div class="side-menu__title">System</div>
                </a>
            </div>
        @else
            <div class="side-menu__item">
                <a href="{{ $safeRoute($visitorDashboardRoute) }}" class="side-menu__link {{ request()->routeIs('visitor.dashboard', 'dashboard.*', 'user.dashboard') ? 'side-menu__link--active' : '' }}">
                    <i data-lucide="layout-dashboard" class="side-menu__icon"></i>
                    <div class="side-menu__title">Dashboard</div>
                </a>
            </div>

            <div class="side-menu__item">
                <a href="{{ $safeRoute($visitorVehiclesRoute) }}" class="side-menu__link {{ request()->routeIs('visitor.vehicles.*', 'vehicles.*') ? 'side-menu__link--active' : '' }}">
                    <i data-lucide="car" class="side-menu__icon"></i>
                    <div class="side-menu__title">Vehicles</div>
                </a>
            </div>

            <div class="side-menu__item">
                <a href="{{ $safeRoute($visitorBookingsRoute) }}" class="side-menu__link {{ request()->routeIs('visitor.bookings.*', 'bookings.*') ? 'side-menu__link--active' : '' }}">
                    <i data-lucide="calendar-check" class="side-menu__icon"></i>
                    <div class="side-menu__title">Bookings</div>
                </a>
            </div>

            <div class="side-menu__item">
                <a href="{{ $safeRoute($visitorPaymentsRoute) }}" class="side-menu__link {{ request()->routeIs('visitor.payments.*', 'payments.*') ? 'side-menu__link--active' : '' }}">
                    <i data-lucide="credit-card" class="side-menu__icon"></i>
                    <div class="side-menu__title">Payments</div>
                </a>
            </div>

            <div class="side-menu__item">
                <a href="{{ $safeRoute($visitorProfileRoute) }}" class="side-menu__link {{ request()->routeIs('visitor.profile.*', 'profile.*') ? 'side-menu__link--active' : '' }}">
                    <i data-lucide="user-round" class="side-menu__icon"></i>
                    <div class="side-menu__title">Profile</div>
                </a>
            </div>
        @endif
    </nav>

    <div class="absolute inset-x-0 bottom-0 mx-4 mb-8">
        <div class="bg-background/10 border-background/20 dark:bg-foreground/[.02] dark:border-foreground/[.09] flex items-center rounded-full border p-2.5 opacity-80 backdrop-blur-2xl">
            <div class="border-background/20 dark:border-foreground/20 relative h-10 w-10 flex-none overflow-hidden rounded-full border-4">
                <img class="absolute top-0 h-full w-full object-cover" src="{{ asset('backend/ui/images/fakers/profile-11.jpg') }}" alt="User Profile">
            </div>
            <div class="ms-3 overflow-hidden">
                <div class="w-full truncate font-medium">{{ $isAuthenticated ? auth()->user()->name : 'Guest User' }}</div>
                <div class="w-full truncate text-xs opacity-60">{{ $isAdmin ? 'Administrator' : 'User' }}</div>
            </div>
        </div>
    </div>
</div>
