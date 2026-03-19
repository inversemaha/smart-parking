@extends('layouts.admin')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-4">
        <h1 class="text-lg font-medium text-slate-800 dark:text-slate-100">Add Parking Location</h1>
        <nav class="flex pt-2 sm:pt-0" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('admin.dashboard.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-600">Dashboard</a></li>
                <li class="text-slate-500 dark:text-slate-400">/</li>
                <li><a href="{{ route('admin.parking-locations.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-600">Parking Locations</a></li>
                <li class="text-slate-500 dark:text-slate-400">/</li>
                <li class="text-slate-600 dark:text-slate-300">Add Location</li>
            </ol>
        </nav>
    </div>

    <!-- Form -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-8">
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <form action="{{ route('admin.parking-locations.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Location Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" required placeholder="e.g., Downtown Parking"
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                            value="{{ old('name') }}">
                        @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Code -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Location Code <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="code" name="code" required placeholder="e.g., DT-001" maxlength="10"
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('code') border-red-500 @enderror"
                            value="{{ old('code') }}">
                        @error('code')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Description
                        </label>
                        <textarea id="description" name="description" rows="3" placeholder="Parking location description..."
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Address <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="address" name="address" required placeholder="Full address..."
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror"
                            value="{{ old('address') }}">
                        @error('address')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Latitude & Longitude -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Latitude <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="latitude" name="latitude" step="0.00000001" required
                                placeholder="23.8103"
                                class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('latitude') border-red-500 @enderror"
                                value="{{ old('latitude') }}">
                            @error('latitude')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="longitude" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Longitude <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="longitude" name="longitude" step="0.00000001" required
                                placeholder="90.3563"
                                class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('longitude') border-red-500 @enderror"
                                value="{{ old('longitude') }}">
                            @error('longitude')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Capacity -->
                    <div>
                        <label for="total_capacity" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Total Capacity <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="total_capacity" name="total_capacity" required min="1" placeholder="50"
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('total_capacity') border-red-500 @enderror"
                            value="{{ old('total_capacity') }}">
                        @error('total_capacity')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Hourly Rate -->
                    <div>
                        <label for="hourly_rate" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Hourly Rate (৳) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="hourly_rate" name="hourly_rate" required min="0" step="0.01" placeholder="50.00"
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('hourly_rate') border-red-500 @enderror"
                            value="{{ old('hourly_rate') }}">
                        @error('hourly_rate')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Active Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 border-slate-300">
                            <span class="ml-2 text-sm font-medium text-slate-700 dark:text-slate-300">Active</span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <div class="flex gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                            Create Location
                        </button>
                        <a href="{{ route('admin.parking-locations.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-900 dark:text-white rounded-lg font-medium transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info -->
        <div class="col-span-12 lg:col-span-4">
            <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                <h3 class="font-medium text-blue-900 dark:text-blue-100 mb-3">Tips</h3>
                <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                    <li>✓ Use unique location code</li>
                    <li>✓ Provide accurate GPS coordinates</li>
                    <li>✓ Specify total parking slots</li>
                    <li>✓ Set hourly rate in Taka (৳)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
