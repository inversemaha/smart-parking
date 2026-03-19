@extends('layouts.admin')

@section('content')
<div class="content">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-4">
        <h1 class="text-lg font-medium text-slate-800 dark:text-slate-100">Create Parking Floor</h1>
        <nav class="flex pt-2 sm:pt-0" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('admin.dashboard.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-600">Dashboard</a></li>
                <li class="text-slate-500 dark:text-slate-400">/</li>
                <li><a href="{{ route('admin.parking-floors.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-600">Parking Floors</a></li>
                <li class="text-slate-500 dark:text-slate-400">/</li>
                <li class="text-slate-600 dark:text-slate-300">Create</li>
            </ol>
        </nav>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
        <form action="{{ route('admin.parking-floors.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Zone</label>
                    <select name="zone_id" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select a zone</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Floor Number</label>
                    <input type="number" name="floor_number" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 1">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Total Slots</label>
                    <input type="number" name="total_slots" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 50">
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">Create Floor</button>
                    <a href="{{ route('admin.parking-floors.index') }}" class="px-6 py-2 bg-slate-300 dark:bg-slate-600 text-slate-900 dark:text-white rounded-lg font-medium transition-colors">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
