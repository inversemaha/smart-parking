@extends('layouts.auth')

@section('title', __('auth.register'))

@section('content')
<!-- Register Card -->
<div class="box p-8 shadow-xl">
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-foreground">{{ __('auth.register') }}</h2>
        <p class="text-slate-500 text-sm mt-2">{{ __('auth.create_new_account') }}</p>
    </div>

    <!-- Register Form -->
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name Input -->
        <div>
            <label for="name" class="block text-sm font-medium text-foreground mb-2">
                {{ __('auth.full_name') }}
            </label>
            <div class="relative">
                <i data-lucide="user" class="absolute left-3 top-3 w-5 h-5 text-slate-400"></i>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
                    required
                    autofocus
                    class="form-input w-full pl-10 {{ $errors->has('name') ? 'border-danger' : '' }}"
                    placeholder="John Doe"
                    autocomplete="name"
                >
            </div>
            @error('name')
                <p class="text-danger text-xs mt-2 flex items-center gap-1">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

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

        <!-- Phone Input -->
        <div>
            <label for="phone" class="block text-sm font-medium text-foreground mb-2">
                {{ __('auth.phone') }}
            </label>
            <div class="relative">
                <i data-lucide="phone" class="absolute left-3 top-3 w-5 h-5 text-slate-400"></i>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone" 
                    value="{{ old('phone') }}"
                    required
                    class="form-input w-full pl-10 {{ $errors->has('phone') ? 'border-danger' : '' }}"
                    placeholder="+8801XXXXXXXXX"
                    autocomplete="tel"
                >
            </div>
            @error('phone')
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
                    placeholder="Enter a strong password"
                    autocomplete="new-password"
                >
            </div>
            @error('password')
                <p class="text-danger text-xs mt-2 flex items-center gap-1">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Confirm Password Input -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-foreground mb-2">
                {{ __('auth.confirm_password') }}
            </label>
            <div class="relative">
                <i data-lucide="lock" class="absolute left-3 top-3 w-5 h-5 text-slate-400"></i>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    required
                    class="form-input w-full pl-10"
                    placeholder="Confirm your password"
                    autocomplete="new-password"
                >
            </div>
        </div>

        <!-- Terms Agreement -->
        <div class="flex items-start gap-2 pt-2">
            <input 
                type="checkbox" 
                id="terms" 
                name="terms" 
                required
                class="w-4 h-4 rounded mt-1"
            >
            <label for="terms" class="text-sm text-slate-600">
                {{ __('auth.agree_terms') }}
                <a href="#" class="text-primary hover:underline">{{ __('auth.terms_of_service') }}</a>
                {{ __('general.and') }}
                <a href="#" class="text-primary hover:underline">{{ __('auth.privacy_policy') }}</a>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-full mt-6">
            <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
            {{ __('auth.register') }}
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

    <!-- Login Link -->
    <a 
        href="{{ route('login') }}" 
        class="flex items-center justify-center gap-2 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-900 transition-colors text-sm font-medium text-foreground w-full"
    >
        <i data-lucide="log-in" class="w-4 h-4"></i>
        {{ __('auth.already_have_account') }}
    </a>
</div>
@endsection
