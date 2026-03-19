@extends('layouts.admin')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-4">
        <h1 class="text-lg font-medium text-slate-800 dark:text-slate-100">Add New Vehicle</h1>
        <nav class="flex pt-2 sm:pt-0" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('admin.dashboard.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-600">Dashboard</a></li>
                <li class="text-slate-500 dark:text-slate-400">/</li>
                <li><a href="{{ route('admin.vehicles.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-600">Vehicles</a></li>
                <li class="text-slate-500 dark:text-slate-400">/</li>
                <li class="text-slate-600 dark:text-slate-300">Add Vehicle</li>
            </ol>
        </nav>
    </div>

    <!-- Form Container -->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-8">
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <form action="{{ route('admin.vehicles.store') }}" method="POST" id="vehicleForm" class="space-y-6">
                    @csrf

                    <!-- Register Number -->
                    <div>
                        <label for="registration_number" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Registration Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="registration_number" name="registration_number" 
                            placeholder="e.g., DHA-1234-ABC" required
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('registration_number') border-red-500 @enderror"
                            value="{{ old('registration_number') }}">
                        @error('registration_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vehicle Type -->
                    <div>
                        <label for="vehicle_type" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Vehicle Type <span class="text-red-500">*</span>
                        </label>
                        <select id="vehicle_type" name="vehicle_type" required
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('vehicle_type') border-red-500 @enderror">
                            <option value="">Select Type</option>
                            <option value="car" {{ old('vehicle_type') == 'car' ? 'selected' : '' }}>Car</option>
                            <option value="motorcycle" {{ old('vehicle_type') == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                            <option value="bus" {{ old('vehicle_type') == 'bus' ? 'selected' : '' }}>Bus</option>
                            <option value="truck" {{ old('vehicle_type') == 'truck' ? 'selected' : '' }}>Truck</option>
                        </select>
                        @error('vehicle_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Brand -->
                        <div>
                            <label for="brand" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Brand <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="brand" name="brand" required
                                placeholder="e.g., Toyota"
                                class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('brand') border-red-500 @enderror"
                                value="{{ old('brand') }}">
                            @error('brand')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Model -->
                        <div>
                            <label for="model" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Model <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="model" name="model" required
                                placeholder="e.g., Corolla"
                                class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('model') border-red-500 @enderror"
                                value="{{ old('model') }}">
                            @error('model')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Color -->
                        <div>
                            <label for="color" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Color <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="color" name="color" required
                                placeholder="e.g., Silver"
                                class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('color') border-red-500 @enderror"
                                value="{{ old('color') }}">
                            @error('color')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Manufacture Year -->
                        <div>
                            <label for="manufacture_year" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Manufacture Year <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="manufacture_year" name="manufacture_year" required
                                placeholder="e.g., 2022" min="1900" max="{{ date('Y') + 1 }}"
                                class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('manufacture_year') border-red-500 @enderror"
                                value="{{ old('manufacture_year') }}">
                            @error('manufacture_year')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Active Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 border-slate-300">
                            <span class="ml-2 text-sm font-medium text-slate-700 dark:text-slate-300">Active</span>
                        </label>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                            Add Vehicle
                        </button>
                        <a href="{{ route('admin.vehicles.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-900 dark:text-white rounded-lg font-medium transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Help Panel -->
        <div class="col-span-12 lg:col-span-4">
            <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                <h3 class="font-medium text-blue-900 dark:text-blue-100 mb-3">Information</h3>
                <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                    <li>✓ All fields marked with * are required</li>
                    <li>✓ Registration number must be unique</li>
                    <li>✓ Year must be between 1900 and current year + 1</li>
                    <li>✓ Vehicle will be set as pending verification by default</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('vehicleForm').addEventListener('submit', function(e) {
    const regNum = document.getElementById('registration_number').value.trim();
    if (!regNum) {
        e.preventDefault();
        alert('Registration number is required');
    }
});
</script>
@endsection
