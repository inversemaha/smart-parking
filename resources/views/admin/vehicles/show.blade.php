@extends('layouts.admin')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-4">
        <h1 class="text-lg font-medium text-slate-800 dark:text-slate-100">Vehicle Details</h1>
        <nav class="flex pt-2 sm:pt-0" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('admin.dashboard.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-600">Dashboard</a></li>
                <li class="text-slate-500 dark:text-slate-400">/</li>
                <li><a href="{{ route('admin.vehicles.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-600">Vehicles</a></li>
                <li class="text-slate-500 dark:text-slate-400">/</li>
                <li class="text-slate-600 dark:text-slate-300">{{ $vehicle->registration_number }}</li>
            </ol>
        </nav>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <!-- Main Details -->
        <div class="col-span-12 lg:col-span-8 space-y-6">
            <!-- Vehicle Info Card -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-start mb-6">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ $vehicle->brand }} {{ $vehicle->model }}</h2>
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        @if($vehicle->is_active) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @endif">
                        {{ $vehicle->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-slate-600 dark:text-slate-400 text-sm">Registration Number</label>
                        <p class="font-medium text-slate-900 dark:text-white mt-1 text-lg">{{ $vehicle->registration_number }}</p>
                    </div>
                    <div>
                        <label class="text-slate-600 dark:text-slate-400 text-sm">Vehicle Type</label>
                        <p class="font-medium text-slate-900 dark:text-white mt-1 capitalize">{{ $vehicle->vehicle_type }}</p>
                    </div>
                    <div>
                        <label class="text-slate-600 dark:text-slate-400 text-sm">Manufacture Year</label>
                        <p class="font-medium text-slate-900 dark:text-white mt-1">{{ $vehicle->manufacture_year }}</p>
                    </div>
                    <div>
                        <label class="text-slate-600 dark:text-slate-400 text-sm">Color</label>
                        <p class="font-medium text-slate-900 dark:text-white mt-1">{{ $vehicle->color }}</p>
                    </div>
                    <div>
                        <label class="text-slate-600 dark:text-slate-400 text-sm">Registered Owner</label>
                        <p class="font-medium text-slate-900 dark:text-white mt-1">{{ $vehicle->user->name }}</p>
                    </div>
                    <div>
                        <label class="text-slate-600 dark:text-slate-400 text-sm">Owner Email</label>
                        <p class="font-medium text-slate-900 dark:text-white mt-1 text-sm">{{ $vehicle->user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Verification Status -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Verification Status</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600 dark:text-slate-400">Status</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($vehicle->verification_status === 'verified') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($vehicle->verification_status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @endif">
                            {{ ucfirst($vehicle->verification_status) }}
                        </span>
                    </div>

                    @if($vehicle->verified_at)
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600 dark:text-slate-400">Verified At</span>
                        <span class="text-slate-900 dark:text-white">{{ $vehicle->verified_at->format('M d, Y \a\t H:i') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600 dark:text-slate-400">Verified By</span>
                        <span class="text-slate-900 dark:text-white">{{ $vehicle->verifiedBy->name ?? 'N/A' }}</span>
                    </div>
                    @endif

                    @if($vehicle->verification_notes)
                    <div class="mt-4 p-4 bg-slate-100 dark:bg-slate-700 rounded-lg">
                        <label class="text-slate-600 dark:text-slate-400 text-sm block mb-2">Verification Notes</label>
                        <p class="text-slate-900 dark:text-white">{{ $vehicle->verification_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Booking History -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Booking History</h3>
                <div class="overflow-x-auto">
                    @if($vehicle->bookings && count($vehicle->bookings) > 0)
                    <table class="w-full">
                        <thead class="border-b border-slate-200 dark:border-slate-700">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-slate-600 dark:text-slate-400">ID</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-slate-600 dark:text-slate-400">Location</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-slate-600 dark:text-slate-400">Duration</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-slate-600 dark:text-slate-400">Amount</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-slate-600 dark:text-slate-400">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach($vehicle->bookings as $booking)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700">
                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-white">#{{ $booking->id }}</td>
                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-white">{{ $booking->parking_location->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400">
                                    {{ $booking->check_in->format('M d') }} - {{ $booking->check_out->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-slate-900 dark:text-white">৳{{ number_format($booking->total_amount, 2) }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        @if($booking->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($booking->status === 'active') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        @elseif($booking->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @else bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200
                                        @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="text-slate-500 dark:text-slate-400 text-center py-8">No bookings found for this vehicle</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-span-12 lg:col-span-4 space-y-4">
            <!-- Actions -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <h3 class="font-bold text-slate-900 dark:text-white mb-4">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}" class="block w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-center rounded-lg font-medium transition-colors">
                        Edit Vehicle
                    </a>
                    @if($vehicle->verification_status === 'pending')
                    <button onclick="verifyVehicle({{ $vehicle->id }})" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                        Verify Vehicle
                    </button>
                    <button onclick="rejectVehicle({{ $vehicle->id }})" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                        Reject Vehicle
                    </button>
                    @endif
                </div>
            </div>

            <!-- System Info -->
            <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4">
                <h3 class="font-medium text-slate-900 dark:text-white mb-3 text-sm">System Information</h3>
                <div class="text-xs text-slate-600 dark:text-slate-400 space-y-2">
                    <p><strong>Created:</strong> {{ $vehicle->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Updated:</strong> {{ $vehicle->updated_at->format('M d, Y H:i') }}</p>
                    <p><strong>Vehicle ID:</strong> {{ $vehicle->id }}</p>
                    @if($vehicle->deleted_at)
                    <p class="text-red-600 dark:text-red-400"><strong>Deleted:</strong> {{ $vehicle->deleted_at->format('M d, Y H:i') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function verifyVehicle(vehicleId) {
    if (confirm('Are you sure you want to verify this vehicle?')) {
        fetch(`/admin/vehicles/${vehicleId}/verify`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Vehicle verified successfully');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => alert('Error: ' + error));
    }
}

function rejectVehicle(vehicleId) {
    const notes = prompt('Enter rejection reason:');
    if (notes) {
        fetch(`/admin/vehicles/${vehicleId}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ verification_notes: notes })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Vehicle rejected successfully');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => alert('Error: ' + error));
    }
}
</script>
@endsection
