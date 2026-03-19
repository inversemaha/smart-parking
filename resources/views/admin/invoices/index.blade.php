@extends('layouts.admin')

@section('title', __('admin.invoices.title'))

@section('content')
<div class="content">
    <!-- BEGIN: Top Bar -->
    <div class="top-bar-boxed">
        <div class="flex flex-col md:flex-row items-center justify-between gap-3 w-full">
            <nav aria-label="breadcrumb" class="flex flex-1">
                <ol class="flex items-center whitespace-nowrap">
                    <li>
                        <a href="{{ route('admin.dashboard.index') }}">{{ __('admin.dashboard') }}</a>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" class="w-4 h-4 mx-2 stroke-slate-400">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </li>
                    <li class="text-primary font-medium">{{ __('admin.invoices.title') }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- END: Top Bar -->

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <!-- BEGIN: Invoices List -->
            <div class="intro-y box">
                <div class="flex flex-col sm:flex-row sm:items-center p-5 border-b border-slate-200/60">
                    <h2 class="font-bold text-base mr-auto">{{ __('admin.invoices.management') }}</h2>
                    <div class="flex items-center gap-3 w-full sm:w-auto mt-3 sm:mt-0">
                    </div>
                </div>

                <!-- Search & Filters -->
                <div class="p-5 border-b border-slate-200/60">
                    <form method="GET" action="{{ route('admin.invoices.index') }}" class="flex flex-col gap-4">
                        <div class="flex gap-3 flex-col lg:flex-row">
                            <!-- Search -->
                            <div class="flex-1">
                                <div class="relative">
                                    <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                                    <input type="text" name="search" placeholder="{{ __('admin.invoices.search_placeholder') }}" value="{{ request('search') }}"
                                        class="pl-10 pr-4 py-2 w-full border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                            </div>

                            <!-- Status Filter -->
                            <select name="status" class="px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">{{ __('general.all_status') }}</option>
                                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>{{ __('admin.invoices.status.paid') }}</option>
                                <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>{{ __('admin.invoices.status.unpaid') }}</option>
                                <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>{{ __('admin.invoices.status.overdue') }}</option>
                            </select>

                            <!-- Filter Button -->
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors whitespace-nowrap">
                                <i data-lucide="filter" class="w-4 h-4 inline-block mr-2"></i>{{ __('general.filter') }}
                            </button>

                            <!-- Reset Filter -->
                            @if(request('search') || request('status'))
                                <a href="{{ route('admin.invoices.index') }}" class="px-4 py-2 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors whitespace-nowrap">
                                    <i data-lucide="x" class="w-4 h-4 inline-block mr-2"></i>{{ __('general.reset') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Invoices Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 border-b border-slate-200/60">
                            <tr>
                                <th class="px-5 py-3 text-left font-semibold">Invoice #</th>
                                <th class="px-5 py-3 text-left font-semibold">Customer</th>
                                <th class="px-5 py-3 text-left font-semibold">Amount</th>
                                <th class="px-5 py-3 text-left font-semibold">Status</th>
                                <th class="px-5 py-3 text-left font-semibold">Issued Date</th>
                                <th class="px-5 py-3 text-left font-semibold">Due Date</th>
                                <th class="px-5 py-3 text-center font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                                <tr class="border-b border-slate-200/60 hover:bg-slate-50 transition">
                                    <td class="px-5 py-4">
                                        <span class="font-semibold text-primary">{{ $invoice->invoice_number }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 bg-gradient-to-br from-primary to-primary/60 rounded-full flex items-center justify-center text-white font-bold text-xs">
                                                {{ strtoupper(substr($invoice->user->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold">{{ $invoice->user->name ?? 'N/A' }}</p>
                                                <p class="text-xs text-slate-500">{{ $invoice->user->email ?? '' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="font-semibold text-green-600">{{ $invoice->currency ?? 'BDT' }} {{ number_format($invoice->amount + ($invoice->tax_amount ?? 0) - ($invoice->discount_amount ?? 0), 2) }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        @php
                                            $isOverdue = $invoice->isOverdue();
                                            $statusColors = [
                                                'paid' => 'bg-green-100 text-green-800',
                                                'unpaid' => 'bg-yellow-100 text-yellow-800',
                                                'overdue' => 'bg-red-100 text-red-800',
                                            ];
                                            $displayStatus = $isOverdue && $invoice->status === 'unpaid' ? 'overdue' : $invoice->status;
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$displayStatus] ?? 'bg-slate-100 text-slate-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $displayStatus)) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="text-slate-600">{{ $invoice->issued_at?->format('M d, Y') ?? 'N/A' }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="text-slate-600">{{ $invoice->due_date?->format('M d, Y') ?? 'N/A' }}</span>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.invoices.show', $invoice) }}" class="inline-flex items-center justify-center gap-2 px-3 py-2 text-primary hover:bg-primary/10 rounded-lg transition-colors">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </a>
                                            <a href="{{ route('admin.invoices.download', $invoice) }}" class="inline-flex items-center justify-center gap-2 px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors" title="Download PDF">
                                                <i data-lucide="download" class="w-4 h-4"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i data-lucide="inbox" class="w-12 h-12 text-slate-300 mb-3"></i>
                                            <p class="text-slate-500 font-semibold">No invoices found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($invoices->hasPages())
                    <div class="p-5 border-t border-slate-200/60">
                        {{ $invoices->links() }}
                    </div>
                @endif
            </div>
            <!-- END: Invoices List -->
        </div>
    </div>
</div>
@endsection
