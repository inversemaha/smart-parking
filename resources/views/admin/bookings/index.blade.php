@extends('layouts.admin')

@section('content')
<div class="content">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-4">
        <h1 class="text-lg font-medium text-slate-800 dark:text-slate-100">{{ __('admin.bookings.title') }}</h1>
        <nav class="flex pt-2 sm:pt-0" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('admin.dashboard.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-600">{{ __('admin.dashboard') }}</a></li>
                <li class="text-slate-500 dark:text-slate-400">/</li>
                <li class="text-slate-600 dark:text-slate-300">{{ __('admin.bookings.title') }}</li>
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
                <div class="flex-1">
                    <input type="text" id="search" placeholder="{{ __('admin.bookings.search_placeholder') }}"
                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <select id="status-filter" class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">{{ __('general.all_status') }}</option>
                    <option value="pending">{{ __('admin.bookings.status_pending') }}</option>
                    <option value="confirmed">{{ __('admin.bookings.status_confirmed') }}</option>
                    <option value="active">{{ __('admin.bookings.status_active') }}</option>
                    <option value="completed">{{ __('admin.bookings.status_completed') }}</option>
                    <option value="cancelled">{{ __('admin.bookings.status_cancelled') }}</option>
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
                        <th class="px-6 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-400">Booking #</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-400">User</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-400">Vehicle</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-400">Location</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-400">Period</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-400">Amount</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-400">Status</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-slate-600 dark:text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700 booking-row" 
                        data-booking="{{ strtolower($booking->booking_number) }}" 
                        data-user="{{ strtolower($booking->user->name) }}"
                        data-vehicle="{{ strtolower($booking->vehicle->brand . ' ' . $booking->vehicle->model) }}"
                        data-status="{{ strtolower($booking->status) }}">
                        <td class="px-6 py-4 text-slate-900 dark:text-white font-mono font-medium">{{ $booking->booking_number }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-900 dark:text-white">{{ $booking->user->name }}</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $booking->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-900 dark:text-white">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</td>
                        <td class="px-6 py-4 text-slate-900 dark:text-white">{{ $booking->parkingLocation->name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                            {{ $booking->start_time->format('M d') }} - {{ $booking->end_time->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-slate-900 dark:text-white font-medium">৳{{ number_format($booking->total_amount, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                @if($booking->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($booking->status === 'active') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @elseif($booking->status === 'confirmed') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                @elseif($booking->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @else bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200
                                @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200 rounded hover:bg-blue-200 transition-colors">
                                    View
                                </a>
                                @if($booking->status === 'pending')
                                <button onclick="confirmBooking({{ $booking->id }})" class="px-3 py-1 text-xs bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200 rounded hover:bg-green-200 transition-colors">
                                    Confirm
                                </button>
                                @endif
                                @if(in_array($booking->status, ['pending', 'confirmed']))
                                <button onclick="cancelBooking({{ $booking->id }})" class="px-3 py-1 text-xs bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200 rounded hover:bg-red-200 transition-colors">
                                    Cancel
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                            No bookings found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $bookings->links() }}
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
    
    document.querySelectorAll('.booking-row').forEach(row => {
        const booking = row.getAttribute('data-booking');
        const user = row.getAttribute('data-user');
        const vehicle = row.getAttribute('data-vehicle');
        const status = row.getAttribute('data-status');
        
        const matchSearch = booking.includes(searchTerm) || user.includes(searchTerm) || vehicle.includes(searchTerm);
        const matchStatus = !statusFilter || status === statusFilter;
        
        row.style.display = matchSearch && matchStatus ? '' : 'none';
    });
}

function confirmBooking(bookingId) {
    if (confirm('Are you sure you want to confirm this booking?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.bookings.confirm', '') }}/${bookingId}`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function cancelBooking(bookingId) {
    const reason = prompt('Enter cancellation reason:');
    if (reason) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.bookings.cancel', '') }}/${bookingId}`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="reason" value="${reason}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
