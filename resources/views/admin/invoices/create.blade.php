@extends('layouts.admin')

@section('title', __('admin.invoices.create_title'))

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
                    <li>
                        <a href="{{ route('admin.invoices.index') }}">{{ __('admin.invoices.title') }}</a>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" class="w-4 h-4 mx-2 stroke-slate-400">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </li>
                    <li class="text-primary font-medium">{{ __('admin.invoices.create_invoice') }}</li>
                </ol>
            </nav>
            <a href="{{ route('admin.invoices.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-lg hover:bg-slate-50">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span class="text-sm font-semibold">{{ __('general.back') }}</span>
            </a>
        </div>
    </div>
    <!-- END: Top Bar -->

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-8">
            <div class="intro-y box">
                <div class="p-5 border-b border-slate-200/60 bg-gradient-to-r from-primary/5 to-primary/10">
                    <h1 class="text-2xl font-bold text-slate-900">{{ __('admin.invoices.create_new_invoice') }}</h1>
                    <p class="text-slate-600 mt-2">{{ __('admin.invoices.select_parking_session') }}</p>
                </div>

                <div class="p-5">
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <h3 class="font-bold text-red-800 mb-2">{{ __('general.errors') }}</h3>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li class="text-red-700">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.invoices.generate', '') }}" method="POST">
                        @csrf
                        <div class="mb-5">
                            <label class="form-label">{{ __('admin.invoices.select_session') }}</label>
                            <div class="relative">
                                <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                                <input type="text" id="session-search" placeholder="{{ __('admin.invoices.search_session') }}" 
                                    class="pl-10 pr-4 py-2 w-full border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                            <div id="sessions-list" class="mt-3 max-h-96 overflow-y-auto border border-slate-200 rounded-lg">
                                @forelse ($sessions as $session)
                                    <div class="p-4 border-b border-slate-100 hover:bg-slate-50 cursor-pointer session-item" 
                                        data-session-id="{{ $session->id }}"
                                        data-plate="{{ $session->vehicle?->license_plate }}"
                                        data-zone="{{ $session->zone?->name }}"
                                        data-amount="{{ $session->total_charge }}">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <p class="font-semibold text-slate-900">{{ __('general.license_plate') }}: <span class="text-primary">{{ $session->vehicle?->license_plate ?? 'N/A' }}</span></p>
                                                <p class="text-sm text-slate-600">{{ __('general.zone') }}: {{ $session->zone?->name ?? 'N/A' }}</p>
                                                <p class="text-sm text-slate-600">{{ __('general.entry') }}: {{ $session->entry_time?->format('d M Y H:i') ?? 'N/A' }}</p>
                                                <p class="text-sm text-slate-600">{{ __('general.exit') }}: {{ $session->exit_time?->format('d M Y H:i') ?? 'N/A' }}</p>
                                                <p class="text-sm text-slate-600">{{ __('admin.invoices.duration') }}: {{ $session->duration_minutes ?? 0 }} {{ __('general.minutes') }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-lg font-bold text-primary">৳ {{ number_format($session->total_charge, 2) }}</p>
                                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full 
                                                    {{ $session->session_status === 'active' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ $session->session_status }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-8 text-center text-slate-600">
                                        <p>{{ __('admin.invoices.no_sessions_available') }}</p>
                                    </div>
                                @endforelse
                            </div>
                            <input type="hidden" id="selected-session-id" name="session_id">
                        </div>

                        <!-- Invoice Details Preview -->
                        <div id="invoice-preview" class="hidden mb-5">
                            <h3 class="font-bold text-slate-900 mb-3">{{ __('admin.invoices.invoice_summary') }}</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-slate-600">{{ __('general.license_plate') }}:</span>
                                    <span id="preview-plate" class="font-semibold"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-600">{{ __('general.zone') }}:</span>
                                    <span id="preview-zone" class="font-semibold"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-600">{{ __('admin.invoices.base_amount') }}:</span>
                                    <span id="preview-amount" class="font-semibold"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-600">{{ __('admin.invoices.tax') }} (5%):</span>
                                    <span id="preview-tax" class="font-semibold"></span>
                                </div>
                                <hr class="my-2">
                                <div class="flex justify-between text-base font-bold">
                                    <span>{{ __('admin.invoices.total') }}:</span>
                                    <span class="text-primary" id="preview-total"></span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit" class="hidden btn btn-primary gap-2 w-full md:w-auto" id="submit-btn">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                {{ __('admin.invoices.create_invoice') }}
                            </button>
                            <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary gap-2 w-full md:w-auto">
                                {{ __('general.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-span-12 lg:col-span-4">
            <div class="intro-y box">
                <div class="p-5 border-b border-slate-200/60 bg-slate-50">
                    <h3 class="font-bold text-slate-900">{{ __('admin.invoices.invoice_info') }}</h3>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <p class="text-sm font-semibold text-slate-600 mb-2">{{ __('admin.invoices.how_it_works') }}</p>
                        <ul class="text-sm text-slate-600 space-y-2 list-decimal list-inside">
                            <li>{{ __('admin.invoices.step_1') }}</li>
                            <li>{{ __('admin.invoices.step_2') }}</li>
                            <li>{{ __('admin.invoices.step_3') }}</li>
                            <li>{{ __('admin.invoices.step_4') }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="intro-y box mt-6">
                <div class="p-5 border-b border-slate-200/60 bg-slate-50">
                    <h3 class="font-bold text-slate-900">{{ __('admin.invoices.session_statistics') }}</h3>
                </div>
                <div class="p-5 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-slate-600">{{ __('admin.invoices.total_available') }}:</span>
                        <strong class="text-slate-900">{{ count($sessions) }}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">{{ __('admin.invoices.total_amount') }}:</span>
                        <strong class="text-slate-900">৳ {{ number_format($sessions->sum('total_charge'), 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('session-search');
        const sessionsList = document.getElementById('sessions-list');
        const sessionItems = document.querySelectorAll('.session-item');
        const selectedSessionId = document.getElementById('selected-session-id');
        const invoicePreview = document.getElementById('invoice-preview');
        const submitBtn = document.getElementById('submit-btn');

        // Search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            sessionItems.forEach(item => {
                const plate = item.getAttribute('data-plate').toLowerCase();
                const zone = item.getAttribute('data-zone').toLowerCase();
                const matches = plate.includes(searchTerm) || zone.includes(searchTerm);
                item.style.display = matches ? 'block' : 'none';
            });
        });

        // Session selection
        sessionItems.forEach(item => {
            item.addEventListener('click', function() {
                const sessionId = this.getAttribute('data-session-id');
                const plate = this.getAttribute('data-plate');
                const zone = this.getAttribute('data-zone');
                const amount = parseFloat(this.getAttribute('data-amount'));
                
                selectedSessionId.value = sessionId;
                
                // Calculate tax
                const tax = amount * 0.05;
                const total = amount + tax;
                
                // Update preview
                document.getElementById('preview-plate').textContent = plate;
                document.getElementById('preview-zone').textContent = zone;
                document.getElementById('preview-amount').textContent = '৳ ' + amount.toFixed(2);
                document.getElementById('preview-tax').textContent = '৳ ' + tax.toFixed(2);
                document.getElementById('preview-total').textContent = '৳ ' + total.toFixed(2);
                
                // Show preview and submit button
                invoicePreview.classList.remove('hidden');
                submitBtn.classList.remove('hidden');
                
                // Highlight selected item
                sessionItems.forEach(i => i.classList.remove('bg-blue-50'));
                this.classList.add('bg-blue-50');
            });
        });
    });
</script>
@endsection
