<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Smart Parking System') }} @hasSection('title') - @yield('title') @endif</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('dashboard.index') }}" class="flex items-center">
                            <h1 class="text-xl font-bold text-blue-600">{{ config('app.name') }}</h1>
                        </a>

                        @auth
                        <!-- Main Navigation -->
                        <div class="hidden md:flex items-center ml-10 space-x-8">
                            <a href="{{ route('dashboard.index') }}" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium {{ request()->routeIs('dashboard.*') ? 'border-b-2 border-blue-600' : '' }}">
                                {{ __('general.dashboard') }}
                            </a>

                            @can('accessBasic', auth()->user())
                            <a href="{{ route('vehicles.index') }}" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium {{ request()->routeIs('vehicles.*') ? 'border-b-2 border-blue-600' : '' }}">
                                {{ __('vehicles.vehicles') }}
                            </a>

                            <a href="{{ route('bookings.index') }}" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium {{ request()->routeIs('bookings.*') ? 'border-b-2 border-blue-600' : '' }}">
                                {{ __('booking.bookings') }}
                            </a>
                            @endcan

                            @can('accessAccountant', auth()->user())
                            <a href="{{ route('payments.index') }}" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium {{ request()->routeIs('payments.*') ? 'border-b-2 border-blue-600' : '' }}">
                                {{ __('payment.payments') }}
                            </a>
                            @endcan

                            @can('accessAdmin', auth()->user())
                            <a href="{{ route('admin.dashboard.index') }}" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium">
                                {{ __('admin.admin_panel') }}
                            </a>
                            @endcan
                        </div>
                        @endauth
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Language Switcher -->
                        <div class="flex items-center space-x-2">
                            <form method="POST" action="{{ route('language.switch', 'en') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 text-sm {{ app()->getLocale() == 'en' ? 'bg-blue-100 text-blue-800' : 'text-gray-600 hover:text-blue-600' }} rounded-md transition-colors">
                                    English
                                </button>
                            </form>
                            <span class="text-gray-300">|</span>
                            <form method="POST" action="{{ route('language.switch', 'bn') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 text-sm {{ app()->getLocale() == 'bn' ? 'bg-blue-100 text-blue-800' : 'text-gray-600 hover:text-blue-600' }} rounded-md transition-colors">
                                    বাংলা
                                </button>
                            </form>
                        </div>

                        @auth
                            <!-- User Menu -->
                            <div class="relative">
                                <div class="flex items-center space-x-4">
                                    <a href="{{ route('profile.index') }}" class="text-sm text-gray-700 hover:text-blue-600">
                                        {{ Auth::user()->name }}
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-gray-500 hover:text-gray-700 text-sm">
                                            {{ __('auth.logout') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">{{ __('auth.login') }}</a>
                            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition-colors">{{ __('auth.register') }}</a>
                        @endauth
                    </div>
                </div>
            </div>

            @auth
            <!-- Mobile Navigation Menu -->
            <div class="md:hidden">
                <div class="px-2 pt-2 pb-3 space-y-1 bg-gray-50">
                    <a href="{{ route('dashboard.index') }}" class="block px-3 py-2 text-base font-medium text-gray-900 hover:text-blue-600 {{ request()->routeIs('dashboard.*') ? 'bg-blue-50' : '' }}">
                        {{ __('general.dashboard') }}
                    </a>

                    @can('accessBasic', auth()->user())
                    <a href="{{ route('vehicles.index') }}" class="block px-3 py-2 text-base font-medium text-gray-900 hover:text-blue-600 {{ request()->routeIs('vehicles.*') ? 'bg-blue-50' : '' }}">
                        {{ __('vehicles.vehicles') }}
                    </a>

                    <a href="{{ route('bookings.index') }}" class="block px-3 py-2 text-base font-medium text-gray-900 hover:text-blue-600 {{ request()->routeIs('bookings.*') ? 'bg-blue-50' : '' }}">
                        {{ __('booking.bookings') }}
                    </a>
                    @endcan

                    @can('accessAccountant', auth()->user())
                    <a href="{{ route('payments.index') }}" class="block px-3 py-2 text-base font-medium text-gray-900 hover:text-blue-600 {{ request()->routeIs('payments.*') ? 'bg-blue-50' : '' }}">
                        {{ __('payment.payments') }}
                    </a>
                    @endcan

                    @can('accessAdmin', auth()->user())
                    <a href="{{ route('admin.dashboard.index') }}" class="block px-3 py-2 text-base font-medium text-gray-900 hover:text-blue-600">
                        {{ __('admin.admin_panel') }}
                    </a>
                    @endcan
                </div>
            </div>
            @endauth
        </nav>

        <!-- Flash Messages -->
        <div id="flash-messages" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-md mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-md mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white shadow mt-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('general.all_rights_reserved') }}.
                    </p>
                    <div class="flex items-center space-x-6">
                        @auth
                        <span class="text-xs text-gray-400">
                            {{ __('general.logged_in_as') }}:
                            <span class="font-medium text-gray-600">{{ auth()->user()->name }}</span>
                            @if(auth()->user()->roles->isNotEmpty())
                                ({{ auth()->user()->roles->pluck('display_name')->join(', ') }})
                            @endif
                        </span>
                        @endauth
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Auto-hide flash messages -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const flashMessages = document.getElementById('flash-messages');
            if (flashMessages && flashMessages.children.length > 0) {
                setTimeout(function() {
                    flashMessages.style.transition = 'opacity 0.5s ease-out';
                    flashMessages.style.opacity = '0';
                    setTimeout(function() {
                        flashMessages.style.display = 'none';
                    }, 500);
                }, 5000);
            }
        });
    </script>
</body>
</html>
