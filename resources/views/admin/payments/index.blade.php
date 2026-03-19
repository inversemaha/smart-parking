@extends('layouts.admin')

@section('title', 'Payments')

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
                    <li class="text-primary font-medium">Payments</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- END: Top Bar -->

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <!-- BEGIN: Payments List -->
            <div class="intro-y box">
                <div class="flex flex-col sm:flex-row sm:items-center p-5 border-b border-slate-200/60">
                    <h2 class="font-bold text-base mr-auto">Payment Transactions</h2>
                    <div class="flex items-center gap-3 w-full sm:w-auto mt-3 sm:mt-0">
                    </div>
                </div>

                <!-- Search & Filters -->
                <div class="p-5 border-b border-slate-200/60">
                    <form method="GET" action="{{ route('admin.payments.index') }}" class="flex flex-col gap-4">
                        <div class="flex gap-3 flex-col lg:flex-row">
                            <!-- Search -->
                            <div class="flex-1">
                                <div class="relative">
                                    <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                                    <input type="text" name="search" placeholder="Search by Payment ID or User..." value="{{ request('search') }}"
                                        class="pl-10 pr-4 py-2 w-full border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                            </div>

                            <!-- Status Filter -->
                            <select name="status" class="px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">All Status</option>
                                <option value="initiated" {{ request('status') === 'initiated' ? 'selected' : '' }}>Initiated</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>

                            <!-- Payment Method Filter -->
                            <select name="payment_method" class="px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">All Methods</option>
                                <option value="card" {{ request('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
                                <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="bkash" {{ request('payment_method') === 'bkash' ? 'selected' : '' }}>bKash</option>
                                <option value="nagad" {{ request('payment_method') === 'nagad' ? 'selected' : '' }}>Nagad</option>
                                <option value="wallet" {{ request('payment_method') === 'wallet' ? 'selected' : '' }}>Wallet</option>
                            </select>

                            <!-- Filter Button -->
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors whitespace-nowrap">
                                <i data-lucide="filter" class="w-4 h-4 inline-block mr-2"></i>Filter
                            </button>

                            <!-- Reset Filter -->
                            @if(request('search') || request('status') || request('payment_method'))
                                <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors whitespace-nowrap">
                                    <i data-lucide="x" class="w-4 h-4 inline-block mr-2"></i>Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Payments Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 border-b border-slate-200/60">
                            <tr>
                                <th class="px-5 py-3 text-left font-semibold">Payment ID</th>
                                <th class="px-5 py-3 text-left font-semibold">User</th>
                                <th class="px-5 py-3 text-left font-semibold">Amount</th>
                                <th class="px-5 py-3 text-left font-semibold">Status</th>
                                <th class="px-5 py-3 text-left font-semibold">Method</th>
                                <th class="px-5 py-3 text-left font-semibold">Gateway</th>
                                <th class="px-5 py-3 text-left font-semibold">Date</th>
                                <th class="px-5 py-3 text-center font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr class="border-b border-slate-200/60 hover:bg-slate-50 transition">
                                    <td class="px-5 py-4">
                                        <span class="font-semibold text-primary">{{ $payment->payment_id }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 bg-gradient-to-br from-primary to-primary/60 rounded-full flex items-center justify-center text-white font-bold text-xs">
                                                {{ strtoupper(substr($payment->user->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold">{{ $payment->user->name ?? 'N/A' }}</p>
                                                <p class="text-xs text-slate-500">{{ $payment->user->email ?? '' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="font-semibold text-green-600">{{ $payment->currency ?? 'BDT' }} {{ number_format($payment->amount, 2) }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        @php
                                            $statusColors = [
                                                'initiated' => 'bg-blue-100 text-blue-800',
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'processing' => 'bg-purple-100 text-purple-800',
                                                'paid' => 'bg-green-100 text-green-800',
                                                'failed' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$payment->status] ?? 'bg-slate-100 text-slate-800' }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="text-slate-600">{{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'N/A')) }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="text-slate-600">{{ ucfirst($payment->gateway ?? 'Manual') }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="text-slate-600">{{ $payment->initiated_at?->format('M d, Y H:i') ?? 'N/A' }}</span>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <a href="{{ route('admin.payments.show', $payment) }}" class="inline-flex items-center justify-center gap-2 px-3 py-2 text-primary hover:bg-primary/10 rounded-lg transition-colors">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                            <span class="text-xs font-semibold">View</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i data-lucide="inbox" class="w-12 h-12 text-slate-300 mb-3"></i>
                                            <p class="text-slate-500 font-semibold">No payments found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($payments->hasPages())
                    <div class="p-5 border-t border-slate-200/60">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>
            <!-- END: Payments List -->
        </div>
    </div>
</div>
@endsection
