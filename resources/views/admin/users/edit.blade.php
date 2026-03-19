@extends('layouts.admin')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-4">
        <h1 class="text-lg font-medium text-slate-800 dark:text-slate-100">Edit User</h1>
        <nav class="flex pt-2 sm:pt-0" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('admin.dashboard.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-600">Dashboard</a></li>
                <li class="text-slate-500 dark:text-slate-400">/</li>
                <li><a href="{{ route('admin.users.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-600">Users</a></li>
                <li class="text-slate-500 dark:text-slate-400">/</li>
                <li class="text-slate-600 dark:text-slate-300">{{ $user->name }}</li>
            </ol>
        </nav>
    </div>

    <!-- Form -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-8">
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" required
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                            value="{{ old('name', $user->name) }}">
                        @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Email
                        </label>
                        <input type="email" id="email" name="email" readonly
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('email', $user->email) }}">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Email cannot be changed</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Mobile -->
                        <div>
                            <label for="mobile" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Mobile
                            </label>
                            <input type="tel" id="mobile" name="mobile"
                                class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('mobile') border-red-500 @enderror"
                                value="{{ old('mobile', $user->mobile) }}">
                            @error('mobile')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- User Type -->
                        <div>
                            <label for="user_type" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                User Type
                            </label>
                            <input type="text" id="user_type" readonly
                                class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white"
                                value="{{ ucfirst($user->user_type) }}">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">User type cannot be changed</p>
                        </div>
                    </div>

                    <!-- Active Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 border-slate-300">
                            <span class="ml-2 text-sm font-medium text-slate-700 dark:text-slate-300">Active</span>
                        </label>
                    </div>

                    <!-- Roles -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-4">Roles</label>
                        <div class="space-y-2">
                            @forelse($roles as $role)
                            <label class="flex items-center">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                    {{ $user->roles->contains($role->id) ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 border-slate-300">
                                <span class="ml-2 text-sm text-slate-700 dark:text-slate-300">{{ $role->name }}</span>
                            </label>
                            @empty
                            <p class="text-sm text-slate-500 dark:text-slate-400">No roles available</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                            Update User
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-900 dark:text-white rounded-lg font-medium transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info -->
        <div class="col-span-12 lg:col-span-4">
            <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4">
                <h3 class="font-medium text-slate-900 dark:text-white mb-3 text-sm">System Info</h3>
                <div class="text-xs text-slate-600 dark:text-slate-400 space-y-2">
                    <p><strong>Created:</strong> {{ $user->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Updated:</strong> {{ $user->updated_at->format('M d, Y H:i') }}</p>
                    <p><strong>User ID:</strong> {{ $user->id }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
