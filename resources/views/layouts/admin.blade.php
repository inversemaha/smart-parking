<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('backend/assets/images/logo.svg') }}" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Parking Management System - Modern Admin Dashboard with Tailwind CSS">
    <meta name="keywords" content="parking, admin, dashboard, management, system, tailwind">
    <meta name="author" content="Parking Management System">

    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <title>@yield('title', 'Dashboard') - Parking Management System</title>

    <!-- BEGIN: CSS Assets -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dark-mode.css') }}">
    <!-- END: CSS Assets -->

    <script>
        // Initialize dark mode preference on page load
        (function () {
            const mode = localStorage.getItem('darkMode');
            const isSystemDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const shouldDark = mode === 'active' || (mode === 'system' && isSystemDark);
            document.documentElement.classList.toggle('dark', shouldDark);
        })();
    </script>

    @stack('styles')
</head>
<body class="py-5">
    <!-- BEGIN: Mobile Menu -->
    <div class="mobile-menu md:hidden">
        <div class="mobile-menu-bar">
            <a href="{{ route('admin.dashboard.index') }}" class="flex mr-auto">
                <img alt="Parking Management System" class="w-6" src="{{ asset('backend/assets/images/logo.svg') }}">
            </a>
            <a href="javascript:;" class="mobile-menu-toggler">
                <i data-lucide="bar-chart-2" class="w-8 h-8 text-white transform -rotate-90"></i>
            </a>
        </div>
        <div class="scrollable">
            <a href="javascript:;" class="mobile-menu-toggler">
                <i data-lucide="x-circle" class="w-8 h-8 text-white transform -rotate-90"></i>
            </a>
            <ul class="scrollable__content py-2">
                <li>
                    <a href="{{ route('admin.dashboard.index') }}" class="menu {{ request()->routeIs('admin.dashboard*') ? 'menu--active' : '' }}">
                        <div class="menu__icon">
                            <i data-lucide="home"></i>
                        </div>
                        <div class="menu__title">Dashboard</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.vehicles.pending') }}" class="menu {{ request()->routeIs('admin.vehicles*') ? 'menu--active' : '' }}">
                        <div class="menu__icon">
                            <i data-lucide="car"></i>
                        </div>
                        <div class="menu__title">Vehicles</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.reports.index') }}" class="menu {{ request()->routeIs('admin.reports*') ? 'menu--active' : '' }}">
                        <div class="menu__icon">
                            <i data-lucide="chart-column"></i>
                        </div>
                        <div class="menu__title">Reports</div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- END: Mobile Menu -->

    <div class="flex mt-[4.7rem] md:mt-0">
        <!-- BEGIN: Side Menu -->
        @include('partials.admin.sidebar')
        <!-- END: Side Menu -->

        <!-- BEGIN: Content -->
        <div class="content">
            <!-- BEGIN: Top Bar -->
            @include('partials.admin.header')
            <!-- END: Top Bar -->

            <!-- Page Content -->
            <div class="@yield('content-class', '')">
                @yield('content')
            </div>

            <!-- Footer -->
            @include('partials.admin.footer')
        </div>
        <!-- END: Content -->
    </div>

    <!-- BEGIN: Dark Mode Switcher -->
    <div class="dark-mode-switcher cursor-pointer shadow-md fixed bottom-0 right-0 box border rounded-full w-40 h-12 flex items-center justify-center z-50 mb-10 mr-10 bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 hover:shadow-lg transition-all" role="switch" aria-checked="false" aria-label="Toggle dark mode" onclick="return false;">
        <div class="mr-4 text-slate-600 dark:text-slate-300 font-medium text-sm select-none pointer-events-none">Dark Mode</div>
        <div class="dark-mode-switcher__toggle w-12 h-7 bg-slate-300 dark:bg-blue-600 rounded-full relative transition-colors duration-300 pointer-events-none">
            <div class="absolute top-1 left-1 w-5 h-5 bg-white rounded-full shadow transition-all duration-300 dark-mode-switcher__toggle--active:left-6"></div>
        </div>
    </div>
    <!-- END: Dark Mode Switcher -->

    <!-- BEGIN: JS Assets -->
    <script src="{{ asset('backend/assets/js/dark-mode.js') }}"></script>
    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyArMKJpScpQjwqf26N3I9jIwn6bRJspCiU&libraries=places"></script>
    <script src="{{ asset('backend/assets/js/app.js') }}"></script>
    <!-- END: JS Assets -->

    @stack('scripts')
</body>
</html>