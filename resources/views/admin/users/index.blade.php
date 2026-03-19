@extends('layouts.admin')

@section('content')
<div class="content">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-4">
        <h1 class="text-lg font-medium text-slate-800 dark:text-slate-100">Users</h1>
        <nav class="flex pt-2 sm:pt-0" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('admin.dashboard.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-600">Dashboard</a></li>
                <li class="text-slate-500 dark:text-slate-400">/</li>
                <li class="text-slate-600 dark:text-slate-300">Users</li>
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
                <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add User
                </a>
                <div class="flex-1">
                    <input type="text" id="search" placeholder="Search by name or email..."
                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <select id="status-filter" class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="suspended">Suspended</option>
                    <option value="deactivated">Deactivated</option>
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
                        <th class="px-6 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-400">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-400">Type</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-400">Roles</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-400">Status</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-slate-600 dark:text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700 user-row"
                        data-name="{{ strtolower($user->name) }}"
                        data-email="{{ strtolower($user->email) }}"
                        data-status="{{ $user->suspended_at ? 'suspended' : ($user->deactivated_at ? 'deactivated' : 'active') }}">
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-900 dark:text-white">{{ $user->name }}</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $user->user_type }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-900 dark:text-white">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-300">
                                {{ ucfirst($user->user_type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @forelse($user->roles as $role)
                            <span class="inline-block px-2 py-1 text-xs rounded bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 mr-1 mb-1">
                                {{ $role->name }}
                            </span>
                            @empty
                            <span class="text-slate-500 dark:text-slate-400">No roles</span>
                            @endforelse
                        </td>
                        <td class="px-6 py-4">
                            @if($user->suspended_at)
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                Suspended
                            </span>
                            @elseif($user->deactivated_at)
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                Deactivated
                            </span>
                            @else
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Active
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200 rounded hover:bg-blue-200 transition-colors">
                                    View
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200 rounded hover:bg-yellow-200 transition-colors">
                                    Edit
                                </a>
                                @if(!$user->suspended_at)
                                <button onclick="suspendUser({{ $user->id }}, '{{ $user->name }}')" class="px-3 py-1 text-xs bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-200 rounded hover:bg-orange-200 transition-colors">
                                    Suspend
                                </button>
                                @else
                                <button onclick="activateUser({{ $user->id }}, '{{ $user->name }}')" class="px-3 py-1 text-xs bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200 rounded hover:bg-green-200 transition-colors">
                                    Activate
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                            No users found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>

<script>
document.getElementById('search').addEventListener('keyup', function() {
    filterTable();
});

document.getElementById('status-filter').addEventListener('change', function() {
    filterTable();
});

function filterTable() {
    const searchTerm = document.getElementById('search').value.toLowerCase();
    const statusFilter = document.getElementById('status-filter').value.toLowerCase();
    
    document.querySelectorAll('.user-row').forEach(row => {
        const name = row.getAttribute('data-name');
        const email = row.getAttribute('data-email');
        const status = row.getAttribute('data-status');
        
        const matchSearch = name.includes(searchTerm) || email.includes(searchTerm);
        const matchStatus = !statusFilter || status === statusFilter;
        
        row.style.display = matchSearch && matchStatus ? '' : 'none';
    });
}

function suspendUser(userId, userName) {
    const reason = prompt(`Suspend ${userName}? Enter reason:`);
    if (reason) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.users.suspend', '') }}/${userId}`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="reason" value="${reason}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function activateUser(userId, userName) {
    if (confirm(`Activate ${userName}?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.users.activate', '') }}/${userId}`;
        form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
