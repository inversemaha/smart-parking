<?php

namespace App\Domains\Parking\Controllers;

use App\Domains\Parking\Models\ParkingQrCode;
use App\Domains\Parking\Models\ParkingZone;
use App\Domains\Parking\Models\ParkingFloor;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ParkingQrCodeController extends Controller
{
    /**
     * Display a listing of parking QR codes
     */
    public function index(): View
    {
        $qrCodes = ParkingQrCode::with('zone', 'floor')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.parking.qr-codes.index', compact('qrCodes'));
    }

    /**
     * Show the form for creating a new QR code
     */
    public function create(): View
    {
        $zones = ParkingZone::active()->orderBy('name')->get();
        $floors = ParkingFloor::orderBy('zone_id')->orderBy('floor_number')->get();

        return view('admin.parking.qr-codes.create', compact('zones', 'floors'));
    }

    /**
     * Store a newly created QR code in database
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:parking_zones,id',
            'floor_id' => 'nullable|exists:parking_floors,id',
            'type' => 'required|in:zone,floor,slot,instructions',
            'redirect_url' => 'nullable|string|max:500|url',
            'metadata' => 'nullable|json',
            'is_active' => 'boolean',
        ]);

        // Generate unique QR code identifier
        $code = 'QR_' . uniqid() . '_' . now()->timestamp;

        // Generate QR code data
        $qrData = $this->generateQrCodeImage($code, $validated);

        $qrCode = ParkingQrCode::create([
            ...$validated,
            'code' => $code,
            'qr_data' => $qrData,
        ]);

        return redirect()
            ->route('admin.parking-qr-codes.show', $qrCode)
            ->with('success', __('admin.qr_code_created_successfully'));
    }

    /**
     * Display a specific QR code
     */
    public function show(ParkingQrCode $qrCode): View
    {
        return view('admin.parking.qr-codes.show', compact('qrCode'));
    }

    /**
     * Show the form for editing QR code
     */
    public function edit(ParkingQrCode $qrCode): View
    {
        $zones = ParkingZone::active()->orderBy('name')->get();
        $floors = ParkingFloor::orderBy('zone_id')->orderBy('floor_number')->get();

        return view('admin.parking.qr-codes.edit', compact('qrCode', 'zones', 'floors'));
    }

    /**
     * Update QR code in database
     */
    public function update(Request $request, ParkingQrCode $qrCode): RedirectResponse
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:parking_zones,id',
            'floor_id' => 'nullable|exists:parking_floors,id',
            'type' => 'required|in:zone,floor,slot,instructions',
            'redirect_url' => 'nullable|string|max:500|url',
            'metadata' => 'nullable|json',
            'is_active' => 'boolean',
        ]);

        $qrCode->update($validated);

        return redirect()
            ->route('admin.parking-qr-codes.show', $qrCode)
            ->with('success', __('admin.qr_code_updated_successfully'));
    }

    /**
     * Delete a QR code (soft delete)
     */
    public function destroy(ParkingQrCode $qrCode): RedirectResponse
    {
        $qrCode->delete();

        return redirect()
            ->route('admin.parking-qr-codes.index')
            ->with('success', __('admin.qr_code_deleted_successfully'));
    }

    /**
     * Restore a soft-deleted QR code
     */
    public function restore(string $id): RedirectResponse
    {
        $qrCode = ParkingQrCode::onlyTrashed()->findOrFail($id);
        $qrCode->restore();

        return redirect()
            ->route('admin.parking-qr-codes.index')
            ->with('success', __('admin.qr_code_restored_successfully'));
    }

    /**
     * Generate QR code image (SVG format)
     */
    private function generateQrCodeImage(string $code, array $data): string
    {
        // Build QR data string
        $qrContent = json_encode([
            'code' => $code,
            'zone_id' => $data['zone_id'],
            'floor_id' => $data['floor_id'] ?? null,
            'type' => $data['type'],
            'url' => $data['redirect_url'] ?? route('parking.qr.redirect', ['code' => $code]),
        ]);

        // Generate QR code as SVG string
        $qrSvg = QrCode::size(300)->generate($qrContent);

        // Return as base64 encoded
        return 'data:image/svg+xml;base64,' . base64_encode($qrSvg);
    }

    /**
     * Download QR code image
     */
    public function download(ParkingQrCode $qrCode)
    {
        $filename = 'qr_code_' . $qrCode->code . '.svg';

        return response($qrCode->qr_data, 200)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }

    /**
     * Bulk generate QR codes for a zone
     */
    public function bulkGenerateForZone(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:parking_zones,id',
            'type' => 'required|in:zone,floor,slot',
        ]);

        $zone = ParkingZone::findOrFail($validated['zone_id']);
        $created = 0;

        if ($validated['type'] === 'zone') {
            // Create one QR code for the zone
            if (!ParkingQrCode::where('zone_id', $zone->id)->where('type', 'zone')->exists()) {
                $this->createQrCode($zone->id, null, 'zone');
                $created++;
            }
        } elseif ($validated['type'] === 'floor') {
            // Create QR code for each floor in the zone
            foreach ($zone->floors as $floor) {
                if (!ParkingQrCode::where('floor_id', $floor->id)->where('type', 'floor')->exists()) {
                    $this->createQrCode($zone->id, $floor->id, 'floor');
                    $created++;
                }
            }
        }

        return back()->with('success', __('admin.qr_codes_generated_successfully', ['count' => $created]));
    }

    /**
     * Helper method to create a QR code
     */
    private function createQrCode(int $zoneId, ?int $floorId, string $type): ParkingQrCode
    {
        $code = 'QR_' . uniqid() . '_' . now()->timestamp;
        $qrData = $this->generateQrCodeImage($code, [
            'zone_id' => $zoneId,
            'floor_id' => $floorId,
            'type' => $type,
        ]);

        return ParkingQrCode::create([
            'zone_id' => $zoneId,
            'floor_id' => $floorId,
            'code' => $code,
            'qr_data' => $qrData,
            'type' => $type,
            'is_active' => true,
        ]);
    }

    /**
     * Record QR code scan
     */
    public function recordScan(Request $request, string $code): JsonResponse
    {
        $qrCode = ParkingQrCode::where('code', $code)->firstOrFail();
        $qrCode->recordScan();

        return response()->json([
            'success' => true,
            'message' => 'QR code scanned successfully',
            'code' => $qrCode->code,
            'redirect_url' => $qrCode->redirect_url,
            'metadata' => $qrCode->metadata,
        ]);
    }

    /**
     * View statistics for QR codes
     */
    public function statistics(): View
    {
        $totalQrCodes = ParkingQrCode::count();
        $activeQrCodes = ParkingQrCode::where('is_active', true)->count();
        $totalScans = ParkingQrCode::sum('scan_count');
        $mostScanned = ParkingQrCode::mostScanned(10)->get();

        return view('admin.parking.qr-codes.statistics', compact(
            'totalQrCodes',
            'activeQrCodes',
            'totalScans',
            'mostScanned'
        ));
    }
}
