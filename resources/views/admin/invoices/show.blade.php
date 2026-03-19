@extends('layouts.admin')

@section('title', 'Invoice - ' . $invoice->invoice_number)

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
                        <a href="{{ route('admin.invoices.index') }}">Invoices</a>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" class="w-4 h-4 mx-2 stroke-slate-400">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </li>
                    <li class="text-primary font-medium">{{ $invoice->invoice_number }}</li>
                </ol>
            </nav>
            <div class="flex gap-3">
                <a href="{{ route('admin.invoices.download', $invoice) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-lg hover:bg-primary/20">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    <span class="text-sm font-semibold">Download PDF</span>
                </a>
                <a href="{{ route('admin.invoices.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-lg hover:bg-slate-50">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    <span class="text-sm font-semibold">Back</span>
                </a>
            </div>
        </div>
    </div>
    <!-- END: Top Bar -->

    <div class="grid grid-cols-12 gap-6">
        <!-- Main Content -->
        <div class="col-span-12 lg:col-span-8">
            <!-- Invoice Details -->
            <div class="intro-y box">
                <!-- Header -->
                <div class="p-5 border-b border-slate-200/60 bg-gradient-to-r from-primary/5 to-primary/10">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h1 class="text-3xl font-bold text-slate-900">INVOICE</h1>
                            <p class="text-sm text-slate-600 mt-1">Invoice Number: <span class="font-bold text-primary">{{ $invoice->invoice_number }}</span></p>
                        </div>
                        @php
                            $isOverdue = $invoice->isOverdue();
                            $statusColors = [
                                'paid' => 'bg-green-100 text-green-800',
                                'unpaid' => 'bg-yellow-100 text-yellow-800',
                                'overdue' => 'bg-red-100 text-red-800',
                            ];
                            $displayStatus = $isOverdue && $invoice->status === 'unpaid' ? 'overdue' : $invoice->status;
                        @endphp
                        <span class="px-4 py-2 rounded-lg text-sm font-bold {{ $statusColors[$displayStatus] ?? 'bg-slate-100 text-slate-800' }}">
                            {{ ucfirst(str_replace('_', ' ', $displayStatus)) }}
                        </span>
                    </div>
                </div>

                <!-- Invoice Details Grid -->
                <div class="p-5 border-b border-slate-200/60">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <!-- Invoice Number -->
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase block mb-2">Invoice #</label>
                            <p class="text-lg font-semibold text-primary">{{ $invoice->invoice_number }}</p>
                        </div>

                        <!-- Issued Date -->
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase block mb-2">Issued Date</label>
                            <p class="text-lg font-semibold text-slate-900">{{ $invoice->issued_at?->format('M d, Y') ?? 'N/A' }}</p>
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase block mb-2">Due Date</label>
                            <p class="text-lg font-semibold {{ $isOverdue && $invoice->status === 'unpaid' ? 'text-red-600' : 'text-slate-900' }}">
                                {{ $invoice->due_date?->format('M d, Y') ?? 'N/A' }}
                            </p>
                        </div>

                        <!-- Paid Date -->
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase block mb-2">Paid Date</label>
                            <p class="text-lg font-semibold text-slate-900">{{ $invoice->paid_at?->format('M d, Y') ?? 'Not Paid' }}</p>
                        </div>
                    </div>

                    <!-- Customer & Booking Info -->
                    <div class="grid grid-cols-2 gap-6">
                        <!-- From -->
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase block mb-2">From</label>
                            <p class="text-sm font-semibold text-slate-900">Parking Management System</p>
                            <p class="text-sm text-slate-600">Smart Parking Services</p>
                        </div>

                        <!-- Bill To -->
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase block mb-2">Bill To</label>
                            <p class="text-sm font-semibold text-slate-900">{{ $invoice->user->name ?? 'N/A' }}</p>
                            <p class="text-sm text-slate-600">{{ $invoice->user->email ?? 'N/A' }}</p>
                            <p class="text-sm text-slate-600">{{ $invoice->user->mobile ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Line Items -->
                <div class="p-5 border-b border-slate-200/60">
                    <h3 class="text-sm font-bold text-slate-900 mb-4">Invoice Items</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-3 py-2 text-left font-semibold">Description</th>
                                    <th class="px-3 py-2 text-right font-semibold">Quantity</th>
                                    <th class="px-3 py-2 text-right font-semibold">Unit Price</th>
                                    <th class="px-3 py-2 text-right font-semibold">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($invoice->booking)
                                    <tr class="border-b border-slate-200/60">
                                        <td class="px-3 py-3">
                                            <p class="font-semibold">Parking Fee - {{ $invoice->booking->booking_reference }}</p>
                                            <p class="text-xs text-slate-500">{{ $invoice->booking->start_time?->format('M d, H:i') }} - {{ $invoice->booking->end_time?->format('M d, H:i') }}</p>
                                        </td>
                                        <td class="px-3 py-3 text-right">1</td>
                                        <td class="px-3 py-3 text-right font-semibold">{{ $invoice->currency ?? 'BDT' }} {{ number_format($invoice->amount, 2) }}</td>
                                        <td class="px-3 py-3 text-right font-semibold">{{ $invoice->currency ?? 'BDT' }} {{ number_format($invoice->amount, 2) }}</td>
                                    </tr>
                                @else
                                    <tr class="border-b border-slate-200/60">
                                        <td class="px-3 py-3">Service Charge</td>
                                        <td class="px-3 py-3 text-right">1</td>
                                        <td class="px-3 py-3 text-right font-semibold">{{ $invoice->currency ?? 'BDT' }} {{ number_format($invoice->amount, 2) }}</td>
                                        <td class="px-3 py-3 text-right font-semibold">{{ $invoice->currency ?? 'BDT' }} {{ number_format($invoice->amount, 2) }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Amount Summary -->
                <div class="p-5 border-b border-slate-200/60">
                    <div class="flex justify-end">
                        <div class="w-full md:w-1/2 lg:w-1/3 space-y-3">
                            <!-- Subtotal -->
                            <div class="flex justify-between">
                                <span class="text-slate-600">Subtotal:</span>
                                <span class="font-semibold">{{ $invoice->currency ?? 'BDT' }} {{ number_format($invoice->amount, 2) }}</span>
                            </div>

                            <!-- Tax -->
                            @if($invoice->tax_amount > 0)
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Tax ({{ isset($invoice->tax_amount) ? ($invoice->tax_amount > 0 ? round(($invoice->tax_amount / $invoice->amount) * 100) : 0) : 0 }}%):</span>
                                    <span class="font-semibold">{{ $invoice->currency ?? 'BDT' }} {{ number_format($invoice->tax_amount, 2) }}</span>
                                </div>
                            @endif

                            <!-- Discount -->
                            @if($invoice->discount_amount > 0)
                                <div class="flex justify-between text-green-600">
                                    <span>Discount:</span>
                                    <span class="font-semibold">-{{ $invoice->currency ?? 'BDT' }} {{ number_format($invoice->discount_amount, 2) }}</span>
                                </div>
                            @endif

                            <!-- Total -->
                            <div class="flex justify-between pt-3 border-t-2 border-slate-200">
                                <span class="font-bold text-lg">Total:</span>
                                <span class="font-bold text-2xl text-primary">
                                    {{ $invoice->currency ?? 'BDT' }} {{ number_format($invoice->amount + ($invoice->tax_amount ?? 0) - ($invoice->discount_amount ?? 0), 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                @if($invoice->notes)
                    <div class="p-5">
                        <h4 class="text-sm font-bold text-slate-900 mb-2">Notes</h4>
                        <p class="text-sm text-slate-700">{{ $invoice->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-span-12 lg:col-span-4">
            <!-- Invoice Status Card -->
            <div class="intro-y box">
                <div class="flex items-center p-5 border-b border-slate-200/60 bg-slate-50">
                    <h3 class="font-bold text-slate-900">Invoice Status</h3>
                </div>
                <div class="p-5">
                    <div class="text-center">
                        @if($invoice->isPaid())
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i data-lucide="check-circle" class="w-8 h-8 text-green-600"></i>
                            </div>
                            <p class="text-lg font-bold text-green-600 mb-2">Paid</p>
                            <p class="text-sm text-slate-600">Paid on {{ $invoice->paid_at?->format('M d, Y') }}</p>
                        @elseif($invoice->isOverdue())
                            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i data-lucide="alert-circle" class="w-8 h-8 text-red-600"></i>
                            </div>
                            <p class="text-lg font-bold text-red-600 mb-2">Overdue</p>
                            <p class="text-sm text-slate-600">Due since {{ $invoice->due_date?->format('M d, Y') }}</p>
                        @else
                            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i data-lucide="clock" class="w-8 h-8 text-yellow-600"></i>
                            </div>
                            <p class="text-lg font-bold text-yellow-600 mb-2">Unpaid</p>
                            <p class="text-sm text-slate-600">Due on {{ $invoice->due_date?->format('M d, Y') }}</p>
                        @endif
                    </div>

                    <!-- Mark as Paid Button -->
                    @if(!$invoice->isPaid())
                        <form action="{{ route('admin.invoices.mark-paid', $invoice) }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors font-semibold">
                                <i data-lucide="check" class="w-4 h-4 inline-block mr-2"></i>Mark as Paid
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="intro-y box mt-6">
                <div class="flex items-center p-5 border-b border-slate-200/60 bg-slate-50">
                    <h3 class="font-bold text-slate-900">Customer Details</h3>
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary to-primary/60 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr($invoice->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">{{ $invoice->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-slate-500">{{ $invoice->user->user_type ?? 'User' }}</p>
                        </div>
                    </div>

                    <div class="space-y-3 border-t border-slate-200/60 pt-4">
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase">Email</label>
                            <p class="text-sm text-slate-700 break-all">{{ $invoice->user->email }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase">Phone</label>
                            <p class="text-sm text-slate-700">{{ $invoice->user->mobile ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <a href="{{ route('admin.users.show', $invoice->user) }}" class="mt-4 w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-lg hover:bg-primary/20 transition-colors font-semibold text-sm">
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>View Profile
                    </a>
                </div>
            </div>

            <!-- Related Payment -->
            @if($invoice->payment)
                <div class="intro-y box mt-6">
                    <div class="flex items-center p-5 border-b border-slate-200/60 bg-slate-50">
                        <h3 class="font-bold text-slate-900">Related Payment</h3>
                    </div>
                    <div class="p-5">
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs font-semibold text-slate-500 uppercase">Payment ID</label>
                                <p class="text-sm font-semibold text-primary">{{ $invoice->payment->payment_id }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-500 uppercase">Amount</label>
                                <p class="text-sm font-semibold">{{ $invoice->currency ?? 'BDT' }} {{ number_format($invoice->payment->amount, 2) }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-500 uppercase">Status</label>
                                <p class="text-sm font-semibold text-primary">{{ ucfirst($invoice->payment->status) }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.payments.show', $invoice->payment) }}" class="mt-4 w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-lg hover:bg-primary/20 transition-colors font-semibold text-sm">
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>View Payment
                        </a>
                    </div>
                </div>
            @endif

            <!-- System Information -->
            <div class="intro-y box mt-6">
                <div class="flex items-center p-5 border-b border-slate-200/60 bg-slate-50">
                    <h3 class="font-bold text-slate-900">System Information</h3>
                </div>
                <div class="p-5">
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase">Created</label>
                            <p class="text-xs text-slate-600">{{ $invoice->created_at?->format('M d, Y H:i:s') }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase">Last Updated</label>
                            <p class="text-xs text-slate-600">{{ $invoice->updated_at?->format('M d, Y H:i:s') }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase">Invoice ID</label>
                            <p class="text-xs font-mono text-slate-600">{{ $invoice->id }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
