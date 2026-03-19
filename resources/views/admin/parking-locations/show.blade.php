@extends('layouts.admin')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-4">
        <h1 class="text-lg font-medium text-slate-800 dark:text-slate-100">Parking Location Details</h1>
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

    <div class="grid grid-cols-12 gap-6">
        <!-- Main Info -->
        <div class="col-span-12 lg:col-span-8 space-y-6">
            <!-- Location Card -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $parkingLocation->name }}</h2>
                        <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $parkingLocation->address }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $parkingLocation->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                        {{ $parkingLocation->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-slate-600 dark:text-slate-400 text-sm">Code</label>
                        <p class="font-medium text-slate-900 dark:text-white mt-1 font-mono">{{ $parkingLocation->code }}</p>
                    </div>
                    <div>
                        <label class="text-slate-600 dark:text-slate-400 text-sm">Total Capacity</label>
                        <p class="font-medium text-slate-900 dark:text-white mt-1">{{ $parkingLocation->total_capacity }} slots</p>
                    </div>
                    <div>
                        <label class="text-slate-600 dark:text-slate-400 text-sm">Hourly Rate</label>
                        <p class="font-medium text-slate-900 dark:text-white mt-1">৳{{ number_format($parkingLocation->hourly_rate, 2) }}</p>
                    </div>
                </div>

                @if($parkingLocation->description)
                <div class="mt-6 p-4 bg-slate-100 dark:bg-slate-700 rounded-lg">
                    <label class="text-slate-600 dark:text-slate-400 text-sm block mb-2">Description</label>
                    <p class="text-slate-900 dark:text-white">{{ $parkingLocation->description }}</p>
                </div>
                @endif
            </div>

            <!-- Coordinates & Details -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Location Details</h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="text-slate-600 dark:text-slate-400 text-sm">Latitude</label>
                        <p class="font-medium text-slate-900 dark:text-white mt-1">{{ $parkingLocation->latitude }}</p>
                    </div>
                    <div>
                        <label class="text-slate-600 dark:text-slate-400 text-sm">Longitude</label>
                        <p class="font-medium text-slate-900 dark:text-white mt-1">{{ $parkingLocation->longitude }}</p>
                    </div>
                </div>
            </div>

            <!-- Parking Slots -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Parking Slots</h3>
                <div class="grid grid-cols-3 gap-4">
                    <div class="p-4 bg-blue-50 dark:bg-blue-900 rounded-lg border border-blue-200 dark:border-blue-800">
                        <label class="text-blue-700 dark:text-blue-300 text-sm">Total Slots</label>
                        <p class="text-2xl font-bold text-blue-900 dark:text-blue-100 mt-1">{{ $parkingLocation->total_capacity }}</p>
                    </div>
                    <div class="p-4 bg-green-50 dark:bg-green-900 rounded-lg border border-green-200 dark:border-green-800">
                        <label class="text-green-700 dark:text-green-300 text-sm">Available</label>
                        <p class="text-2xl font-bold text-green-900 dark:text-green-100 mt-1">{{ $parkingLocation->availableSlots()->count() }}</p>
                    </div>
                    <div class="p-4 bg-red-50 dark:bg-red-900 rounded-lg border border-red-200 dark:border-red-800">
                        <label class="text-red-700 dark:text-red-300 text-sm">Occupied</label>
                        <p class="text-2xl font-bold text-red-900 dark:text-red-100 mt-1">{{ $parkingLocation->total_capacity - $parkingLocation->availableSlots()->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-span-12 lg:col-span-4 space-y-4">
            <!-- Actions -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <h3 class="font-bold text-slate-900 dark:text-white mb-4">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.parking-locations.edit', $parkingLocation->id) }}" class="block w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-center rounded-lg font-medium transition-colors">
                        Edit Location
                    </a>
                    <button onclick="confirmDelete()" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                        Delete Location
                    </button>
                </div>
            </div>

            <!-- System Info -->
            <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4">
                <h3 class="font-medium text-slate-900 dark:text-white mb-3 text-sm">System Information</h3>
                <div class="text-xs text-slate-600 dark:text-slate-400 space-y-2">
                    <p><strong>Created:</strong> {{ $parkingLocation->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Updated:</strong> {{ $parkingLocation->updated_at->format('M d, Y H:i') }}</p>
                    <p><strong>Location ID:</strong> {{ $parkingLocation->id }}</p>
                    @if($parkingLocation->opened_at)
                    <p><strong>Opened:</strong> {{ $parkingLocation->opened_at->format('M d, Y') }}</p>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4">
                <h3 class="font-medium text-slate-900 dark:text-white mb-3 text-sm">Quick Stats</h3>
                <div class="text-xs text-slate-600 dark:text-slate-400 space-y-2">
                    <p><strong>Total Bookings:</strong> {{ $parkingLocation->bookings()->count() ?? 0 }}</p>
                    <p><strong>Occupancy Rate:</strong> {{ round((($parkingLocation->total_capacity - $parkingLocation->availableSlots()->count()) / $parkingLocation->total_capacity) * 100, 1) }}%</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this parking location? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.parking-locations.destroy', $parkingLocation->id) }}`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
