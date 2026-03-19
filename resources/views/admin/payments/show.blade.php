@extends('layouts.admin')

@section('title', 'Payment Details - ' . $payment->payment_id)

@section('content')
<div class="content">
    <!-- BEGIN: Top Bar -->
    <div class="top-bar-boxed">
        <div class="flex flex-col md:flex-row items-center justify-between gap-3 w-full">
            <nav aria-label="breadcrumb" class="flex flex-1">
                <ol class="flex items-center whitespace-nowrap">
                    <li>
                        <a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" class="w-4 h-4 mx-2 stroke-slate-400">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </li>
                    <li>
                        <a href="{{ route('admin.payments.index') }}">Payments</a>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" class="w-4 h-4 mx-2 stroke-slate-400">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </li>
                    <li class="text-primary font-medium">{{ $payment->payment_id }}</li>
                </ol>
            </nav>
            <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-lg hover:bg-slate-50">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span class="text-sm font-semibold">Back</span>
            </a>
        </div>
    </div>
    <!-- END: Top Bar -->

    <div class="grid grid-cols-12 gap-6">
        <!-- Main Content -->
        <div class="col-span-12 lg:col-span-8">
            <!-- Payment Details Card -->
            <div class="intro-y box">
                <div class="flex items-center justify-between p-5 border-b border-slate-200/60 bg-gradient-to-r from-primary/5 to-primary/10">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Payment Information</h3>
                        <p class="text-sm text-slate-500 mt-1">Transaction Reference: <span class="font-semibold text-primary">{{ $payment->payment_id }}</span></p>
                    </div>
                    @php
                        $statusColors = [
                            'initiated' => 'bg-blue-100 text-blue-800',
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'processing' => 'bg-purple-100 text-purple-800',
                            'paid' => 'bg-green-100 text-green-800',
                            'failed' => 'bg-red-100 text-red-800',
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-bold {{ $statusColors[$payment->status] ?? 'bg-slate-100 text-slate-800' }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>

                <div class="p-5">
                    <!-- Payment Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Amount & Currency -->
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase block mb-2">Amount</label>
                            <p class="text-2xl font-bold text-green-600">{{ $payment->currency ?? 'BDT' }} {{ number_format($payment->amount, 2) }}</p>
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase block mb-2">Payment Method</label>
                            <p class="text-lg font-semibold text-slate-900">{{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'Unknown')) }}</p>
                        </div>

                        <!-- Gateway -->
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase block mb-2">Payment Gateway</label>
                            <p class="text-lg font-semibold text-slate-900">{{ ucfirst($payment->gateway ?? 'Manual') }}</p>
                        </div>

                        <!-- Gateway Transaction ID -->
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase block mb-2">Gateway Transaction ID</label>
                            <p class="text-sm font-mono text-slate-700 break-all">{{ $payment->gateway_transaction_id ?? 'N/A' }}</p>
                        </div>

                        <!-- Initiated At -->
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase block mb-2">Initiated Date</label>
                            <p class="text-lg font-semibold text-slate-900">{{ $payment->initiated_at?->format('M d, Y H:i:s') ?? 'N/A' }}</p>
                        </div>

                        <!-- Paid At -->
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase block mb-2">Paid Date</label>
                            <p class="text-lg font-semibold text-slate-900">{{ $payment->paid_at?->format('M d, Y H:i:s') ?? 'Not yet paid' }}</p>
                        </div>

                        <!-- Failed At (if applicable) -->
                        @if($payment->failed_at)
                            <div>
                                <label class="text-xs font-semibold text-slate-500 uppercase block mb-2">Failed Date</label>
                                <p class="text-lg font-semibold text-red-600">{{ $payment->failed_at?->format('M d, Y H:i:s') }}</p>
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-slate-500 uppercase block mb-2">Failure Reason</label>
                                <p class="text-sm text-red-600">{{ $payment->failure_reason ?? 'No reason provided' }}</p>
                            </div>
                        @endif

                        <!-- Notes -->
                        @if($payment->notes)
                            <div class="md:col-span-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase block mb-2">Notes</label>
                                <p class="text-sm text-slate-700">{{ $payment->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Gateway Response (if available) -->
                    @if($payment->gateway_response)
                        <div class="border-t border-slate-200/60 pt-6">
                            <h4 class="text-sm font-bold text-slate-900 mb-3">Gateway Response</h4>
                            <div class="bg-slate-50 p-4 rounded-lg overflow-x-auto">
                                <pre class="text-xs text-slate-700">{{ json_encode($payment->gateway_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- SSL Logs (if available) -->
            @if($payment->sslcommerzLogs->isNotEmpty())
                <div class="intro-y box mt-6">
                    <div class="flex items-center p-5 border-b border-slate-200/60 bg-slate-50">
                        <h3 class="text-base font-bold text-slate-900">Transaction Logs</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200/60">
                                <tr>
                                    <th class="px-5 py-3 text-left font-semibold">Date</th>
                                    <th class="px-5 py-3 text-left font-semibold">Status</th>
                                    <th class="px-5 py-3 text-left font-semibold">Response</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payment->sslcommerzLogs as $log)
                                    <tr class="border-b border-slate-200/60 hover:bg-slate-50">
                                        <td class="px-5 py-3">{{ $log->created_at?->format('M d, Y H:i:s') }}</td>
                                        <td class="px-5 py-3">
                                            <span class="px-2 py-1 rounded text-xs font-semibold bg-slate-100 text-slate-800">{{ $log->status ?? 'Unknown' }}</span>
                                        </td>
                                        <td class="px-5 py-3">
                                            <span class="text-xs text-slate-600">{{ substr($log->response, 0, 100) }}...</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-span-12 lg:col-span-4">
            <!-- User Information -->
            <div class="intro-y box">
                <div class="flex items-center p-5 border-b border-slate-200/60 bg-slate-50">
                    <h3 class="font-bold text-slate-900">Customer Information</h3>
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary to-primary/60 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr($payment->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">{{ $payment->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-slate-500">{{ $payment->user->user_type ?? 'User' }}</p>
                        </div>
                    </div>

                    <div class="space-y-3 border-t border-slate-200/60 pt-4">
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase">Email</label>
                            <p class="text-sm text-slate-700 break-all">{{ $payment->user->email }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase">Phone</label>
                            <p class="text-sm text-slate-700">{{ $payment->user->mobile ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <a href="{{ route('admin.users.show', $payment->user) }}" class="mt-4 w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-lg hover:bg-primary/20 transition-colors font-semibold text-sm">
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>View Profile
                    </a>
                </div>
            </div>

            <!-- Related Booking (if applicable) -->
            @if($payment->booking)
                <div class="intro-y box mt-6">
                    <div class="flex items-center p-5 border-b border-slate-200/60 bg-slate-50">
                        <h3 class="font-bold text-slate-900">Related Booking</h3>
                    </div>
                    <div class="p-5">
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs font-semibold text-slate-500 uppercase">Booking Reference</label>
                                <p class="text-sm font-semibold text-primary">{{ $payment->booking->booking_reference ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-500 uppercase">Duration</label>
                                <p class="text-sm text-slate-700">{{ $payment->booking->start_time?->format('M d, H:i') ?? 'N/A' }} to {{ $payment->booking->end_time?->format('M d, H:i') ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-500 uppercase">Status</label>
                                <p class="text-sm font-semibold text-primary">{{ ucfirst($payment->booking->status ?? 'Unknown') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.bookings.show', $payment->booking) }}" class="mt-4 w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-lg hover:bg-primary/20 transition-colors font-semibold text-sm">
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>View Booking
                        </a>
                    </div>
                </div>
            @endif

            <!-- Timeline -->
            <div class="intro-y box mt-6">
                <div class="flex items-center p-5 border-b border-slate-200/60 bg-slate-50">
                    <h3 class="font-bold text-slate-900">Payment Timeline</h3>
                </div>
                <div class="p-5">
                    <div class="space-y-4">
                        <!-- Initiated -->
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="clock" class="w-4 h-4 text-blue-600"></i>
                                </div>
                                <div class="w-0.5 h-12 bg-slate-200 my-2"></div>
                            </div>
                            <div class="flex-1 pt-1">
                                <p class="font-semibold text-slate-900">Initiated</p>
                                <p class="text-xs text-slate-500">{{ $payment->initiated_at?->format('M d, Y H:i:s') ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Processing/Pending -->
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full {{ $payment->isPending() ? 'bg-yellow-100' : 'bg-slate-100' }} flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="loader" class="w-4 h-4 {{ $payment->isPending() ? 'text-yellow-600' : 'text-slate-400' }}"></i>
                                </div>
                                <div class="w-0.5 h-12 bg-slate-200 my-2"></div>
                            </div>
                            <div class="flex-1 pt-1">
                                <p class="font-semibold {{ $payment->isPending() ? 'text-slate-900' : 'text-slate-400' }}">Processing</p>
                                <p class="text-xs {{ $payment->isPending() ? 'text-slate-500' : 'text-slate-400' }}">Waiting for gateway response</p>
                            </div>
                        </div>

                        <!-- Completed (Success or Failed) -->
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                @if($payment->isSuccessful())
                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="check-circle" class="w-4 h-4 text-green-600"></i>
                                    </div>
                                @elseif($payment->status === 'failed')
                                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="x-circle" class="w-4 h-4 text-red-600"></i>
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="help-circle" class="w-4 h-4 text-slate-400"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 pt-1">
                                <p class="font-semibold {{ $payment->isSuccessful() ? 'text-green-600' : ($payment->status === 'failed' ? 'text-red-600' : 'text-slate-400') }}">
                                    {{ $payment->isSuccessful() ? 'Successfully Paid' : ($payment->status === 'failed' ? 'Payment Failed' : 'Pending Completion') }}
                                </p>
                                <p class="text-xs {{ $payment->isSuccessful() ? 'text-slate-500' : ($payment->status === 'failed' ? 'text-slate-500' : 'text-slate-400') }}">
                                    {{ $payment->paid_at?->format('M d, Y H:i:s') ?? ($payment->failed_at?->format('M d, Y H:i:s') ?? 'Not completed') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="intro-y box mt-6">
                <div class="flex items-center p-5 border-b border-slate-200/60 bg-slate-50">
                    <h3 class="font-bold text-slate-900">System Information</h3>
                </div>
                <div class="p-5">
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase">Created</label>
                            <p class="text-xs text-slate-600">{{ $payment->created_at?->format('M d, Y H:i:s') }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase">Last Updated</label>
                            <p class="text-xs text-slate-600">{{ $payment->updated_at?->format('M d, Y H:i:s') }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase">Payment ID</label>
                            <p class="text-xs font-mono text-slate-600">{{ $payment->id }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
