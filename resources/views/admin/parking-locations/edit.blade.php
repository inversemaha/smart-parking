@extends('layouts.admin')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-4">
        <h1 class="text-lg font-medium text-slate-800 dark:text-slate-100">Edit Parking Location</h1>
        <nav class="flex pt-2 sm:pt-0" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('admin.dashboard.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-600">Dashboard</a></li>
                <li class="text-slate-500 dark:text-slate-400">/</li>
                <li><a href="{{ route('admin.parking-locations.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-600">Parking Locations</a></li>
                <li class="text-slate-500 dark:text-slate-400">/</li>
                <li class="text-slate-600 dark:text-slate-300">{{ $parkingLocation->name }}</li>
            </ol>
        </nav>
    </div>

    <!-- Form -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-8">
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <form action="{{ route('admin.parking-locations.update', $parkingLocation->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Location Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" required
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                            value="{{ old('name', $parkingLocation->name) }}">
                        @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Code -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Location Code
                        </label>
                        <input type="text" id="code" name="code" maxlength="10"
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('code') border-red-500 @enderror"
                            value="{{ old('code', $parkingLocation->code) }}"
                            readonly>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Code cannot be changed</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Description
                        </label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $parkingLocation->description) }}</textarea>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Address <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="address" name="address" required
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror"
                            value="{{ old('address', $parkingLocation->address) }}">
                        @error('address')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Latitude & Longitude -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Latitude <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="latitude" name="latitude" step="0.00000001" required
                                class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('latitude') border-red-500 @enderror"
                                value="{{ old('latitude', $parkingLocation->latitude) }}">
                            @error('latitude')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="longitude" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Longitude <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="longitude" name="longitude" step="0.00000001" required
                                class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('longitude') border-red-500 @enderror"
                                value="{{ old('longitude', $parkingLocation->longitude) }}">
                            @error('longitude')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Capacity -->
                    <div>
                        <label for="total_capacity" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Total Capacity <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="total_capacity" name="total_capacity" required min="1"
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('total_capacity') border-red-500 @enderror"
                            value="{{ old('total_capacity', $parkingLocation->total_capacity) }}">
                        @error('total_capacity')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Hourly Rate -->
                    <div>
                        <label for="hourly_rate" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Hourly Rate (৳) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="hourly_rate" name="hourly_rate" required min="0" step="0.01"
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('hourly_rate') border-red-500 @enderror"
                            value="{{ old('hourly_rate', $parkingLocation->hourly_rate) }}">
                        @error('hourly_rate')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Active Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $parkingLocation->is_active) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 border-slate-300">
                            <span class="ml-2 text-sm font-medium text-slate-700 dark:text-slate-300">Active</span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <div class="flex gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                            Update Location
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
            <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4">
                <h3 class="font-medium text-slate-900 dark:text-white mb-3 text-sm">System Info</h3>
                <div class="text-xs text-slate-600 dark:text-slate-400 space-y-2">
                    <p><strong>Created:</strong> {{ $parkingLocation->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Updated:</strong> {{ $parkingLocation->updated_at->format('M d, Y H:i') }}</p>
                    <p><strong>ID:</strong> {{ $parkingLocation->id }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
