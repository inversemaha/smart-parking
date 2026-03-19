@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 text-dark mb-0">{{ __('admin.operator.dashboard') }}</h1>
                    <p class="text-muted mb-0">Gate Operations & Quick Entry/Exit</p>
                </div>
                <div class="btn-group" role="group">
                    <a href="{{ route('operator.quick-scan') }}" class="btn btn-primary">
                        <i class="fas fa-qrcode"></i> {{ __('admin.operator.quick_scan') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Real-time Stats -->
    <div class="row g-3 mb-4">
        <!-- Active Sessions -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-left-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                {{ __('admin.operator.active_sessions_count') }}
                            </div>
                            <div class="h3 mb-0 text-dark">
                                {{ \App\Domains\Parking\Models\ParkingSession::active()->count() }}
                            </div>
                        </div>
                        <div class="fa-2x text-success opacity-50">
                            <i class="fas fa-car"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Revenue -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-left-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                {{ __('admin.operator.revenue_today') }}
                            </div>
                            <div class="h3 mb-0 text-dark">
                                ৳ {{ number_format(\App\Domains\Payment\Models\Invoice::where('paid_at', '>=', now()->startOfDay())->sum('total_amount'), 0) }}
                            </div>
                        </div>
                        <div class="fa-2x text-info opacity-50">
                            <i class="fas fa-money-bill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-left-warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                {{ __('admin.operator.pending_payments_count') }}
                            </div>
                            <div class="h3 mb-0 text-dark">
                                {{ \App\Domains\Payment\Models\Invoice::whereIn('status', ['issued', 'partially_paid'])->count() }}
                            </div>
                        </div>
                        <div class="fa-2x text-warning opacity-50">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overstayed Vehicles -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-left-danger h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                {{ __('admin.operator.overstayed_count') }}
                            </div>
                            <div class="h3 mb-0 text-dark">
                                {{ \App\Domains\Parking\Models\ParkingSession::active()
                                    ->where('entry_time', '<', now()->subHours(4))
                                    ->count() }}
                            </div>
                        </div>
                        <div class="fa-2x text-danger opacity-50">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Operations -->
    <div class="row g-3 mb-4">
        <!-- Manual Entry -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-arrow-right"></i> {{ __('admin.operator.manual_entry') }}
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('operator.entry') }}" method="POST" class="needs-validation">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">License Plate</label>
                            <input type="text" name="license_plate" class="form-control form-control-lg" 
                                placeholder="e.g., ঢাকা-মেট্রো-গা-১২-৩৪৫৬" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Parking Zone</label>
                            <select name="zone_id" class="form-select form-select-lg" required>
                                <option value="">Select a zone...</option>
                                @foreach(\App\Domains\Parking\Models\ParkingZone::all() as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100 btn-lg">
                            <i class="fas fa-sign-in-alt"></i> Record Entry
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Manual Exit -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-arrow-left"></i> {{ __('admin.operator.manual_exit') }}
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('operator.exit') }}" method="POST" class="needs-validation">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">License Plate</label>
                            <input type="text" name="license_plate" class="form-control form-control-lg" 
                                placeholder="e.g., ঢাকা-মেট্রো-গা-১२-३४५६" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gate</label>
                            <select name="exit_gate_id" class="form-select form-select-lg" required>
                                <option value="">Select exit gate...</option>
                                @foreach(\App\Domains\Parking\Models\ParkingGate::all() as $gate)
                                    <option value="{{ $gate->id }}">{{ $gate->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-warning w-100 btn-lg">
                            <i class="fas fa-sign-out-alt"></i> Record Exit
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Search Session -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-search"></i> {{ __('admin.operator.search') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">License Plate</label>
                        <input type="text" id="searchInput" class="form-control form-control-lg" 
                            placeholder="Search by plate..." minlength="2">
                    </div>
                    <div id="searchResults" class="list-group"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('admin.operator.recent_activities') }}</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Time</th>
                                <th>License Plate</th>
                                <th>Zone</th>
                                <th>Action</th>
                                <th>Duration</th>
                                <th>Charge</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Domains\Parking\Models\ParkingSession::latest()->take(10)->get() as $session)
                                <tr>
                                    <td>{{ $session->entry_time?->format('H:i:s') }}</td>
                                    <td><strong>{{ $session->vehicle->license_plate ?? 'N/A' }}</strong></td>
                                    <td>{{ $session->zone->name }}</td>
                                    <td>
                                        @if($session->exit_time)
                                            <span class="badge bg-warning">Exit</span>
                                        @else
                                            <span class="badge bg-success">Entry</span>
                                        @endif
                                    </td>
                                    <td>{{ $session->duration_minutes ?? '-' }} mins</td>
                                    <td>৳ {{ number_format($session->total_charge ?? 0, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $session->session_status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($session->session_status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No recent activities</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');

    searchInput.addEventListener('input', async function() {
        const query = this.value;
        
        if (query.length < 2) {
            searchResults.innerHTML = '';
            return;
        }

        try {
            const response = await fetch(`{{ route('operator.search') }}?q=${encodeURIComponent(query)}`);
            const data = await response.json();
            
            searchResults.innerHTML = '';
            
            if (data.length === 0) {
                searchResults.innerHTML = '<div class="list-group-item disabled">No sessions found</div>';
                return;
            }

            data.forEach(session => {
                const item = document.createElement('a');
                item.href = `{{ route('operator.session', '') }}/${session.id}`;
                item.className = 'list-group-item list-group-item-action';
                item.innerHTML = `
                    <div class="d-flex justify-content-between">
                        <strong>${session.license_plate}</strong>
                        <span class="badge bg-primary">${session.status}</span>
                    </div>
                    <small class="text-muted">${session.zone} • ${session.duration} mins</small>
                `;
                searchResults.appendChild(item);
            });
        } catch (error) {
            console.error('Search error:', error);
        }
    });
</script>
@endpush
@endsection
