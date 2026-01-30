@extends('layouts.admin')

@section('admin-title', __('admin.users.title'))
@section('admin-subtitle', __('admin.users.manage'))

@section('admin-content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <!-- Search -->
            <div class="relative">
                <input type="text" id="user-search"
                       class="form-input pl-10 pr-4 py-2 border-gray-300 rounded-lg w-64"
                       placeholder="{{ __('admin.actions.search') }} {{ __('admin.users.title') }}...">
                <i data-lucide="search" class="w-5 h-5 text-gray-400 absolute left-3 top-2.5"></i>
            </div>

            <!-- Status Filter -->
            <select id="status-filter" class="form-select border-gray-300 rounded-lg">
                <option value="">{{ __('admin.users.status.all') }}</option>
                <option value="active">{{ __('admin.users.status.active') }}</option>
                <option value="suspended">{{ __('admin.users.status.suspended') }}</option>
                <option value="pending_verification">{{ __('admin.users.status.pending_verification') }}</option>
            </select>
        </div>

        @can('createUsers', \App\Policies\AdminPolicy::class)
        <button onclick="openCreateModal()"
                class="btn btn-primary">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            {{ __('admin.users.create') }}
        </button>
        @endcan
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stats-card">
            <div class="flex items-center">
                <div class="stats-icon bg-blue-100">
                    <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-semibold text-gray-800" id="total-users">{{ $stats['total'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">{{ __('admin.users.fields.total') }}</div>
                </div>
            </div>
        </div>

        <div class="stats-card">
            <div class="flex items-center">
                <div class="stats-icon bg-green-100">
                    <i data-lucide="user-check" class="w-6 h-6 text-green-600"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-semibold text-gray-800" id="active-users">{{ $stats['active'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">{{ __('admin.users.status.active') }}</div>
                </div>
            </div>
        </div>

        <div class="stats-card">
            <div class="flex items-center">
                <div class="stats-icon bg-yellow-100">
                    <i data-lucide="user-x" class="w-6 h-6 text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-semibold text-gray-800" id="suspended-users">{{ $stats['suspended'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">{{ __('admin.users.status.suspended') }}</div>
                </div>
            </div>
        </div>

        <div class="stats-card">
            <div class="flex items-center">
                <div class="stats-icon bg-purple-100">
                    <i data-lucide="clock" class="w-6 h-6 text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-semibold text-gray-800" id="pending-users">{{ $stats['pending'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">{{ __('admin.users.status.pending_verification') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-medium text-gray-700">{{ __('admin.users.fields.name') }}</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">{{ __('admin.users.fields.mobile') }}</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">{{ __('admin.users.fields.status') }}</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">{{ __('admin.users.fields.total_bookings') }}</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">{{ __('admin.users.fields.total_spent') }}</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">{{ __('admin.users.fields.created_at') }}</th>
                            <th class="text-right py-3 px-4 font-medium text-gray-700">{{ __('admin.actions.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="users-table-body">
                        <!-- Users will be loaded via AJAX -->
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                <i data-lucide="loader" class="w-6 h-6 mx-auto mb-2 animate-spin"></i>
                                {{ __('admin.messages.loading') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6" id="pagination-container">
                <!-- Pagination will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit User Modal -->
<div id="user-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-800" id="modal-title">{{ __('admin.users.create') }}</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <form id="user-form">
                <input type="hidden" id="user-id" name="user_id">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.users.fields.name') }}</label>
                        <input type="text" id="user-name" name="name" required
                               class="form-input w-full border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.users.fields.mobile') }}</label>
                        <input type="text" id="user-mobile" name="mobile" required
                               class="form-input w-full border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.users.fields.status') }}</label>
                        <select id="user-status" name="status" class="form-select w-full border-gray-300 rounded-lg">
                            <option value="active">{{ __('admin.users.status.active') }}</option>
                            <option value="suspended">{{ __('admin.users.status.suspended') }}</option>
                            <option value="pending_verification">{{ __('admin.users.status.pending_verification') }}</option>
                        </select>
                    </div>

                    <div id="password-field" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.users.fields.password') }}</label>
                        <input type="password" id="user-password" name="password"
                               class="form-input w-full border-gray-300 rounded-lg">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal()"
                            class="btn btn-secondary">
                        {{ __('admin.actions.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ __('admin.actions.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentPage = 1;
let isLoading = false;

// Load users data
function loadUsers(page = 1, search = '', status = '') {
    if (isLoading) return;
    isLoading = true;

    const params = new URLSearchParams({
        page: page,
        search: search,
        status: status,
        per_page: 15
    });

    fetch(`/api/admin/users?${params}`, {
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('api_token')}`,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderUsersTable(data.data);
            renderPagination(data.meta);
            updateStats();
        }
    })
    .catch(error => {
        console.error('Error loading users:', error);
        showNotification('{{ __("admin.messages.errors.operation_failed") }}', 'error');
    })
    .finally(() => {
        isLoading = false;
    });
}

// Render users table
function renderUsersTable(users) {
    const tbody = document.getElementById('users-table-body');

    if (users.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-8 text-gray-500">
                    {{ __('admin.messages.no_data') }}
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = users.map(user => `
        <tr class="border-b border-gray-100 hover:bg-gray-50">
            <td class="py-3 px-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                        <span class="text-xs font-medium text-gray-600">${user.name.charAt(0).toUpperCase()}</span>
                    </div>
                    <div>
                        <div class="font-medium text-gray-800">${user.name}</div>
                        <div class="text-sm text-gray-500">#${user.id}</div>
                    </div>
                </div>
            </td>
            <td class="py-3 px-4 text-gray-600">${user.mobile}</td>
            <td class="py-3 px-4">
                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full ${getStatusClass(user.status)}">
                    ${getStatusText(user.status)}
                </span>
            </td>
            <td class="py-3 px-4 text-gray-600">${user.total_bookings || 0}</td>
            <td class="py-3 px-4 text-gray-600">৳${(user.total_spent || 0).toLocaleString()}</td>
            <td class="py-3 px-4 text-gray-600">${formatDate(user.created_at)}</td>
            <td class="py-3 px-4 text-right">
                <div class="flex items-center justify-end space-x-2">
                    @can('editUsers', \App\Policies\AdminPolicy::class)
                    <button onclick="editUser(${user.id})"
                            class="text-blue-600 hover:text-blue-800 text-sm">
                        {{ __('admin.actions.edit') }}
                    </button>
                    @endcan

                    @can('suspendUsers', \App\Policies\AdminPolicy::class)
                    ${user.status === 'active' ?
                        `<button onclick="suspendUser(${user.id})" class="text-yellow-600 hover:text-yellow-800 text-sm">
                            {{ __('admin.users.suspend') }}
                        </button>` :
                        `<button onclick="activateUser(${user.id})" class="text-green-600 hover:text-green-800 text-sm">
                            {{ __('admin.users.activate') }}
                        </button>`
                    }
                    @endcan

                    @can('deleteUsers', \App\Policies\AdminPolicy::class)
                    <button onclick="deleteUser(${user.id})"
                            class="text-red-600 hover:text-red-800 text-sm">
                        {{ __('admin.actions.delete') }}
                    </button>
                    @endcan
                </div>
            </td>
        </tr>
    `).join('');
}

// Helper functions
function getStatusClass(status) {
    switch(status) {
        case 'active': return 'bg-green-100 text-green-800';
        case 'suspended': return 'bg-yellow-100 text-yellow-800';
        case 'pending_verification': return 'bg-purple-100 text-purple-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

function getStatusText(status) {
    const statusMap = {
        'active': '{{ __("admin.users.status.active") }}',
        'suspended': '{{ __("admin.users.status.suspended") }}',
        'pending_verification': '{{ __("admin.users.status.pending_verification") }}'
    };
    return statusMap[status] || status;
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString();
}

// Modal functions
function openCreateModal() {
    document.getElementById('modal-title').textContent = '{{ __("admin.users.create") }}';
    document.getElementById('user-form').reset();
    document.getElementById('user-id').value = '';
    document.getElementById('password-field').classList.remove('hidden');
    document.getElementById('user-modal').classList.remove('hidden');
    document.getElementById('user-modal').classList.add('flex');
}

function closeModal() {
    document.getElementById('user-modal').classList.add('hidden');
    document.getElementById('user-modal').classList.remove('flex');
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    loadUsers();

    // Search functionality
    let searchTimeout;
    document.getElementById('user-search').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadUsers(1, e.target.value, document.getElementById('status-filter').value);
        }, 500);
    });

    // Status filter
    document.getElementById('status-filter').addEventListener('change', function(e) {
        loadUsers(1, document.getElementById('user-search').value, e.target.value);
    });

    // Initialize Lucide icons
    lucide.createIcons();
});

// Show notification
function showNotification(message, type = 'info') {
    // Implementation depends on your notification system
    console.log(`${type}: ${message}`);
}
</script>
@endpush
@endsection
