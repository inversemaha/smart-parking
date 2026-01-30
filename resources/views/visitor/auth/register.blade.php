@extends('layouts.visitor')

@section('title', __('auth.register'))

@section('content')
<div class="flex min-h-screen">
    <!-- Left Side - Register Form -->
    <div class="flex flex-col justify-center w-full max-w-md mx-auto px-8 py-12">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ __('auth.create_account') }}
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                {{ __('auth.register_description') }}
            </p>
        </div>

        <form method="POST" action="{{ route('visitor.register.store') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('user.name') }}
                </label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary @error('name') border-red-500 @enderror"
                    placeholder="{{ __('user.enter_name') }}"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('user.email') }}
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary @error('email') border-red-500 @enderror"
                    placeholder="{{ __('user.enter_email') }}"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mobile -->
            <div>
                <label for="mobile" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('user.mobile') }}
                </label>
                <input
                    id="mobile"
                    name="mobile"
                    type="tel"
                    value="{{ old('mobile') }}"
                    required
                    pattern="01[3-9]\d{8}"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary @error('mobile') border-red-500 @enderror"
                    placeholder="{{ __('user.enter_mobile') }}"
                >
                <p class="mt-1 text-xs text-gray-500">{{ __('user.mobile_format') }}</p>
                @error('mobile')
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
                <p class="mt-1 text-xs text-gray-500">{{ __('auth.password_requirements') }}</p>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('auth.confirm_password') }}
                </label>
                <div class="mt-1 relative">
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        placeholder="{{ __('auth.confirm_password') }}"
                    >
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePasswordVisibility('password_confirmation')">
                        <i id="password_confirmation-eye" data-lucide="eye" class="size-4 text-gray-400"></i>
                    </button>
                </div>
            </div>

            <!-- Terms Agreement -->
            <div class="flex items-start">
                <input
                    id="terms"
                    name="terms"
                    type="checkbox"
                    required
                    class="h-4 w-4 mt-1 text-primary focus:ring-primary border-gray-300 rounded @error('terms') border-red-500 @enderror"
                >
                <label for="terms" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                    {{ __('auth.agree_to') }}
                    <a href="#" class="text-primary hover:text-primary/80">{{ __('general.terms_of_service') }}</a>
                    {{ __('general.and') }}
                    <a href="#" class="text-primary hover:text-primary/80">{{ __('general.privacy_policy') }}</a>
                </label>
                @error('terms')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
            >
                {{ __('auth.register') }}
            </button>
        </form>

        <!-- Login Link -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('auth.already_have_account') }}
                <a href="{{ route('visitor.login') }}" class="font-medium text-primary hover:text-primary/80">
                    {{ __('auth.login_now') }}
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

    <!-- Right Side - Benefits -->
    <div class="hidden lg:flex lg:flex-1 lg:bg-gradient-to-br lg:from-green-500 lg:to-blue-600">
        <div class="flex flex-col justify-center items-center p-12 text-white">
            <div class="text-center mb-12">
                <div class="text-6xl mb-6">🎯</div>
                <h2 class="text-3xl font-bold mb-4">{{ __('auth.join_thousands') }}</h2>
                <p class="text-lg opacity-90 leading-relaxed">
                    {{ __('auth.register_benefits') }}
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 w-full max-w-sm">
                <div class="flex items-start space-x-4">
                    <div class="bg-white/20 rounded-full p-3 mt-1">
                        <i data-lucide="clock" class="size-5"></i>
                    </div>
                    <div>
                        <div class="font-semibold text-lg">{{ __('general.instant_booking') }}</div>
                        <div class="text-sm opacity-80">{{ __('general.book_parking_instantly') }}</div>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="bg-white/20 rounded-full p-3 mt-1">
                        <i data-lucide="shield-check" class="size-5"></i>
                    </div>
                    <div>
                        <div class="font-semibold text-lg">{{ __('general.secure_reliable') }}</div>
                        <div class="text-sm opacity-80">{{ __('general.guaranteed_parking_spots') }}</div>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="bg-white/20 rounded-full p-3 mt-1">
                        <i data-lucide="wallet" class="size-5"></i>
                    </div>
                    <div>
                        <div class="font-semibold text-lg">{{ __('general.competitive_prices') }}</div>
                        <div class="text-sm opacity-80">{{ __('general.best_rates_guaranteed') }}</div>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="bg-white/20 rounded-full p-3 mt-1">
                        <i data-lucide="headphones" class="size-5"></i>
                    </div>
                    <div>
                        <div class="font-semibold text-lg">{{ __('general.support') }}</div>
                        <div class="text-sm opacity-80">{{ __('general.help_when_needed') }}</div>
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

// Mobile number formatting
document.getElementById('mobile').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 11) {
        value = value.slice(0, 11);
    }
    e.target.value = value;
});
</script>
@endpush
@endsection
