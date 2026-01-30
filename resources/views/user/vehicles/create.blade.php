@extends('layouts.user')

@section('title', __('vehicles.add_vehicle'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('vehicles.add_vehicle') }}</h1>
            <p class="text-gray-600 mt-1">{{ __('vehicles.add_vehicle_desc') }}</p>
        </div>

        <!-- Vehicle Form -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('vehicles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Registration Number -->
                <div class="mb-6">
                    <label for="registration_number" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('vehicles.registration_number') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="registration_number"
                           id="registration_number"
                           value="{{ old('registration_number') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('registration_number') border-red-300 @enderror"
                           placeholder="{{ __('vehicles.registration_number') }}"
                           required>
                    @error('registration_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Vehicle Type -->
                <div class="mb-6">
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('vehicles.type') }} <span class="text-red-500">*</span>
                    </label>
                    <select name="type"
                            id="type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-300 @enderror"
                            required>
                        <option value="">{{ __('general.select') }}</option>
                        <option value="car" {{ old('type') == 'car' ? 'selected' : '' }}>{{ __('vehicles.car') }}</option>
                        <option value="motorcycle" {{ old('type') == 'motorcycle' ? 'selected' : '' }}>{{ __('vehicles.motorcycle') }}</option>
                        <option value="truck" {{ old('type') == 'truck' ? 'selected' : '' }}>{{ __('vehicles.truck') }}</option>
                        <option value="van" {{ old('type') == 'van' ? 'selected' : '' }}>{{ __('vehicles.van') }}</option>
                        <option value="bus" {{ old('type') == 'bus' ? 'selected' : '' }}>{{ __('vehicles.bus') }}</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Make and Model -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="make" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('vehicles.make') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="make"
                               id="make"
                               value="{{ old('make') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('make') border-red-300 @enderror"
                               placeholder="{{ __('vehicles.make') }}"
                               required>
                        @error('make')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('vehicles.model') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="model"
                               id="model"
                               value="{{ old('model') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('model') border-red-300 @enderror"
                               placeholder="{{ __('vehicles.model') }}"
                               required>
                        @error('model')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Year and Color -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('vehicles.year') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               name="year"
                               id="year"
                               value="{{ old('year') }}"
                               min="1900"
                               max="{{ date('Y') + 1 }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('year') border-red-300 @enderror"
                               placeholder="{{ __('vehicles.year') }}"
                               required>
                        @error('year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('vehicles.color') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="color"
                               id="color"
                               value="{{ old('color') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('color') border-red-300 @enderror"
                               placeholder="{{ __('vehicles.color') }}"
                               required>
                        @error('color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Documents Upload -->
                <div class="mb-6">
                    <label for="documents" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('general.documents') }}
                    </label>
                    <input type="file"
                           name="documents[]"
                           id="documents"
                           multiple
                           accept=".jpg,.jpeg,.png,.pdf"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <p class="mt-1 text-xs text-gray-500">{{ __('general.file_formats_supported') }}: JPG, PNG, PDF</p>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('vehicles.index') }}"
                       class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ __('general.cancel') }}
                    </a>
                    <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        {{ __('vehicles.add_vehicle') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
