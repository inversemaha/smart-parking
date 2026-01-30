@extends('layouts.app')

@section('title', __('Register'))

@push('styles')
<style>
    .auth-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }

    .auth-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
    }

    .dark .auth-card {
        background: rgba(30, 30, 30, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
</style>
@endpush

@section('content')
<div class="auth-page flex items-center justify-center p-6">
    <div class="auth-card w-full max-w-md p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                {{ __('Create Account') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                {{ __('Join our parking management system') }}
            </p>
        </div>

        <!-- Language Toggle -->
        <div class="flex justify-center mb-6">
            <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-1 flex">
                <form action="{{ route('language.switch', 'en') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1 rounded-md text-sm font-medium transition-colors {{ app()->getLocale() === 'en' ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                        English
                    </button>
                </form>
                <form action="{{ route('language.switch', 'bn') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1 rounded-md text-sm font-medium transition-colors {{ app()->getLocale() === 'bn' ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                        বাংলা
                    </button>
                </form>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                @foreach ($errors->all() as $error)
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Register Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Full Name') }}
                </label>
                <input id="name" name="name" type="text" required autofocus autocomplete="name"
                       value="{{ old('name') }}"
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white transition-colors @error('name') border-red-500 @enderror">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Email Address') }}
                </label>
                <input id="email" name="email" type="email" required autocomplete="username"
                       value="{{ old('email') }}"
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white transition-colors @error('email') border-red-500 @enderror">
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Phone Number') }}
                </label>
                <input id="phone" name="phone" type="tel" required autocomplete="tel"
                       value="{{ old('phone') }}"
                       placeholder="+8801XXXXXXXXX"
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white transition-colors @error('phone') border-red-500 @enderror">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Password') }}
                </label>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white transition-colors @error('password') border-red-500 @enderror">
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Confirm Password') }}
                </label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white transition-colors">
            </div>

            <!-- Terms and Privacy -->
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input id="terms" name="terms" type="checkbox" required
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                </div>
                <div class="ml-3 text-sm">
                    <label for="terms" class="text-gray-700 dark:text-gray-300">
                        {{ __('I agree to the') }}
                        <a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-500 font-medium">
                            {{ __('Terms of Service') }}
                        </a>
                        {{ __('and') }}
                        <a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-500 font-medium">
                            {{ __('Privacy Policy') }}
                        </a>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                {{ __('Create Account') }}
            </button>
        </form>

        <!-- Login Link -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('Already have an account?') }}
                <a href="{{ route('login') }}"
                   class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500">
                    {{ __('Sign in') }}
                </a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Password strength indicator
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const strength = checkPasswordStrength(password);

        // You can add password strength indicator here
    });

    function checkPasswordStrength(password) {
        let score = 0;
        if (password.length >= 8) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[a-z]/.test(password)) score++;
        if (/\d/.test(password)) score++;
        if (/[^A-Za-z\d]/.test(password)) score++;

        return score;
    }
</script>
@endpush
