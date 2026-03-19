<?php

namespace App\Domains\Parking\Controllers;

use App\Domains\Parking\Models\{ParkingSession, ParkingZone, ParkingRate, ParkingGate};
use App\Models\Vehicle;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class ParkingSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            abort_if(Gate::denies('manage_parking'), 403);
            return $next($request);
        });
    }

    /**
     * Display active and recent sessions
     */
    public function index(Request $request)
    {
        $sessions = ParkingSession::query()
            ->with(['vehicle', 'zone', 'entryGate', 'exitGate'])
            ->latest('entry_time');

        // Filter by status
        if ($request->filled('status')) {
            $sessions->where('session_status', $request->status);
        }

        // Filter by zone
        if ($request->filled('zone_id')) {
            $sessions->where('zone_id', $request->zone_id);
        }

        // Filter by charging status
        if ($request->filled('charging_status')) {
            $sessions->where('charging_status', $request->charging_status);
        }

        // Search by license plate
        if ($request->filled('search')) {
            $sessions->where('license_plate', 'like', "%{$request->search}%");
        }

        $sessions = $sessions->paginate(20);
        $zones = ParkingZone::active()->get();

        return view('admin.parking.sessions.index', compact('sessions', 'zones'));
    }

    /**
     * Show active sessions dashboard
     */
    public function active(Request $request)
    {
        $activeSessions = ParkingSession::active()
            ->with(['vehicle', 'zone', 'entryGate'])
            ->latest('entry_time')
            ->paginate(15);

        $stats = [
            'total_active' => ParkingSession::active()->count(),
            'total_parked_today' => ParkingSession::today()->count(),
            'total_revenue_today' => ParkingSession::today()->sum('total_charge'),
            'overdue_sessions' => ParkingSession::active()
                ->where('entry_time', '<', now()->subHours(12))
                ->count(),
        ];

        $zones = ParkingZone::active()->get();

        return view('admin.parking.sessions.active', compact('activeSessions', 'stats', 'zones'));
    }

    /**
     * Create new session entry
     */
    public function create()
    {
        $vehicles = Vehicle::where('is_verified', true)
            ->with('owner')
            ->orderBy('license_plate')
            ->get();
        $zones = ParkingZone::active()->get();
        $gates = ParkingGate::active()->get();

        return view('admin.parking.sessions.create', compact('vehicles', 'zones', 'gates'));
    }

    /**
     * Store new session
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'zone_id' => 'required|exists:parking_zones,id',
            'floor_id' => 'nullable|exists:parking_floors,id',
            'entry_gate_id' => 'nullable|exists:parking_gates,id',
            'license_plate' => 'required|string|max:20',
            'entry_metadata' => 'nullable|json',
            'notes' => 'nullable|string',
        ]);

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        $zone = ParkingZone::findOrFail($validated['zone_id']);

        // Get applicable rate
        $rate = ParkingRate::where('zone_id', $zone->id)
            ->where('vehicle_type_id', $vehicle->vehicle_type_id)
            ->first();

        $session = ParkingSession::create([
            ...$validated,
            'entry_time' => now(),
            'session_status' => 'active',
            'charging_status' => 'pending',
            'vehicle_category' => $vehicle->type->name ?? 'Unknown',
            'parking_rate_id' => $rate?->id,
            'base_rate_per_hour' => $rate?->hourly_rate,
            'vehicle_multiplier' => $vehicle->type?->rate_multiplier ?? 1.0,
        ]);

        return redirect()->route('admin.parking-sessions.show', $session)
            ->with('success', 'Parking session created successfully');
    }

    /**
     * Show session details
     */
    public function show(ParkingSession $parkingSession)
    {
        $parkingSession->load([
            'vehicle.owner', 'zone', 'floor', 'rate',
            'entryGate', 'exitGate', 'qrCode', 'payment'
        ]);

        return view('admin.parking.sessions.show', compact('parkingSession'));
    }

    /**
     * Edit session
     */
    public function edit(ParkingSession $parkingSession)
    {
        $zones = ParkingZone::active()->get();
        $gates = ParkingGate::active()->get();

        return view('admin.parking.sessions.edit', compact('parkingSession', 'zones', 'gates'));
    }

    /**
     * Update session
     */
    public function update(Request $request, ParkingSession $parkingSession)
    {
        $validated = $request->validate([
            'floor_id' => 'nullable|exists:parking_floors,id',
            'exit_gate_id' => 'nullable|exists:parking_gates,id',
            'notes' => 'nullable|string',
            'discount_amount' => 'nullable|numeric|min:0',
        ]);

        $parkingSession->update($validated);

        return redirect()->route('admin.parking-sessions.show', $parkingSession)
            ->with('success', 'Parking session updated successfully');
    }

    /**
     * Mark session as exited
     */
    public function markExit(Request $request, ParkingSession $parkingSession)
    {
        if ($parkingSession->session_status !== 'active') {
            return back()->with('error', 'Only active sessions can be exited');
        }

        $validated = $request->validate([
            'exit_gate_id' => 'nullable|exists:parking_gates,id',
            'exit_metadata' => 'nullable|json',
        ]);

        $parkingSession->markExited(
            exitTime: now(),
            exitGateId: $validated['exit_gate_id'],
            metadata: $validated['exit_metadata'] ?? null
        );

        return redirect()->route('admin.parking-sessions.show', $parkingSession)
            ->with('success', 'Session marked as exited. Total charge: ৳' . number_format($parkingSession->total_charge, 2));
    }

    /**
     * Extend active session
     */
    public function extend(Request $request, ParkingSession $parkingSession)
    {
        if ($parkingSession->session_status !== 'active') {
            return back()->with('error', 'Only active sessions can be extended');
        }

        $validated = $request->validate([
            'hours' => 'required|integer|min:1|max:8',
            'notes' => 'nullable|string',
        ]);

        $parkingSession->extend($validated['hours'], $validated['notes'] ?? null);

        return back()->with('success', 'Session extended successfully');
    }

    /**
     * Cancel session
     */
    public function cancel(Request $request, ParkingSession $parkingSession)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $parkingSession->cancel($validated['reason'], auth()->user()->id);

        return redirect()->route('admin.parking-sessions.show', $parkingSession)
            ->with('success', 'Session cancelled successfully');
    }

    /**
     * Collect payment
     */
    public function collectPayment(Request $request, ParkingSession $parkingSession)
    {
        if ($parkingSession->session_status !== 'completed') {
            return back()->with('error', 'Only completed sessions can be charged');
        }

        $parkingSession->update([
            'charging_status' => 'collected',
            'payment_notes' => $request->input('payment_notes'),
        ]);

        return back()->with('success', 'Payment collected: ৳' . number_format($parkingSession->total_charge, 2));
    }

    /**
     * Session analytics
     */
    public function analytics(Request $request)
    {
        $period = $request->input('period', 'today'); // today, week, month, year
        
        $query = ParkingSession::completed();

        if ($period === 'today') {
            $query->today();
        } elseif ($period === 'week') {
            $query->where('entry_time', '>=', now()->subDays(7));
        } elseif ($period === 'month') {
            $query->thisMonth();
        } elseif ($period === 'year') {
            $query->thisYear();
        }

        $analytics = [
            'total_sessions' => $query->count(),
            'total_revenue' => $query->sum('total_charge'),
            'average_duration' => round($query->avg('duration_minutes') / 60, 2),
            'peak_hours_usage' => $query->sum('peak_hours'),
            'sessions_by_zone' => $query->orderBy('zone_id')
                ->selectRaw('zone_id, COUNT(*) as count, SUM(total_charge) as revenue')
                ->groupBy('zone_id')
                ->with('zone')
                ->get(),
        ];

        $zones = ParkingZone::active()->get();

        return view('admin.parking.sessions.analytics', compact('analytics', 'period', 'zones'));
    }

    /**
     * Session occupancy report
     */
    public function occupancy(Request $request)
    {
        $zoneId = $request->input('zone_id');

        $activeSessions = ParkingSession::active();
        if ($zoneId) {
            $activeSessions->where('zone_id', $zoneId);
        }

        $occupancyData = [
            'total_active' => $activeSessions->count(),
            'by_zone' => ParkingSession::active()
                ->groupBy('zone_id')
                ->selectRaw('zone_id, COUNT(*) as active_count')
                ->with('zone')
                ->get(),
            'by_floor' => ParkingSession::active()
                ->when($zoneId, fn($q) => $q->where('zone_id', $zoneId))
                ->groupBy('floor_id')
                ->selectRaw('floor_id, COUNT(*) as active_count')
                ->with('floor')
                ->get(),
        ];

        $zones = ParkingZone::active()->get();

        return view('admin.parking.sessions.occupancy', compact('occupancyData', 'zones', 'zoneId'));
    }

    /**
     * Monthly report
     */
    public function report(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $sessions = ParkingSession::completed()
            ->whereYear('entry_time', $year)
            ->whereMonth('entry_time', $month)
            ->with(['vehicle', 'zone'])
            ->orderBy('entry_time', 'desc')
            ->paginate(50);

        return view('admin.parking.sessions.report', compact('sessions', 'year', 'month'));
    }

    /**
     * Destroy session (soft delete)
     */
    public function destroy(ParkingSession $parkingSession)
    {
        $parkingSession->delete();

        return redirect()->route('admin.parking-sessions.index')
            ->with('success', 'Session deleted successfully');
    }

    /**
     * Restore session
     */
    public function restore($id)
    {
        $parkingSession = ParkingSession::withTrashed()->findOrFail($id);
        $parkingSession->restore();

        return back()->with('success', 'Session restored successfully');
    }
}
