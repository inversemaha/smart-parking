@extends('layouts.admin')

@section('title', __('admin.vehicles.title'))
@section('page-title', __('admin.vehicles.title'))

@section('content')
<div class="grid grid-cols-12 gap-6 mt-10">
    <!-- Vehicles List -->
    <div class="intro-y col-span-12">
        <div class="flex flex-wrap sm:flex-nowrap items-center mt-2 mb-4">
            <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary shadow-md mr-2">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> {{ __('admin.vehicles.add_new') }}
            </a>
            <div class="dropdown ml-0">
                <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                    <span class="w-5 h-5 flex items-center justify-center"><i class="w-4 h-4" data-lucide="download"></i></span>
                </button>
                <div class="dropdown-menu w-40">
                    <ul class="dropdown-content">
                        <li><a href="javascript:;" class="dropdown-item" onclick="exportCSV('vehicles')"><i data-lucide="file-text" class="w-4 h-4 mr-2"></i>{{ __('general.export_csv') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="hidden md:block mx-auto text-slate-500">{{ __('general.showing') }} <span id="row-count">0</span> {{ __('general.of') }} {{ $vehicles->count() }} {{ __('admin.vehicles.label_plural') }}</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <input type="text" id="search-input" class="form-control w-56 box pr-10" placeholder="{{ __('general.search') }} {{ __('admin.vehicles.label_plural') }}">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="intro-y overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">{{ __('admin.vehicles.fields.registration_number') }}</th>
                        <th class="whitespace-nowrap">{{ __('admin.vehicles.fields.brand') }} / {{ __('admin.vehicles.fields.model') }}</th>
                        <th class="text-center whitespace-nowrap">{{ __('admin.vehicles.fields.type') }}</th>
                        <th class="text-center whitespace-nowrap">{{ __('admin.vehicles.fields.verification_status') }}</th>
                        <th class="text-center whitespace-nowrap">{{ __('admin.vehicles.fields.owner') }}</th>
                        <th class="text-center whitespace-nowrap">{{ __('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody id="vehicle-table-body">
                    @forelse($vehicles as $vehicle)
                    <tr class="intro-x vehicle-row">
                        <td class="font-medium">{{ $vehicle->registration_number }}</td>
                        <td>
                            <div class="font-medium">{{ $vehicle->brand }} {{ $vehicle->model }}</div>
                            <div class="text-slate-500 text-xs">{{ $vehicle->color }}</div>
                        </td>
                        <td class="text-center">{{ ucfirst($vehicle->vehicle_type) }}</td>
                        <td class="text-center">
                            <span class="inline-block px-2 py-1 rounded text-xs font-sem

ibold {{ $vehicle->verification_status === 'verified' ? 'bg-green-100 text-green-800' : ($vehicle->verification_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($vehicle->verification_status) }}
                            </span>
                        </td>
                        <td class="text-center text-slate-500">{{ $vehicle->user->name ?? 'N/A' }}</td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a class="flex items-center mr-3" href="{{ route('admin.vehicles.show', $vehicle) }}">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i> {{ __('general.view') }}
                                </a>
                                <a class="flex items-center mr-3" href="{{ route('admin.vehicles.edit', $vehicle) }}">
                                    <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> {{ __('general.edit') }}
                                </a>
                                <a class="flex items-center text-danger cursor-pointer" onclick="deleteVehicle({{ $vehicle->id }})">
                                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> {{ __('general.delete') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-slate-500">{{ __('general.no_records_found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">{{ __('general.delete_confirmation') }}</h2>
                <button type="button" class="btn-close" data-tw-dismiss="modal" aria-label="Close"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>
            <div class="modal-body">
                {{ __('general.delete_confirmation_message') }}
            </div>
            <div class="modal-footer">
                <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20">{{ __('general.cancel') }}</button>
                <button type="button" class="btn btn-danger w-20" onclick="confirmDelete()">{{ __('general.delete') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    let deleteVehicleId = null;

    function deleteVehicle(id) {
        deleteVehicleId = id;
        const modal = document.getElementById('delete-modal');
        const tw = window.Tail;
        tw.Modal.getInstance(modal).show();
    }

    function confirmDelete() {
        if (deleteVehicleId) {
            fetch(`/api/admin/vehicles/${deleteVehicleId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            }).then(response => response.json()).then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Failed to delete vehicle');
                }
            });
        }
    }

    function exportCSV(type) {
        window.location.href = `/admin/${type}/export?format=csv`;
    }

    // Search functionality
    document.getElementById('search-input').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.vehicle-row');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        document.getElementById('row-count').innerText = visibleCount;
    });
</script>
@endsection
