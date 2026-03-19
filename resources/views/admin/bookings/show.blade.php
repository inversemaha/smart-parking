@extends('layouts.admin')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-4">
        <h1 class="text-lg font-medium text-slate-800 dark:text-slate-100">Booking Details</h1>
        <nav class="flex pt-2 sm:pt-0" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('admin.dashboard.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-600">Dashboard</a></li>
                <li class="text-slate-500 dark:text-slate-400">/</li>
                <li><a href="{{ route('admin.bookings.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-600">Bookings</a></li>
                <li class="text-slate-500 dark:text-slate-400">/</li>
                <li class="text-slate-600 dark:text-slate-300">{{ $booking->booking_number }}</li>
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

    <div class="grid grid-cols-12 gap-6">
        <!-- Main Info -->
        <div class="col-span-12 lg:col-span-8 space-y-6">
            <!-- Booking Header -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $booking->booking_number }}</h2>
                        <p class="text-slate-600 dark:text-slate-400 mt-1">Booking for {{ $booking->user->name }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-bold
                        @if($booking->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @elseif($booking->status === 'active') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                        @elseif($booking->status === 'confirmed') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                        @elseif($booking->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @else bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200
                        @endif">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-slate-600 dark:text-slate-400 text-sm">User</label>
                        <p class="font-medium text-slate-900 dark:text-white mt-1">{{ $booking->user->name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $booking->user->email }}</p>
                    </div>
                    <div>
                        <label class="text-slate-600 dark:text-slate-400 text-sm">Vehicle</label>
                        <p class="font-medium text-slate-900 dark:text-white mt-1">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $booking->vehicle->registration_number }}</p>
                    </div>
                    <div>
                        <label class="text-slate-600 dark:text-slate-400 text-sm">Location</label>
                        <p class="font-medium text-slate-900 dark:text-white mt-1">{{ $booking->parkingLocation->name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Code: {{ $booking->parkingLocation->code }}</p>
                    </div>
                </div>
            </div>

            <!-- Booking Timeline -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Booking Timeline</h3>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 text-slate-600 dark:text-slate-400 text-sm font-medium">Booking Started:</div>
                        <div class="text-slate-900 dark:text-white">{{ $booking->start_time->format('M d, Y H:i') }}</div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 text-slate-600 dark:text-slate-400 text-sm font-medium">Booking Ends:</div>
                        <div class="text-slate-900 dark:text-white">{{ $booking->end_time->format('M d, Y H:i') }}</div>
                    </div>
                    @if($booking->entry_time)
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 text-slate-600 dark:text-slate-400 text-sm font-medium">Vehicle Entered:</div>
                        <div class="text-slate-900 dark:text-white">{{ $booking->entry_time->format('M d, Y H:i') }}</div>
                    </div>
                    @endif
                    @if($booking->exit_time)
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 text-slate-600 dark:text-slate-400 text-sm font-medium">Vehicle Exited:</div>
                        <div class="text-slate-900 dark:text-white">{{ $booking->exit_time->format('M d, Y H:i') }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Pricing Details -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Pricing Details</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-slate-600 dark:text-slate-400">Duration:</span>
                        <span class="text-slate-900 dark:text-white font-medium">{{ $booking->duration_hours }} hour(s)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600 dark:text-slate-400">Hourly Rate:</span>
                        <span class="text-slate-900 dark:text-white font-medium">৳{{ number_format($booking->hourly_rate, 2) }}/hour</span>
                    </div>
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-3 mt-3 flex justify-between">
                        <span class="text-slate-900 dark:text-white font-bold">Total Amount:</span>
                        <span class="text-lg font-bold text-green-600 dark:text-green-400">৳{{ number_format($booking->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Status -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Payment Information</h3>
                <div class="flex items-center justify-between">
                    <span class="text-slate-600 dark:text-slate-400">Payment Status:</span>
                    <span class="px-4 py-2 rounded-full text-sm font-bold
                        @if($booking->payment_status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @elseif($booking->payment_status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @endif">
                        {{ ucfirst($booking->payment_status) }}
                    </span>
                </div>
                @if($booking->payment)
                <div class="mt-4 p-4 bg-slate-100 dark:bg-slate-700 rounded-lg">
                    <p class="text-sm text-slate-600 dark:text-slate-400"><strong>Transaction ID:</strong> {{ $booking->payment->transaction_id ?? 'N/A' }}</p>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-2"><strong>Payment Date:</strong> {{ $booking->payment->created_at->format('M d, Y H:i') }}</p>
                </div>
                @endif
            </div>

            @if($booking->notes)
            <!-- Notes -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Notes</h3>
                <p class="text-slate-600 dark:text-slate-400">{{ $booking->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-span-12 lg:col-span-4 space-y-4">
            <!-- Actions -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <h3 class="font-bold text-slate-900 dark:text-white mb-4">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.bookings.index') }}" class="block w-full px-4 py-2 bg-slate-300 hover:bg-slate-400 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-900 dark:text-white text-center rounded-lg font-medium transition-colors">
                        Back to Bookings
                    </a>
                    @if($booking->status === 'pending')
                    <button onclick="confirmBooking()" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                        Confirm Booking
                    </button>
                    @endif
                    @if(in_array($booking->status, ['pending', 'confirmed']))
                    <button onclick="cancelBooking()" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                        Cancel Booking
                    </button>
                    @endif
                </div>
            </div>

            <!-- System Info -->
            <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4">
                <h3 class="font-medium text-slate-900 dark:text-white mb-3 text-sm">System Information</h3>
                <div class="text-xs text-slate-600 dark:text-slate-400 space-y-2">
                    <p><strong>Created:</strong> {{ $booking->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Updated:</strong> {{ $booking->updated_at->format('M d, Y H:i') }}</p>
                    <p><strong>Booking ID:</strong> {{ $booking->id }}</p>
                </div>
            </div>

            @if($booking->cancelled_at)
            <!-- Cancellation Info -->
            <div class="bg-red-50 dark:bg-red-900 rounded-lg p-4 border border-red-200 dark:border-red-800">
                <h3 class="font-medium text-red-900 dark:text-red-100 mb-2 text-sm">Cancellation</h3>
                <div class="text-xs text-red-800 dark:text-red-200 space-y-1">
                    <p><strong>Cancelled At:</strong> {{ $booking->cancelled_at->format('M d, Y H:i') }}</p>
                    @if($booking->cancellation_reason)
                    <p><strong>Reason:</strong> {{ $booking->cancellation_reason }}</p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function confirmBooking() {
    if (confirm('Are you sure you want to confirm this booking?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.bookings.confirm', $booking->id) }}`;
        form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
        document.body.appendChild(form);
        form.submit();
    }
}

function cancelBooking() {
    const reason = prompt('Enter cancellation reason:');
    if (reason) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.bookings.cancel', $booking->id) }}`;
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
