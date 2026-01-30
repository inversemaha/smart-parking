<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Smart Parking System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col">
    <!-- Header with Navigation -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-blue-600">{{ config('app.name', 'Smart Parking') }}</h1>
                </div>

                <!-- Navigation & Language Switcher -->
                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <div class="relative">
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
                    </div>

                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                {{ __('general.dashboard') }}
                            </a>
                        @else
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">
                                    {{ __('auth.login') }}
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                        {{ __('auth.register') }}
                                    </a>
                                @endif
                            </div>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Hero Section -->
            <div class="space-y-8">
                <div class="space-y-4">
                    <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
                        {{ __('parking.welcome_title') }}
                    </h2>
                    <p class="text-xl sm:text-2xl text-gray-600 max-w-2xl mx-auto">
                        {{ __('parking.welcome_description') }}
                    </p>
                </div>

                <!-- Features Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mt-16">
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('parking.feature_booking') }}</h3>
                        <p class="text-gray-600 text-sm">{{ __('parking.feature_booking_desc') }}</p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('parking.feature_security') }}</h3>
                        <p class="text-gray-600 text-sm">{{ __('parking.feature_security_desc') }}</p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('parking.feature_payment') }}</h3>
                        <p class="text-gray-600 text-sm">{{ __('parking.feature_payment_desc') }}</p>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center mt-12">
                    @auth
                        <a href="{{ route('dashboard.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-lg font-medium transition-colors">
                            {{ __('parking.go_to_dashboard') }}
                        </a>
                        <a href="{{ route('bookings.create') }}" class="bg-white hover:bg-gray-50 text-blue-600 border-2 border-blue-600 px-8 py-3 rounded-lg text-lg font-medium transition-colors">
                            {{ __('parking.book_now') }}
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-lg font-medium transition-colors">
                            {{ __('parking.get_started') }}
                        </a>
                        <a href="{{ route('login') }}" class="bg-white hover:bg-gray-50 text-blue-600 border-2 border-blue-600 px-8 py-3 rounded-lg text-lg font-medium transition-colors">
                            {{ __('parking.sign_in') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">{{ __('parking.about') }}</h3>
                    <p class="mt-2 text-sm text-gray-600">{{ __('parking.footer_about') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">{{ __('parking.services') }}</h3>
                    <ul class="mt-2 space-y-1 text-sm text-gray-600">
                        <li>{{ __('parking.service_parking') }}</li>
                        <li>{{ __('parking.service_booking') }}</li>
                        <li>{{ __('parking.service_payment') }}</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">{{ __('parking.support') }}</h3>
                    <ul class="mt-2 space-y-1 text-sm text-gray-600">
                        <li>{{ __('parking.contact_us') }}</li>
                        <li>{{ __('parking.help_center') }}</li>
                        <li>{{ __('parking.faq') }}</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">{{ __('parking.legal') }}</h3>
                    <ul class="mt-2 space-y-1 text-sm text-gray-600">
                        <li>{{ __('parking.privacy_policy') }}</li>
                        <li>{{ __('parking.terms_service') }}</li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-200">
                <p class="text-center text-sm text-gray-600">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('parking.all_rights_reserved') }}
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
