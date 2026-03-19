<?php

namespace App\Domains\Parking\Controllers;

use App\Domains\Parking\Models\{ParkingSession, ParkingGate, ParkingZone, ParkingQrCode};
use App\Models\Vehicle;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class OperatorDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            // Allow operators and admins
            abort_if(
                Gate::denies('operate_gate') && Gate::denies('manage_parking'),
                403
            );
            return $next($request);
        });
    }

    /**
     * Main operator dashboard
     */
    public function dashboard(Request $request)
    {
        // Get operator's assigned gate(s) or all gates if admin
        $gates = auth()->user()->hasRole('admin') 
            ? ParkingGate::active()->get()
            : ParkingGate::active()->limit(3)->get();

        // Real-time stats
        $stats = [
            'total_active_sessions' => ParkingSession::active()->count(),
            'total_today' => ParkingSession::today()->count(),
            'pending_payments' => ParkingSession::completed()
                ->where('charging_status', 'pending')
                ->count(),
            'total_revenue_today' => ParkingSession::today()->sum('total_charge'),
            'overstayed_vehicles' => ParkingSession::where('is_overstayed', true)
                ->where('session_status', 'active')
                ->count(),
        ];

        // Get last 10 activities
        $recentActivities = ParkingSession::query()
            ->with(['vehicle', 'entryGate', 'exitGate'])
            ->latest('updated_at')
            ->limit(10)
            ->get();

        // Zone occupancy
        $occupancyByZone = ParkingSession::active()
            ->with('zone')
            ->selectRaw('zone_id, COUNT(*) as count')
            ->groupBy('zone_id')
            ->get();

        return view('operator.dashboard', compact(
            'gates',
            'stats',
            'recentActivities',
            'occupancyByZone'
        ));
    }

    /**
     * Quick scan interface for QR codes
     */
    public function quickScan(Request $request)
    {
        return view('operator.quick-scan');
    }

    /**
     * Process QR code scan (entry or exit)
     */
    public function processScan(Request $request)
    {
        $validated = $request->validate([
            'qr_code' => 'required|string',
            'gate_id' => 'nullable|exists:parking_gates,id',
        ]);

        // Try to find QR code
        $qrCode = ParkingQrCode::where('code', $validated['qr_code'])->first();

        if (!$qrCode) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code',
                'type' => 'error',
            ], 404);
        }

        // Record QR code scan
        $qrCode->recordScan();

        // Determine if this is entry or exit based on gate
        $gate = $validated['gate_id'] ? ParkingGate::find($validated['gate_id']) : null;
        $isEntry = !$gate || $gate->gate_type === 'entry';

        if ($isEntry) {
            // Entry scan - create new session
            return $this->handleEntryByQr($qrCode, $gate);
        } else {
            // Exit scan - find and complete session
            return $this->handleExitByQr($qrCode, $gate);
        }
    }

    /**
     * Handle QR code entry
     */
    private function handleEntryByQr($qrCode, $gate = null)
    {
        // If QR is for a specific zone/floor
        $zone = $qrCode->zone;
        $floor = $qrCode->floor;

        return response()->json([
            'success' => true,
            'message' => 'Entry recorded successfully',
            'type' => 'entry',
            'zone' => $zone?->name,
            'floor' => $floor?->floor_number,
            'qr_type' => $qrCode->type,
            'scans' => $qrCode->scan_count,
        ]);
    }

    /**
     * Handle QR code exit
     */
    private function handleExitByQr($qrCode, $gate = null)
    {
        // Find active session for this zone
        $session = ParkingSession::active()
            ->where('zone_id', $qrCode->zone_id)
            ->latest('entry_time')
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'No active session found for this zone',
                'type' => 'error',
            ], 404);
        }

        // Mark as exited
        $session->markExited(
            exitTime: now(),
            exitGateId: $gate?->id,
            metadata: json_encode(['qr_scan' => true, 'qr_code_id' => $qrCode->id])
        );

        return response()->json([
            'success' => true,
            'message' => 'Exit recorded successfully',
            'type' => 'exit',
            'license_plate' => $session->license_plate,
            'entry_time' => $session->entry_time->format('Y-m-d H:i'),
            'exit_time' => $session->exit_time->format('Y-m-d H:i'),
            'duration' => $session->getFormattedDurationAttribute(),
            'charge' => '৳' . number_format($session->total_charge, 2),
        ]);
    }

    /**
     * Quick manual entry
     */
    public function quickEntry(Request $request)
    {
        $validated = $request->validate([
            'license_plate' => 'required|string|max:50',
            'zone_id' => 'required|exists:parking_zones,id',
            'gate_id' => 'nullable|exists:parking_gates,id',
        ]);

        // Check if vehicle already has active session
        $existingSession = ParkingSession::active()
            ->where('license_plate', $validated['license_plate'])
            ->first();

        if ($existingSession) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle already has an active parking session',
                'existing_session' => [
                    'id' => $existingSession->id,
                    'entry_time' => $existingSession->entry_time->format('Y-m-d H:i'),
                    'zone' => $existingSession->zone->name,
                ],
            ], 409);
        }

        $zone = ParkingZone::findOrFail($validated['zone_id']);
        $rate = $zone->rates->first();

        // Create new session
        $session = ParkingSession::create([
            'zone_id' => $zone->id,
            'gate_id' => $validated['gate_id'],
            'license_plate' => $validated['license_plate'],
            'entry_time' => now(),
            'session_status' => 'active',
            'charging_status' => 'pending',
            'parking_rate_id' => $rate?->id,
            'base_rate_per_hour' => $rate?->hourly_rate ?? 50,
            'vehicle_multiplier' => 1.0,
            'entry_metadata' => json_encode(['type' => 'manual', 'operator_id' => auth()->id()]),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Entry recorded successfully',
            'session_id' => $session->id,
            'license_plate' => $session->license_plate,
            'zone' => $zone->name,
            'time' => now()->format('H:i:s'),
        ]);
    }

    /**
     * Quick manual exit
     */
    public function quickExit(Request $request)
    {
        $validated = $request->validate([
            'license_plate' => 'required|string|max:50',
            'gate_id' => 'nullable|exists:parking_gates,id',
        ]);

        // Find active session by license plate
        $session = ParkingSession::active()
            ->where('license_plate', $validated['license_plate'])
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'No active session found for this vehicle',
            ], 404);
        }

        // Mark as exited
        $session->markExited(
            exitTime: now(),
            exitGateId: $validated['gate_id'],
            metadata: json_encode(['type' => 'manual', 'operator_id' => auth()->id()])
        );

        return response()->json([
            'success' => true,
            'message' => 'Exit recorded successfully',
            'session' => [
                'id' => $session->id,
                'license_plate' => $session->license_plate,
                'entry_time' => $session->entry_time->format('Y-m-d H:i'),
                'exit_time' => $session->exit_time->format('Y-m-d H:i'),
                'duration' => $session->getFormattedDurationAttribute(),
                'total_charge' => $session->total_charge,
                'charge_formatted' => '৳' . number_format($session->total_charge, 2),
            ],
        ]);
    }

    /**
     * Search session by license plate
     */
    public function searchSession(Request $request)
    {
        $query = $request->input('q');

        if (strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Search term too short',
            ]);
        }

        $sessions = ParkingSession::where('license_plate', 'like', "%{$query}%")
            ->with(['zone', 'entryGate', 'exitGate'])
            ->latest('entry_time')
            ->limit(10)
            ->get()
            ->map(function ($session) {
                return [
                    'id' => $session->id,
                    'license_plate' => $session->license_plate,
                    'zone' => $session->zone->name,
                    'status' => $session->session_status,
                    'entry_time' => $session->entry_time->format('Y-m-d H:i'),
                    'exit_time' => $session->exit_time?->format('Y-m-d H:i'),
                    'duration' => $session->getFormattedDurationAttribute(),
                    'charge' => $session->total_charge ? '৳' . number_format($session->total_charge, 2) : '-',
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $sessions,
        ]);
    }

    /**
     * Get session details
     */
    public function sessionDetails($id)
    {
        $session = ParkingSession::with(['vehicle', 'zone', 'entryGate', 'exitGate', 'rate'])
            ->findOrFail($id);

        // Check permission
        abort_if(
            !auth()->user()->hasRole('admin') && $session->zone->id !== auth()->user()->assigned_zone_id,
            403
        );

        return response()->json([
            'success' => true,
            'session' => [
                'id' => $session->id,
                'license_plate' => $session->license_plate,
                'vehicle_category' => $session->vehicle_category,
                'zone' => $session->zone->name,
                'floor' => $session->floor?->floor_number,
                'entry_gate' => $session->entryGate?->name,
                'exit_gate' => $session->exitGate?->name,
                'entry_time' => $session->entry_time->format('Y-m-d H:i:s'),
                'exit_time' => $session->exit_time?->format('Y-m-d H:i:s'),
                'duration' => $session->getFormattedDurationAttribute(),
                'base_charge' => $session->base_charge ? '৳' . number_format($session->base_charge, 2) : '-',
                'peak_charge' => $session->peak_charge ? '৳' . number_format($session->peak_charge, 2) : '-',
                'discount' => $session->discount_amount ? '৳' . number_format($session->discount_amount, 2) : '-',
                'total_charge' => $session->total_charge ? '৳' . number_format($session->total_charge, 2) : '-',
                'status' => $session->session_status,
                'charging_status' => $session->charging_status,
            ],
        ]);
    }

    /**
     * Gate status and current occupancy
     */
    public function gateStatus($id)
    {
        $gate = ParkingGate::with(['zone', 'floor', 'accessLogs'])
            ->findOrFail($id);

        // Check permission
        abort_if(
            !auth()->user()->hasRole('admin') && $gate->zone->id !== auth()->user()->assigned_zone_id,
            403
        );

        // Recent activities at this gate
        $recentActivity = ParkingSession::where('entry_gate_id', $id)
            ->orWhere('exit_gate_id', $id)
            ->with(['vehicle', 'zone'])
            ->latest('updated_at')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'gate' => [
                'id' => $gate->id,
                'name' => $gate->name,
                'zone' => $gate->zone->name,
                'floor' => $gate->floor?->floor_number,
                'type' => $gate->gate_type,
                'status' => $gate->gate_status,
                'operational' => $gate->isOperational(),
                'location' => $gate->location,
                'contact' => $gate->contact_person,
                'contact_phone' => $gate->contact_phone,
            ],
            'recent_activity' => $recentActivity->map(function ($session) {
                return [
                    'license_plate' => $session->license_plate,
                    'time' => $session->exit_time?->format('H:i') ?? $session->entry_time->format('H:i'),
                    'type' => $session->exit_time ? 'Exit' : 'Entry',
                    'zone' => $session->zone->name,
                ];
            }),
        ]);
    }

    /**
     * Real-time notifications/alerts
     */
    public function notifications(Request $request)
    {
        $alerts = [];

        // Overstayed vehicles (>24 hours)
        $overstayed = ParkingSession::where('is_overstayed', true)
            ->where('session_status', 'active')
            ->with('zone')
            ->get();

        $alerts = array_merge($alerts, $overstayed->map(function ($session) {
            return [
                'type' => 'overstay',
                'severity' => 'danger',
                'message' => "Overstayed vehicle: {$session->license_plate}",
                'details' => "Parked for {$session->getFormattedDurationAttribute()} in {$session->zone->name}",
                'session_id' => $session->id,
            ];
        })->toArray());

        // Sessions approaching 24 hours
        $longParked = ParkingSession::active()
            ->where('entry_time', '<', now()->subHours(20))
            ->with('zone')
            ->get();

        $alerts = array_merge($alerts, $longParked->map(function ($session) {
            return [
                'type' => 'long_park',
                'severity' => 'warning',
                'message' => "Long-parked vehicle: {$session->license_plate}",
                'details' => "Parked for {$session->getFormattedDurationAttribute()} in {$session->zone->name}",
                'session_id' => $session->id,
            ];
        })->toArray());

        // Pending payments from completed sessions
        $unpaid = ParkingSession::completed()
            ->where('charging_status', 'pending')
            ->with('zone')
            ->limit(10)
            ->get();

        $alerts = array_merge($alerts, $unpaid->map(function ($session) {
            return [
                'type' => 'unpaid',
                'severity' => 'info',
                'message' => "Payment pending: {$session->license_plate}",
                'details' => "Amount: ৳" . number_format($session->total_charge, 2),
                'session_id' => $session->id,
            ];
        })->toArray());

        return response()->json([
            'success' => true,
            'alerts' => $alerts,
            'total_alerts' => count($alerts),
        ]);
    }

    /**
     * Update gate status (operator can toggle gate)
     */
    public function updateGateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:operational,maintenance,closed',
            'notes' => 'nullable|string',
        ]);

        $gate = ParkingGate::findOrFail($id);

        // Only admin or assigned operator
        abort_if(
            !auth()->user()->hasRole('admin') && $gate->zone->id !== auth()->user()->assigned_zone_id,
            403
        );

        $gate->update([
            'gate_status' => $validated['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gate status updated',
            'gate' => [
                'id' => $gate->id,
                'name' => $gate->name,
                'status' => $gate->gate_status,
            ],
        ]);
    }
}
