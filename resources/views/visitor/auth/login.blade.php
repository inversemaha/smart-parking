@extends('layouts.visitor')

@section('title', __('auth.login'))

@section('content')
<div class="flex min-h-screen">
    <!-- Left Side - Login Form -->
    <div class="flex flex-col justify-center w-full max-w-md mx-auto px-8 py-12">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ __('auth.welcome_back') }}
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                {{ __('auth.login_description') }}
            </p>
        </div>

        <form method="POST" action="{{ route('visitor.login.store') }}" class="space-y-6">
            @csrf

            <!-- Email or Mobile -->
            <div>
                <label for="login" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('auth.email_or_mobile') }}
                </label>
                <input
                    id="login"
                    name="login"
                    type="text"
                    value="{{ old('login') }}"
                    required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary @error('login') border-red-500 @enderror"
                    placeholder="{{ __('auth.enter_email_or_mobile') }}"
                >
                @error('login')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('auth.password') }}
                </label>
                <div class="mt-1 relative">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary @error('password') border-red-500 @enderror"
                        placeholder="{{ __('auth.enter_password') }}"
                    >
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePasswordVisibility('password')">
                        <i id="password-eye" data-lucide="eye" class="size-4 text-gray-400"></i>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input
                        id="remember"
                        name="remember"
                        type="checkbox"
                        class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                    >
                    <label for="remember" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                        {{ __('auth.remember_me') }}
                    </label>
                </div>

                <div class="text-sm">
                    <a href="{{ route('visitor.password.request') }}" class="font-medium text-primary hover:text-primary/80">
                        {{ __('auth.forgot_password') }}
                    </a>
                </div>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
            >
                {{ __('auth.login') }}
            </button>
        </form>

        <!-- Social Login (Optional) -->
        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white dark:bg-gray-900 text-gray-500">{{ __('auth.or') }}</span>
                </div>
            </div>
        </div>

        <!-- Register Link -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('auth.dont_have_account') }}
                <a href="{{ route('visitor.register') }}" class="font-medium text-primary hover:text-primary/80">
                    {{ __('auth.register_now') }}
                </a>
            </p>
        </div>

        <!-- Language Switcher -->
        <div class="mt-4 text-center">
            <button
                type="button"
                class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                data-tw-toggle="modal"
                data-tw-target="#language-modal"
            >
                <i data-lucide="globe" class="inline size-4 mr-1"></i>
                {{ app()->getLocale() === 'bn' ? 'ভাষা পরিবর্তন করুন' : 'Change Language' }}
            </button>
        </div>
    </div>

    <!-- Right Side - Illustration/Info -->
    <div class="hidden lg:flex lg:flex-1 lg:bg-gradient-to-br lg:from-primary lg:to-blue-600">
        <div class="flex flex-col justify-center items-center p-12 text-white">
            <div class="text-center">
                <div class="text-6xl mb-6">🚗</div>
                <h2 class="text-3xl font-bold mb-4">{{ __('general.smart_parking') }}</h2>
                <p class="text-lg opacity-90 leading-relaxed">
                    {{ __('general.parking_description') }}
                </p>
            </div>

            <div class="mt-12 grid grid-cols-1 gap-4 w-full max-w-sm">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white/20 rounded-full p-2">
                            <i data-lucide="map-pin" class="size-5"></i>
                        </div>
                        <div>
                            <div class="font-semibold">{{ __('parking.multiple_locations') }}</div>
                            <div class="text-sm opacity-80">{{ __('parking.find_nearby') }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white/20 rounded-full p-2">
                            <i data-lucide="smartphone" class="size-5"></i>
                        </div>
                        <div>
                            <div class="font-semibold">{{ __('booking.easy_booking') }}</div>
                            <div class="text-sm opacity-80">{{ __('booking.book_in_seconds') }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white/20 rounded-full p-2">
                            <i data-lucide="credit-card" class="size-5"></i>
                        </div>
                        <div>
                            <div class="font-semibold">{{ __('payments.secure_payment') }}</div>
                            <div class="text-sm opacity-80">{{ __('payments.multiple_methods') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Language Modal -->
@include('visitor.partials.language-modal')

@push('scripts')
<script>
function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    const eye = document.getElementById(fieldId + '-eye');

    if (field.type === 'password') {
        field.type = 'text';
        eye.setAttribute('data-lucide', 'eye-off');
    } else {
        field.type = 'password';
        eye.setAttribute('data-lucide', 'eye');
    }

    // Re-initialize lucide icons
    lucide.createIcons();
}
</script>
@endpush
@endsection
