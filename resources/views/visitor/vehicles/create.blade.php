@extends('layouts.visitor')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('Add Vehicle') }}</h1>
                    <p class="text-gray-600 mt-1">{{ __('Register a new vehicle to your account') }}</p>
                </div>
                <a href="{{ route('visitor.vehicles.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('visitor.vehicles.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <!-- License Plate -->
                    <div>
                        <label for="license_plate" class="block text-sm font-medium text-gray-700">
                            {{ __('License Plate') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="text" name="license_plate" id="license_plate"
                                   value="{{ old('license_plate') }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('license_plate') border-red-300 @enderror"
                                   placeholder="{{ __('Enter license plate number') }}" required>
                        </div>
                        @error('license_plate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vehicle Make -->
                    <div>
                        <label for="make" class="block text-sm font-medium text-gray-700">
                            {{ __('Make') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="text" name="make" id="make"
                                   value="{{ old('make') }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('make') border-red-300 @enderror"
                                   placeholder="{{ __('e.g., Toyota, Honda, BMW') }}" required>
                        </div>
                        @error('make')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vehicle Model -->
                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700">
                            {{ __('Model') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="text" name="model" id="model"
                                   value="{{ old('model') }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('model') border-red-300 @enderror"
                                   placeholder="{{ __('e.g., Camry, Civic, X5') }}" required>
                        </div>
                        @error('model')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Year -->
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700">
                            {{ __('Year') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <select name="year" id="year"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('year') border-red-300 @enderror" required>
                                <option value="">{{ __('Select year') }}</option>
                                @for($year = date('Y'); $year >= 1990; $year--)
                                    <option value="{{ $year }}" {{ old('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        @error('year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Color -->
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700">
                            {{ __('Color') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <select name="color" id="color"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('color') border-red-300 @enderror" required>
                                <option value="">{{ __('Select color') }}</option>
                                <option value="White" {{ old('color') == 'White' ? 'selected' : '' }}>{{ __('White') }}</option>
                                <option value="Black" {{ old('color') == 'Black' ? 'selected' : '' }}>{{ __('Black') }}</option>
                                <option value="Silver" {{ old('color') == 'Silver' ? 'selected' : '' }}>{{ __('Silver') }}</option>
                                <option value="Gray" {{ old('color') == 'Gray' ? 'selected' : '' }}>{{ __('Gray') }}</option>
                                <option value="Red" {{ old('color') == 'Red' ? 'selected' : '' }}>{{ __('Red') }}</option>
                                <option value="Blue" {{ old('color') == 'Blue' ? 'selected' : '' }}>{{ __('Blue') }}</option>
                                <option value="Green" {{ old('color') == 'Green' ? 'selected' : '' }}>{{ __('Green') }}</option>
                                <option value="Yellow" {{ old('color') == 'Yellow' ? 'selected' : '' }}>{{ __('Yellow') }}</option>
                                <option value="Brown" {{ old('color') == 'Brown' ? 'selected' : '' }}>{{ __('Brown') }}</option>
                                <option value="Other" {{ old('color') == 'Other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                            </select>
                        </div>
                        @error('color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vehicle Type -->
                    <div>
                        <label for="vehicle_type" class="block text-sm font-medium text-gray-700">
                            {{ __('Vehicle Type') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <select name="vehicle_type" id="vehicle_type"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('vehicle_type') border-red-300 @enderror" required>
                                <option value="">{{ __('Select vehicle type') }}</option>
                                <option value="car" {{ old('vehicle_type') == 'car' ? 'selected' : '' }}>{{ __('Car') }}</option>
                                <option value="motorcycle" {{ old('vehicle_type') == 'motorcycle' ? 'selected' : '' }}>{{ __('Motorcycle') }}</option>
                                <option value="suv" {{ old('vehicle_type') == 'suv' ? 'selected' : '' }}>{{ __('SUV') }}</option>
                                <option value="truck" {{ old('vehicle_type') == 'truck' ? 'selected' : '' }}>{{ __('Truck') }}</option>
                                <option value="van" {{ old('vehicle_type') == 'van' ? 'selected' : '' }}>{{ __('Van') }}</option>
                            </select>
                        </div>
                        @error('vehicle_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">
                            {{ __('Notes') }} <span class="text-gray-500">({{ __('Optional') }})</span>
                        </label>
                        <div class="mt-1">
                            <textarea name="notes" id="notes" rows="3"
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-300 @enderror"
                                      placeholder="{{ __('Any additional notes about your vehicle') }}">{{ old('notes') }}</textarea>
                        </div>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Set as Default -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_default" id="is_default" value="1"
                                   {{ old('is_default') ? 'checked' : '' }}
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_default" class="font-medium text-gray-700">
                                {{ __('Set as default vehicle') }}
                            </label>
                            <p class="text-gray-500">{{ __('This vehicle will be selected automatically when making bookings') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end pt-6 space-x-3 border-t border-gray-200">
                    <a href="{{ route('visitor.vehicles.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ __('Add Vehicle') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
