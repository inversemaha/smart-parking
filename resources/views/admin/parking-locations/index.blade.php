@extends('layouts.admin')

@section('content')
<div class="content">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-4">
        <h1 class="text-lg font-medium text-slate-800 dark:text-slate-100">Parking Locations</h1>
        <nav class="flex pt-2 sm:pt-0" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('admin.dashboard.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-600">Dashboard</a></li>
                <li class="text-slate-500 dark:text-slate-400">/</li>
                <li class="text-slate-600 dark:text-slate-300">Parking Locations</li>
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
                <a href="{{ route('admin.parking-locations.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Parking Location
                </a>
                <div class="flex-1">
                    <input type="text" id="search" placeholder="Search by name, code, or address..." 
                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <select id="status-filter" class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-100 dark:bg-slate-700 border-b border-slate-200 dark:border-slate-600">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-400">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-400">Code</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-400">Capacity</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-400">Hourly Rate</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-400">Status</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-slate-600 dark:text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($parkingLocations as $location)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700 parking-row" data-name="{{ strtolower($location->name) }}" data-code="{{ strtolower($location->code) }}" data-address="{{ strtolower($location->address) }}" data-status="{{ $location->is_active ? 'active' : 'inactive' }}">
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-900 dark:text-white">{{ $location->name }}</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $location->address }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-900 dark:text-white font-mono">{{ $location->code }}</td>
                        <td class="px-6 py-4 text-slate-900 dark:text-white">{{ $location->total_capacity }} slots</td>
                        <td class="px-6 py-4 text-slate-900 dark:text-white font-medium">৳{{ number_format($location->hourly_rate, 2) }}/hr</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $location->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                {{ $location->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.parking-locations.show', $location->id) }}" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200 rounded hover:bg-blue-200 transition-colors">
                                    View
                                </a>
                                <a href="{{ route('admin.parking-locations.edit', $location->id) }}" class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200 rounded hover:bg-yellow-200 transition-colors">
                                    Edit
                                </a>
                                <button onclick="deleteLoc({{ $location->id }}, '{{ $location->name }}')" class="px-3 py-1 text-xs bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200 rounded hover:bg-red-200 transition-colors">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                            No parking locations found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $parkingLocations->links() }}
    </div>
</div>

<!-- Delete Modal -->
<div id="delete-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl p-6 max-w-sm w-full mx-4">
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Delete Parking Location</h3>
        <p class="text-slate-600 dark:text-slate-400 mb-6">Are you sure you want to delete <span id="modal-location-name" class="font-bold"></span>? This action cannot be undone.</p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-900 dark:text-white rounded-lg font-medium transition-colors">
                Cancel
            </button>
            <button id="confirm-delete-btn" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                Delete
            </button>
        </div>
    </div>
</div>

<script>
let deleteId = null;

document.getElementById('search').addEventListener('keyup', function() {
    filterTable();
});

document.getElementById('status-filter').addEventListener('change', function() {
    filterTable();
});

function filterTable() {
    const searchTerm = document.getElementById('search').value.toLowerCase();
    const statusFilter = document.getElementById('status-filter').value;
    
    document.querySelectorAll('.parking-row').forEach(row => {
        const name = row.getAttribute('data-name');
        const code = row.getAttribute('data-code');
        const address = row.getAttribute('data-address');
        const status = row.getAttribute('data-status');
        
        const matchSearch = name.includes(searchTerm) || code.includes(searchTerm) || address.includes(searchTerm);
        const matchStatus = !statusFilter || status === statusFilter;
        
        row.style.display = matchSearch && matchStatus ? '' : 'none';
    });
}

function deleteLoc(id, name) {
    deleteId = id;
    document.getElementById('modal-location-name').textContent = name;
    document.getElementById('delete-modal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
    deleteId = null;
}

document.getElementById('confirm-delete-btn').addEventListener('click', function() {
    if (deleteId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.parking-locations.destroy', '') }}/${deleteId}`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
    }
});

document.getElementById('delete-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection
