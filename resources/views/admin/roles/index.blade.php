@extends('layouts.app')

@section('title', __('general.roles'))
@section('page-title', __('general.roles'))

@php
$breadcrumb = [
    ['title' => __('general.admin'), 'url' => route('admin.dashboard.index')],
    ['title' => __('general.permissions'), 'url' => route('admin.permissions.index')],
    ['title' => __('general.roles')]
];
@endphp

@section('content')
<div class="grid grid-cols-12 gap-6">
    <!-- Roles Management -->
    <div class="col-span-12 xl:col-span-8">
        <div class="box p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium">{{ __('general.roles') }}</h3>
                <button type="button" class="btn btn-primary" onclick="openCreateRoleModal()">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    {{ __('general.create_role') }}
                </button>
            </div>

            <div class="space-y-4">
                @forelse($roles as $role)
                    <div class="border border-slate-200 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h4 class="font-medium text-lg">{{ $role->name }}</h4>
                                    <span class="badge badge-secondary">{{ $role->users->count() }} {{ __('general.users') }}</span>
                                </div>
                                @if($role->description)
                                    <p class="text-slate-600 mb-3">{{ $role->description }}</p>
                                @endif
                                <div class="flex flex-wrap gap-1">
                                    @foreach($role->permissions as $permission)
                                        <span class="badge badge-outline-primary">{{ $permission->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="editRole({{ $role->id }})">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                @if($role->name !== 'super-admin')
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteRole({{ $role->id }})">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-500">
                        {{ __('general.no_roles_found') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-span-12 xl:col-span-4">
        <div class="box p-6">
            <h3 class="text-lg font-medium mb-6">{{ __('general.role_statistics') }}</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <span class="text-blue-700">{{ __('general.total_roles') }}</span>
                    <span class="font-medium text-blue-800">{{ $roles->count() }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <span class="text-green-700">{{ __('general.total_permissions') }}</span>
                    <span class="font-medium text-green-800">{{ $permissions->count() }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                    <span class="text-purple-700">{{ __('general.active_users_with_roles') }}</span>
                    <span class="font-medium text-purple-800">{{ $roles->sum(fn($r) => $r->users->count()) }}</span>
                </div>
            </div>
        </div>

        <!-- Permission Quick Reference -->
        <div class="box p-6 mt-6">
            <h3 class="text-lg font-medium mb-6">{{ __('general.permission_modules') }}</h3>
            <div class="space-y-2">
                @php
                    $modules = $permissions->groupBy('module');
                @endphp
                @foreach($modules as $module => $modulePermissions)
                    <div class="flex items-center justify-between p-2 hover:bg-slate-50 rounded">
                        <span class="text-slate-700 capitalize">{{ $module }}</span>
                        <span class="badge badge-outline-secondary">{{ $modulePermissions->count() }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Create Role Modal -->
<div id="createRoleModal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">{{ __('general.create_role') }}</h2>
                    <button type="button" class="btn btn-rounded-secondary hidden sm:flex p-1" data-tw-dismiss="modal">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12">
                        <label for="role_name" class="form-label">{{ __('general.role_name') }}</label>
                        <input type="text" id="role_name" name="name" class="form-control" placeholder="{{ __('general.enter_role_name') }}" required>
                    </div>
                    <div class="col-span-12">
                        <label for="role_description" class="form-label">{{ __('general.description') }}</label>
                        <textarea id="role_description" name="description" class="form-control" placeholder="{{ __('general.enter_description') }}"></textarea>
                    </div>
                    <div class="col-span-12">
                        <label class="form-label">{{ __('general.permissions') }}</label>
                        <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border p-3 rounded">
                            @foreach($permissions->groupBy('module') as $module => $modulePermissions)
                                <div class="col-span-2 border-b pb-2 mb-2">
                                    <h5 class="font-medium capitalize">{{ $module }}</h5>
                                </div>
                                @foreach($modulePermissions as $permission)
                                    <div class="flex items-center">
                                        <input type="checkbox" id="perm_{{ $permission->id }}" name="permissions[]" value="{{ $permission->id }}" class="form-check">
                                        <label for="perm_{{ $permission->id }}" class="ml-2 text-sm">{{ $permission->name }}</label>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary w-20 mr-1" data-tw-dismiss="modal">{{ __('general.cancel') }}</button>
                    <button type="submit" class="btn btn-primary w-20">{{ __('general.create') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Role Modal -->
<div id="editRoleModal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div id="editRoleForm">
                <!-- Dynamic content loaded here -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openCreateRoleModal() {
    const modal = new bootstrap.Modal(document.getElementById('createRoleModal'));
    modal.show();
}

function editRole(id) {
    // Load role data and show edit modal
    fetch(`/admin/roles/${id}/edit`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('editRoleForm').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('editRoleModal'));
            modal.show();
        });
}

function deleteRole(id) {
    if (confirm('{{ __("general.confirm_delete_role") }}')) {
        // Implement delete functionality
        console.log('Delete role:', id);
    }
}
</script>
@endpush
