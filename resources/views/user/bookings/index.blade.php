@extends('layouts.user')

@section('title', __('bookings.my_bookings'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('bookings.my_bookings') }}</h1>
                <p class="text-gray-600 mt-1">{{ __('bookings.view_history') }}</p>
            </div>
            <a href="{{ route('bookings.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                {{ __('parking.book_slot') }}
            </a>
        </div>
    </div>

    <!-- Bookings List -->
    @if($bookings->count() > 0)
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-3">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $booking->parking_slot->area->name ?? 'N/A' }} - {{ __('parking.slot') }} {{ $booking->parking_slot->slot_number ?? 'N/A' }}
                                </h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                    @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                    @elseif($booking->status === 'active') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ __('bookings.' . $booking->status) }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm text-gray-600">
                                <div>
                                    <p class="font-medium text-gray-900">{{ __('vehicles.registration_number') }}</p>
                                    <p>{{ $booking->vehicle->registration_number ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ __('parking.start_time') }}</p>
                                    <p>{{ $booking->start_time->format('M j, Y g:i A') }}</p>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ __('parking.end_time') }}</p>
                                    <p>{{ $booking->end_time->format('M j, Y g:i A') }}</p>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ __('payments.amount') }}</p>
                                    <p class="text-lg font-semibold text-gray-900">৳{{ number_format($booking->total_amount, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="ml-6 flex flex-col space-y-2">
                            <a href="{{ route('bookings.show', $booking) }}"
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('general.view') }}
                            </a>

                            @if($booking->status === 'confirmed' && !$booking->payment_status)
                                <a href="{{ route('payments.create', $booking) }}"
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    {{ __('payments.pay_now') }}
                                </a>
                            @endif

                            @if(in_array($booking->status, ['confirmed', 'pending']) && $booking->start_time->isFuture())
                                <button type="button"
                                        onclick="cancelBooking({{ $booking->id }})"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    {{ __('general.cancel') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($bookings->hasPages())
            <div class="mt-8">
                {{ $bookings->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('bookings.no_bookings') }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ __('bookings.start_booking_now') }}</p>
            <div class="mt-6">
                <a href="{{ route('bookings.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    {{ __('parking.book_slot') }}
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Cancel Booking Modal (Simple Implementation) -->
<script>
function cancelBooking(bookingId) {
    if (confirm('{{ __("general.confirm_cancel") }}')) {
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/dashboard/bookings/${bookingId}/cancel`;

        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);

        // Add method override
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
