@extends('layouts.user')

@section('title', __('user.profile'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('user.my_profile') }}</h1>
                <p class="text-gray-600 mt-1">{{ __('user.manage_your_account_settings') }}</p>
            </div>
            <div class="flex items-center space-x-4">
                @if($user->avatar)
                    <img src="{{ Storage::disk('public')->url($user->avatar) }}" alt="{{ $user->name }}" class="h-12 w-12 rounded-full object-cover border-2 border-blue-200">
                @else
                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                        <span class="text-blue-600 font-medium text-lg">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                @endif
                <div>
                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Information -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">{{ __('user.profile_information') }}</h2>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">{{ __('user.name') }}</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">{{ __('user.email') }}</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('user.phone') }}</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Language -->
                        <div>
                            <label for="locale" class="block text-sm font-medium text-gray-700">{{ __('user.language') }}</label>
                            <select name="locale" id="locale" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="en" {{ $user->locale == 'en' ? 'selected' : '' }}>English</option>
                                <option value="bn" {{ $user->locale == 'bn' ? 'selected' : '' }}>বাংলা</option>
                            </select>
                            @error('locale')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            {{ __('user.update_profile') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-lg shadow-sm mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">{{ __('user.change_password') }}</h2>
                </div>

                <form method="POST" action="{{ route('profile.password.update') }}" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700">{{ __('user.current_password') }}</label>
                            <input type="password" name="current_password" id="current_password"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">{{ __('user.new_password') }}</label>
                            <input type="password" name="password" id="password"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('user.confirm_new_password') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            {{ __('user.change_password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Account Status -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('user.account_status') }}</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">{{ __('user.status') }}</span>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->is_active ? __('user.active') : __('user.inactive') }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">{{ __('user.email_verification') }}</span>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $user->email_verified_at ? __('user.verified') : __('user.unverified') }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">{{ __('user.role') }}</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ $user->roles->pluck('display_name')->join(', ') ?: __('user.user_role') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Profile Avatar -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('user.avatar') }}</h3>
                <div class="text-center">
                    @if($user->avatar)
                        <img src="{{ Storage::disk('public')->url($user->avatar) }}" alt="{{ $user->name }}" class="mx-auto h-20 w-20 rounded-full object-cover">
                    @else
                        <div class="mx-auto h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500 font-medium text-2xl">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" class="mt-4">
                        @csrf
                        <input type="file" name="avatar" accept="image/*" class="hidden" id="avatar-upload">
                        <label for="avatar-upload" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 cursor-pointer">
                            {{ __('user.change_photo') }}
                        </label>
                    </form>
                </div>
            </div>

            <!-- Account Actions -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('user.account_actions') }}</h3>
                <div class="space-y-3">
                    <a href="{{ route('vehicles.index') }}" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                        {{ __('user.my_vehicles') }}
                    </a>
                    <a href="{{ route('bookings.index') }}" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                        {{ __('user.my_bookings') }}
                    </a>
                    @can('accessAccountant', auth()->user())
                    <a href="{{ route('payments.index') }}" class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                        {{ __('user.my_payments') }}
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('avatar-upload').addEventListener('change', function() {
    if (this.files && this.files[0]) {
        this.form.submit();
    }
});
</script>
@endsection
