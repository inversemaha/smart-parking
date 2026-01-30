<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Parking Management System - Modern Admin Dashboard">
    <meta name="keywords" content="parking, admin, dashboard, management, system">
    <meta name="author" content="Parking Management System">

    <link href="https://fonts.googleapis.com/" rel="preconnect">
    <link href="https://fonts.gstatic.com/" rel="preconnect" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">

    <title>@yield('title', 'Dashboard') - Parking Management System</title>

    <!-- BEGIN: CSS Assets-->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/vendors/vector-map.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/vendors/tiny-slider.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/themes/rubick/side-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/vendors/simplebar.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/app.css') }}">
    <!-- END: CSS Assets-->

    @stack('styles')
</head>
<body>
    <div>
        <!-- Theme Settings Modal -->
        <div data-tw-toggle="modal" data-tw-target="#settings-dialog" class="fixed inset-y-0 right-0 z-50 my-auto flex hover:w-20 transition-all w-14 h-12 cursor-pointer border-(--color)/50 items-center border justify-center rounded-l-full shadow-lg overflow-hidden bg-background/80 [--color:var(--color-primary)] before:bg-(--color)/20 before:absolute before:inset-0">
            <i data-lucide="settings" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 animate-spin"></i>
        </div>

        @include('partials.theme-settings')
    </div>

    <!-- Page Loader -->
    <div class="page-loader bg-background fixed inset-0 z-[100] flex items-center justify-center transition-opacity">
        <div class="loader-spinner !w-14"></div>
    </div>

    <div class="rubick min-h-screen dark:bg-background before:bg-primary dark:before:bg-foreground/[.01] before:fixed before:inset-0 before:bg-noise after:bg-accent after:bg-contain after:fixed after:inset-0 after:blur-xl dark:after:opacity-20">

        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Main Content Area -->
        <div class="content h-screen transition-[margin,width] duration-200 pt-8 pb-12 px-7 z-10 relative before:absolute before:inset-y-4 before:-ml-px before:right-4 before:opacity-[.07] before:left-4 xl:before:left-0 before:bg-foreground before:rounded-4xl after:absolute after:inset-y-4 after:-ml-px after:right-4 after:left-4 xl:after:left-0 after:bg-[color-mix(in_oklch,_var(--color-background),_var(--color-foreground)_2%)] after:rounded-4xl after:border after:border-foreground/[.15] dark:after:opacity-[.59] xl:ml-[275px] [&.content--compact]:xl:ml-[110px]">
            <div class="h-full overflow-x-hidden">
                <div class="content__scroll-area relative z-20 -mr-7 h-full overflow-y-auto pl-4 pr-11 transition-[margin] duration-200 xl:pl-0">

                    <!-- Header -->
                    @include('partials.header')

                    <!-- Page Content -->
                    <div class="@yield('content-class', '')">
                        @yield('content')
                    </div>

                    <!-- Footer -->
                    @include('partials.footer')

                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN: Vendor JS Assets-->
    <script src="{{ asset('backend/assets/js/vendors/dom.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vendors/lucide.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vendors/tippy.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vendors/dayjs.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vendors/easepick.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vendors/popper.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vendors/dropdown.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vendors/chartjs.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vendors/vector-map.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vendors/tiny-slider.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vendors/modal.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vendors/simplebar.js') }}"></script>
    <script src="{{ asset('backend/assets/js/pages/dashboard-overview-1.js') }}"></script>
    <script src="{{ asset('backend/assets/js/components/base/page-loader.js') }}"></script>
    <script src="{{ asset('backend/assets/js/components/base/lucide.js') }}"></script>
    <script src="{{ asset('backend/assets/js/components/base/tippy.js') }}"></script>
    <script src="{{ asset('backend/assets/js/components/base/easepick.js') }}"></script>
    <script src="{{ asset('backend/assets/js/components/report-line-chart.js') }}"></script>
    <script src="{{ asset('backend/assets/js/components/report-pie-chart.js') }}"></script>
    <script src="{{ asset('backend/assets/js/components/report-donut-chart.js') }}"></script>
    <script src="{{ asset('backend/assets/js/components/vector-map.js') }}"></script>
    <script src="{{ asset('backend/assets/js/components/report-donut-chart-1.js') }}"></script>
    <script src="{{ asset('backend/assets/js/components/simple-line-chart-1.js') }}"></script>
    <script src="{{ asset('backend/assets/js/components/base/tiny-slider.js') }}"></script>
    <script src="{{ asset('backend/assets/js/themes/rubick.js') }}"></script>
    <script src="{{ asset('backend/assets/js/utils/helper.js') }}"></script>
    <script src="{{ asset('backend/assets/js/components/theme-switcher.js') }}"></script>
    <!-- END: Vendor JS Assets-->

    @stack('scripts')
</body>
</html>
