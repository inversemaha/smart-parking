<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Smart Parking') }} - {{ __('general.welcome') }}</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/themes/rubick/side-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/app.css') }}">

    <style>
        .hero-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h2 class="text-xl font-bold text-gray-900">
                            {{ config('app.name') }}
                        </h2>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <div class="relative">
                        <button class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100" data-tw-toggle="modal" data-tw-target="#language-modal">
                            <i data-lucide="globe" class="size-4"></i>
                            <span class="text-sm">{{ app()->getLocale() === 'bn' ? 'বাংলা' : 'English' }}</span>
                        </button>
                    </div>

                    @guest
                        <a href="{{ route('visitor.login') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            {{ __('auth.login') }}
                        </a>
                        <a href="{{ route('visitor.register') }}" class="bg-primary text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-primary/90">
                            {{ __('auth.register') }}
                        </a>
                    @else
                        <a href="{{ route('visitor.dashboard') }}" class="bg-primary text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-primary/90">
                            {{ __('general.dashboard') }}
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-bg text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    {{ __('general.smart_parking_system') }}
                </h1>
                <p class="text-xl md:text-2xl mb-8 opacity-90">
                    {{ __('general.find_book_park_easily') }}
                </p>

                <!-- Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
                    <div class="text-center">
                        <div class="text-3xl md:text-4xl font-bold">{{ $totalLocations ?? 0 }}</div>
                        <div class="text-sm opacity-80">{{ __('parking.locations') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl md:text-4xl font-bold">{{ $totalSlots ?? 0 }}</div>
                        <div class="text-sm opacity-80">{{ __('parking.total_slots') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl md:text-4xl font-bold">{{ $availableSlots ?? 0 }}</div>
                        <div class="text-sm opacity-80">{{ __('parking.available_now') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl md:text-4xl font-bold">24/7</div>
                        <div class="text-sm opacity-80">{{ __('general.support') }}</div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <a href="{{ route('visitor.parking.locations') }}" class="bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        {{ __('parking.find_parking') }}
                    </a>
                    @guest
                        <a href="{{ route('visitor.register') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-primary transition-colors">
                            {{ __('auth.get_started') }}
                        </a>
                    @else
                        <a href="{{ route('visitor.bookings.create') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-primary transition-colors">
                            {{ __('bookings.book_now') }}
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    {{ __('general.why_choose_us') }}
                </h2>
                <p class="text-xl text-gray-600">
                    {{ __('general.features_description') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Real-time Availability -->
                <div class="feature-card bg-white p-8 rounded-xl shadow-lg border border-gray-200">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="clock" class="size-8 text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">{{ __('general.real_time_availability') }}</h3>
                    <p class="text-gray-600">{{ __('general.real_time_description') }}</p>
                </div>

                <!-- Easy Booking -->
                <div class="feature-card bg-white p-8 rounded-xl shadow-lg border border-gray-200">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="smartphone" class="size-8 text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">{{ __('general.easy_booking') }}</h3>
                    <p class="text-gray-600">{{ __('general.easy_booking_description') }}</p>
                </div>

                <!-- Secure Payment -->
                <div class="feature-card bg-white p-8 rounded-xl shadow-lg border border-gray-200">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="shield-check" class="size-8 text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">{{ __('general.secure_payment') }}</h3>
                    <p class="text-gray-600">{{ __('general.secure_payment_description') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Locations -->
    @if(isset($featuredLocations) && $featuredLocations->count() > 0)
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    {{ __('parking.featured_locations') }}
                </h2>
                <p class="text-xl text-gray-600">
                    {{ __('parking.featured_description') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredLocations as $location)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden feature-card">
                    @if($location->image)
                        <img src="{{ Storage::url($location->image) }}" alt="{{ $location->name }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                            <i data-lucide="map-pin" class="size-12 text-white"></i>
                        </div>
                    @endif

                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $location->name }}</h3>
                        <p class="text-gray-600 mb-4">{{ $location->address }}</p>

                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-4">
                                <div class="text-center">
                                    <div class="text-lg font-bold text-green-600">{{ $location->available_slots_count ?? 0 }}</div>
                                    <div class="text-xs text-gray-500">{{ __('parking.available') }}</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-bold text-blue-600">৳{{ $location->hourly_rate ?? 0 }}</div>
                                    <div class="text-xs text-gray-500">{{ __('parking.per_hour') }}</div>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('visitor.parking.location.details', $location) }}" class="w-full bg-primary text-white text-center py-2 rounded-lg hover:bg-primary/90 transition-colors">
                            {{ __('general.view_details') }}
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('visitor.parking.locations') }}" class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary/90 transition-colors">
                    {{ __('parking.view_all_locations') }}
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    <section class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">
                {{ __('general.ready_to_start') }}
            </h2>
            <p class="text-xl mb-8 opacity-90">
                {{ __('general.cta_description') }}
            </p>

            @guest
                <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <a href="{{ route('visitor.register') }}" class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary/90 transition-colors">
                        {{ __('auth.register_free') }}
                    </a>
                    <a href="{{ route('visitor.parking.locations') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-gray-900 transition-colors">
                        {{ __('parking.browse_locations') }}
                    </a>
                </div>
            @else
                <a href="{{ route('visitor.bookings.create') }}" class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary/90 transition-colors">
                    {{ __('bookings.start_booking') }}
                </a>
            @endguest
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-white font-semibold mb-4">{{ config('app.name') }}</h3>
                    <p class="text-sm">{{ __('general.app_description') }}</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">{{ __('general.quick_links') }}</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('visitor.parking.locations') }}" class="hover:text-white">{{ __('parking.find_parking') }}</a></li>
                        <li><a href="{{ route('visitor.register') }}" class="hover:text-white">{{ __('auth.register') }}</a></li>
                        <li><a href="{{ route('visitor.login') }}" class="hover:text-white">{{ __('auth.login') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">{{ __('general.support') }}</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white">{{ __('general.help_center') }}</a></li>
                        <li><a href="#" class="hover:text-white">{{ __('general.contact_us') }}</a></li>
                        <li><a href="#" class="hover:text-white">{{ __('general.faq') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">{{ __('general.legal') }}</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white">{{ __('general.privacy_policy') }}</a></li>
                        <li><a href="#" class="hover:text-white">{{ __('general.terms_of_service') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('general.all_rights_reserved') }}</p>
            </div>
        </div>
    </footer>

    <!-- Language Modal -->
    @include('visitor.partials.language-modal')

    <!-- JavaScript -->
    <script src="{{ asset('backend/assets/js/vendors/lucide.js') }}"></script>
    <script src="{{ asset('backend/assets/js/components/base.js') }}"></script>
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>
