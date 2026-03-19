@extends('layouts.admin')

@section('content')
<div class="content">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-4">
        <h1 class="text-lg font-medium text-slate-800 dark:text-slate-100">Floor {{ $floor->floor_number }}</h1>
        <nav class="flex pt-2 sm:pt-0" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('admin.dashboard.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-600">Dashboard</a></li>
                <li class="text-slate-500 dark:text-slate-400">/</li>
                <li><a href="{{ route('admin.parking-floors.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-600">Parking Floors</a></li>
                <li class="text-slate-500 dark:text-slate-400">/</li>
                <li class="text-slate-600 dark:text-slate-300">Details</li>
            </ol>
        </nav>
    </div>

    <!-- Details Card -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Zone</label>
                <p class="text-slate-900 dark:text-white">{{ $floor->zone->name ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Floor Number</label>
                <p class="text-slate-900 dark:text-white">{{ $floor->floor_number }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Total Slots</label>
                <p class="text-slate-900 dark:text-white">{{ $floor->total_slots ?? 0 }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Available Slots</label>
                <p class="text-slate-900 dark:text-white">{{ $floor->available_slots ?? 0 }}</p>
            </div>
        </div>

        <div class="flex gap-4 mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
            <a href="{{ route('admin.parking-floors.edit', $floor->id) }}" class="px-6 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg font-medium transition-colors">Edit</a>
            <a href="{{ route('admin.parking-floors.index') }}" class="px-6 py-2 bg-slate-300 dark:bg-slate-600 text-slate-900 dark:text-white rounded-lg font-medium transition-colors">Back</a>
        </div>
    </div>
</div>
@endsection
