<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ __('general.app_description') }}">
    <meta name="keywords" content="{{ __('general.app_keywords') }}">
    <meta name="author" content="{{ config('app.name') }}">

    <link href="https://fonts.googleapis.com/" rel="preconnect">
    <link href="https://fonts.gstatic.com/" rel="preconnect" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">

    <title>@yield('title', __('general.visitor_dashboard')) - {{ config('app.name') }}</title>

    <!-- CSS Assets -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/vendors/vector-map.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/vendors/tiny-slider.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/themes/rubick/side-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/vendors/simplebar.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/app.css') }}">

    @stack('styles')
</head>
<body>
    <!-- Page Loader -->
    <div class="page-loader bg-background fixed inset-0 z-[100] flex items-center justify-center transition-opacity">
        <div class="loader-spinner !w-14"></div>
    </div>

    <div class="rubick min-h-screen dark:bg-background before:bg-primary dark:before:bg-foreground/[.01] before:fixed before:inset-0 before:bg-noise after:bg-accent after:bg-contain after:fixed after:inset-0 after:blur-xl dark:after:opacity-20">

        <!-- Visitor Sidebar -->
        @include('visitor.partials.sidebar')

        <!-- Main Content Area -->
        <div class="content h-screen transition-[margin,width] duration-200 pt-8 pb-12 px-7 z-10 relative before:absolute before:inset-y-4 before:-ml-px before:right-4 before:opacity-[.07] before:left-4 xl:before:left-0 before:bg-foreground before:rounded-4xl after:absolute after:inset-y-4 after:-ml-px after:right-4 after:left-4 xl:after:left-0 after:bg-[color-mix(in_oklch,_var(--color-background),_var(--color-foreground)_2%)] after:rounded-4xl after:border after:border-foreground/[.15] dark:after:opacity-[.59] xl:ml-[275px] [&.content--compact]:xl:ml-[110px]">
            <div class="h-full overflow-x-hidden">
                <div class="content__scroll-area relative z-20 -mr-7 h-full overflow-y-auto pl-4 pr-11 transition-[margin] duration-200 xl:pl-0">

                    <!-- Top Bar -->
                    @include('visitor.partials.topbar')

                    <!-- Content -->
                    <div class="mt-8">
                        @if (session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (session('info'))
                            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
                                {{ session('info') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Language Switcher Modal -->
    @include('visitor.partials.language-modal')

    <!-- JavaScript Assets -->
    <script src="{{ asset('backend/assets/js/vendors/dom.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vendors/simplebar.js') }}"></script>
    <script src="{{ asset('backend/assets/js/vendors/lucide.js') }}"></script>
    <script src="{{ asset('backend/assets/js/components/base.js') }}"></script>
    <script src="{{ asset('backend/assets/js/rubick.js') }}"></script>

    @stack('scripts')

    <script>
        // CSRF token for Ajax requests
        window.csrfToken = '{{ csrf_token() }}';

        // Current locale
        window.currentLocale = '{{ app()->getLocale() }}';
    </script>
</body>
</html>
