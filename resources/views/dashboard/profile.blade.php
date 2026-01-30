@extends('layouts.app')

@section('title', __('Profile'))

@section('content')
<div class="content__scroll-area">
    <!-- Profile Header -->
    <div class="grid grid-cols-12 gap-y-10 gap-x-6 mb-8">
        <div class="col-span-12">
            <div class="flex items-center h-10">
                <div class="text-lg font-medium group-[.mode--light]:text-white">
                    {{ __('Profile Settings') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Form -->
    <div class="grid grid-cols-12 gap-y-10 gap-x-6">
        <div class="col-span-12 lg:col-span-8">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">{{ __('Personal Information') }}</h3>

                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                        <p class="text-sm text-green-600 dark:text-green-400">{{ session('success') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        @foreach ($errors->all() as $error)
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Full Name') }}
                        </label>
                        <input id="name" name="name" type="text" required
                               value="{{ old('name', $user->name) }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white transition-colors">
                    </div>

                    <!-- Email (read-only) -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Email Address') }}
                        </label>
                        <input id="email" type="email" readonly
                               value="{{ $user->email }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('Email cannot be changed') }}</p>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Phone Number') }}
                        </label>
                        <input id="phone" name="phone" type="tel" required
                               value="{{ old('phone', $user->phone) }}"
                               placeholder="+8801XXXXXXXXX"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white transition-colors">
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Address') }}
                        </label>
                        <textarea id="address" name="address" rows="3"
                                  placeholder="{{ __('Enter your address') }}"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white transition-colors">{{ old('address', $user->address) }}</textarea>
                    </div>

                    <!-- Language Preference -->
                    <div>
                        <label for="language_preference" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Language Preference') }}
                        </label>
                        <select id="language_preference" name="language_preference" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white transition-colors">
                            <option value="en" {{ old('language_preference', $user->language_preference) === 'en' ? 'selected' : '' }}>English</option>
                            <option value="bn" {{ old('language_preference', $user->language_preference) === 'bn' ? 'selected' : '' }}>বাংলা</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        {{ __('Update Profile') }}
                    </button>
                </form>
            </div>

            <!-- Password Update Form -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-6 mt-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">{{ __('Change Password') }}</h3>

                <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Current Password') }}
                        </label>
                        <input id="current_password" name="current_password" type="password" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white transition-colors">
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('New Password') }}
                        </label>
                        <input id="password" name="password" type="password" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white transition-colors">
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Confirm New Password') }}
                        </label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white transition-colors">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        {{ __('Update Password') }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-span-12 lg:col-span-4">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Account Information') }}</h3>
                <div class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Member Since') }}</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $user->created_at ? $user->created_at->format('F j, Y') : 'Unknown' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email Status') }}</dt>
                        <dd class="text-sm">
                            @if($user->hasVerifiedEmail())
                                <span class="text-green-600 dark:text-green-400">{{ __('Verified') }}</span>
                            @else
                                <span class="text-red-600 dark:text-red-400">{{ __('Unverified') }}</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Phone Status') }}</dt>
                        <dd class="text-sm">
                            @if($user->phone_verified_at)
                                <span class="text-green-600 dark:text-green-400">{{ __('Verified') }}</span>
                            @else
                                <span class="text-red-600 dark:text-red-400">{{ __('Unverified') }}</span>
                            @endif
                        </dd>
                    </div>
                </div>
            </div>

            <!-- Avatar Upload -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Profile Picture') }}</h3>

                <div class="text-center">
                    <div class="mb-4">
                        @if($user->avatar_path)
                            <img src="{{ asset('storage/' . $user->avatar_path) }}" alt="{{ $user->name }}"
                                 class="w-20 h-20 rounded-full mx-auto object-cover">
                        @else
                            <div class="w-20 h-20 bg-gray-200 dark:bg-gray-700 rounded-full mx-auto flex items-center justify-center">
                                <i data-lucide="user" class="w-10 h-10 text-gray-400"></i>
                            </div>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden" onchange="this.form.submit()">
                        <label for="avatar" class="inline-block px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            {{ __('Change Picture') }}
                        </label>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
