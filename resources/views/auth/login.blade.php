@extends('layouts.auth')

@section('title', __('auth.login'))

@section('content')
<!-- Login Card -->
<div class="box p-8 shadow-xl">
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-foreground">{{ __('auth.login') }}</h2>
        <p class="text-slate-500 text-sm mt-2">{{ __('auth.enter_credentials') }}</p>
    </div>

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Input -->
        <div>
            <label for="email" class="block text-sm font-medium text-foreground mb-2">
                {{ __('auth.email') }}
            </label>
            <div class="relative">
                <i data-lucide="mail" class="absolute left-3 top-3 w-5 h-5 text-slate-400"></i>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="form-input w-full pl-10 {{ $errors->has('email') ? 'border-danger' : '' }}"
                    placeholder="you@example.com"
                    autocomplete="email"
                >
            </div>
            @error('email')
                <p class="text-danger text-xs mt-2 flex items-center gap-1">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Password Input -->
        <div>
            <label for="password" class="block text-sm font-medium text-foreground mb-2">
                {{ __('auth.password') }}
            </label>
            <div class="relative">
                <i data-lucide="lock" class="absolute left-3 top-3 w-5 h-5 text-slate-400"></i>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    class="form-input w-full pl-10 {{ $errors->has('password') ? 'border-danger' : '' }}"
                    placeholder="Enter your password"
                    autocomplete="current-password"
                >
            </div>
            @error('password')
                <p class="text-danger text-xs mt-2 flex items-center gap-1">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center gap-2">
            <input 
                type="checkbox" 
                id="remember" 
                name="remember" 
                {{ old('remember') ? 'checked' : '' }}
                class="w-4 h-4 rounded"
            >
            <label for="remember" class="text-sm text-slate-600">
                {{ __('auth.remember_me') }}
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-full mt-6">
            <i data-lucide="log-in" class="w-4 h-4 mr-2"></i>
            {{ __('auth.login') }}
        </button>
    </form>

    <!-- Divider -->
    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-slate-300 dark:border-slate-600"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-3 bg-background dark:bg-foreground/[.01] text-slate-500">{{ __('general.or') }}</span>
        </div>
    </div>

    <!-- Links Section -->
    <div class="space-y-3">
        <!-- Forgot Password Link -->
        @if (Route::has('password.request'))
            <a 
                href="{{ route('password.request') }}" 
                class="flex items-center justify-center gap-2 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-900 transition-colors text-sm font-medium text-foreground"
            >
                <i data-lucide="key" class="w-4 h-4"></i>
                {{ __('auth.forgot_password') }}
            </a>
        @endif

        <!-- Register Link -->
        @if (Route::has('register'))
            <a 
                href="{{ route('register') }}" 
                class="flex items-center justify-center gap-2 py-2.5 bg-primary/10 dark:bg-primary/20 rounded-lg hover:bg-primary/20 dark:hover:bg-primary/30 transition-colors text-sm font-medium text-primary"
            >
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                {{ __('auth.create_account') }}
            </a>
        @endif
    </div>
</div>
@endsection
