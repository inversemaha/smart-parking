@extends('layouts.app')

@section('title', __('general.permissions'))
@section('page-title', __('general.permissions'))

@php
$breadcrumb = [
    ['title' => __('general.admin'), 'url' => route('admin.dashboard.index')],
    ['title' => __('general.permissions')]
];
@endphp

@section('content')
<div class="flex flex-col lg:flex-row gap-6">
    <!-- Permissions List -->
    <div class="lg:w-2/3">
        <div class="box p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium">{{ __('general.permissions') }}</h3>
                <button type="button" class="btn btn-primary" onclick="openCreatePermissionModal()">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    {{ __('general.create_permission') }}
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="table table-bordered">
                    <thead>
                        <tr class="bg-slate-50">
                            <th>{{ __('general.name') }}</th>
                            <th>{{ __('general.module') }}</th>
                            <th>{{ __('general.description') }}</th>
                            <th>{{ __('general.roles') }}</th>
                            <th>{{ __('general.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $permission)
                            <tr>
                                <td class="font-medium">{{ $permission->name }}</td>
                                <td>
                                    <span class="badge badge-outline-secondary">{{ $permission->module }}</span>
                                </td>
                                <td class="text-sm text-slate-600">{{ $permission->description }}</td>
                                <td>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($permission->roles as $role)
                                            <span class="badge badge-primary">{{ $role->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <button type="button" class="text-primary hover:text-primary-600" onclick="editPermission({{ $permission->id }})">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </button>
                                        <button type="button" class="text-danger hover:text-danger-600" onclick="deletePermission({{ $permission->id }})">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-slate-500">
                                    {{ __('general.no_permissions_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Roles Quick View -->
    <div class="lg:w-1/3">
        <div class="box p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium">{{ __('general.roles_summary') }}</h3>
                <a href="{{ route('admin.permissions.roles') }}" class="text-primary hover:text-primary-600">
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <div class="space-y-3">
                @foreach($roles as $role)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                        <div>
                            <h4 class="font-medium">{{ $role->name }}</h4>
                            <p class="text-sm text-slate-600">{{ $role->permissions->count() }} {{ __('general.permissions') }}</p>
                        </div>
                        <span class="badge badge-secondary">{{ $role->users->count() }} {{ __('general.users') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Create Permission Modal -->
<div id="createPermissionModal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.permissions.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">{{ __('general.create_permission') }}</h2>
                    <button type="button" class="btn btn-rounded-secondary hidden sm:flex p-1" data-tw-dismiss="modal">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12">
                        <label for="name" class="form-label">{{ __('general.name') }}</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="{{ __('general.enter_permission_name') }}" required>
                    </div>
                    <div class="col-span-12">
                        <label for="module" class="form-label">{{ __('general.module') }}</label>
                        <select id="module" name="module" class="form-control" required>
                            <option value="">{{ __('general.select_module') }}</option>
                            <option value="admin">{{ __('general.admin') }}</option>
                            <option value="user">{{ __('general.user') }}</option>
                            <option value="vehicle">{{ __('general.vehicle') }}</option>
                            <option value="booking">{{ __('general.booking') }}</option>
                            <option value="payment">{{ __('general.payment') }}</option>
                            <option value="parking">{{ __('general.parking') }}</option>
                            <option value="gate">{{ __('general.gate') }}</option>
                        </select>
                    </div>
                    <div class="col-span-12">
                        <label for="description" class="form-label">{{ __('general.description') }}</label>
                        <textarea id="description" name="description" class="form-control" placeholder="{{ __('general.enter_description') }}"></textarea>
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

@endsection

@push('scripts')
<script>
function openCreatePermissionModal() {
    const modal = new bootstrap.Modal(document.getElementById('createPermissionModal'));
    modal.show();
}

function editPermission(id) {
    // Implement edit functionality
    console.log('Edit permission:', id);
}

function deletePermission(id) {
    if (confirm('{{ __("general.confirm_delete_permission") }}')) {
        // Implement delete functionality
        console.log('Delete permission:', id);
    }
}
</script>
@endpush
