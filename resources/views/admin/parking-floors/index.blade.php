@extends('layouts.admin')

@section('content')
<div class="content">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-4">
        <h1 class="text-lg font-medium text-slate-800 dark:text-slate-100">Parking Floors</h1>
        <nav class="flex pt-2 sm:pt-0" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('admin.dashboard.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-600">Dashboard</a></li>
                <li class="text-slate-500 dark:text-slate-400">/</li>
                <li class="text-slate-600 dark:text-slate-300">Parking Floors</li>
            </ol>
        </nav>
    </div>

    <!-- Messages -->
    @if($message = session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">{{ $message }}</div>
    @endif
    @if($message = session('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">{{ $message }}</div>
    @endif

    <!-- Actions & Search -->
    <div class="grid grid-cols-12 gap-6 mb-4">
        <div class="col-span-12">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <a href="{{ route('admin.parking-floors.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Floor
                </a>
                <div class="flex-1">
                    <input type="text" id="search" placeholder="Search by floor number or zone..." 
                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-100 dark:bg-slate-700 border-b border-slate-200 dark:border-slate-600">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-400">Zone</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-400">Floor Number</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-400">Total Slots</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-400">Available</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-slate-600 dark:text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($floors as $floor)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700">
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-900 dark:text-white">{{ $floor->zone->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-900 dark:text-white font-medium">Floor {{ $floor->floor_number }}</td>
                        <td class="px-6 py-4 text-slate-900 dark:text-white">{{ $floor->total_slots ?? 0 }} slots</td>
                        <td class="px-6 py-4 text-slate-900 dark:text-white">
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                {{ $floor->available_slots ?? 0 }} available
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.parking-floors.show', $floor->id) }}" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200 rounded hover:bg-blue-200 transition-colors">
                                    View
                                </a>
                                <a href="{{ route('admin.parking-floors.edit', $floor->id) }}" class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200 rounded hover:bg-yellow-200 transition-colors">
                                    Edit
                                </a>
                                <button onclick="deleteFloor({{ $floor->id }})" class="px-3 py-1 text-xs bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200 rounded hover:bg-red-200 transition-colors">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                            No parking floors found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($floors->hasPages())
    <div class="mt-4">
        {{ $floors->links() }}
    </div>
    @endif
</div>

<script>
function deleteFloor(id) {
    if (confirm('Are you sure you want to delete this floor?')) {
        // Implement delete logic
        console.log('Delete floor:', id);
    }
}
</script>
@endsection
