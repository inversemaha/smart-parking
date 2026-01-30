<?php

namespace App\Domains\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\User\Models\User;
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Booking\Models\Booking;
use App\Domains\Payment\Models\Payment;
use App\Domains\Parking\Models\ParkingSlot;
use App\Domains\Admin\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

/**
 * ReportController
 *
 * Handles administrative reporting functionality including
 * revenue, booking, vehicle, user, and occupancy reports.
 */
class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
        $this->middleware(['auth', 'role:admin']);
        $this->middleware('permission:admin.reports.view');
    }

    /**
     * Get revenue report.
     */
    public function getRevenueReport(Request $request): JsonResponse|View
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'group_by' => 'nullable|in:day,week,month,year',
            'format' => 'nullable|in:json,csv,pdf'
        ]);

        $startDate = $request->get('start_date', Carbon::now()->subMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->toDateString());
        $groupBy = $request->get('group_by', 'day');

        $data = $this->reportService->getRevenueReport($startDate, $endDate, $groupBy);

        if ($request->get('format') === 'csv') {
            return $this->exportRevenueToCsv($data, $startDate, $endDate);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $data,
                'meta' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'group_by' => $groupBy,
                    'total_revenue' => collect($data['chart_data'])->sum('amount'),
                    'total_transactions' => collect($data['chart_data'])->sum('count')
                ]
            ]);
        }

        return view('admin.reports.revenue', compact('data', 'startDate', 'endDate', 'groupBy'));
    }

    /**
     * Get booking report.
     */
    public function getBookingReport(Request $request): JsonResponse|View
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:active,completed,expired,cancelled',
            'format' => 'nullable|in:json,csv,pdf'
        ]);

        $startDate = $request->get('start_date', Carbon::now()->subMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->toDateString());
        $status = $request->get('status');

        $data = $this->reportService->getBookingReport($startDate, $endDate, $status);

        if ($request->get('format') === 'csv') {
            return $this->exportBookingsToCsv($data, $startDate, $endDate);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $data,
                'meta' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => $status
                ]
            ]);
        }

        return view('admin.reports.bookings', compact('data', 'startDate', 'endDate', 'status'));
    }

    /**
     * Get vehicle report.
     */
    public function getVehicleReport(Request $request): JsonResponse|View
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'verification_status' => 'nullable|in:pending,verified,rejected',
            'type' => 'nullable|in:car,motorcycle,bike',
            'format' => 'nullable|in:json,csv,pdf'
        ]);

        $startDate = $request->get('start_date', Carbon::now()->subMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->toDateString());
        $verificationStatus = $request->get('verification_status');
        $type = $request->get('type');

        $data = $this->reportService->getVehicleReport($startDate, $endDate, $verificationStatus, $type);

        if ($request->get('format') === 'csv') {
            return $this->exportVehiclesToCsv($data, $startDate, $endDate);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $data,
                'meta' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'verification_status' => $verificationStatus,
                    'type' => $type
                ]
            ]);
        }

        return view('admin.reports.vehicles', compact('data', 'startDate', 'endDate', 'verificationStatus', 'type'));
    }

    /**
     * Get user report.
     */
    public function getUserReport(Request $request): JsonResponse|View
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:active,suspended,pending_verification',
            'format' => 'nullable|in:json,csv,pdf'
        ]);

        $startDate = $request->get('start_date', Carbon::now()->subMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->toDateString());
        $status = $request->get('status');

        $data = $this->reportService->getUserReport($startDate, $endDate, $status);

        if ($request->get('format') === 'csv') {
            return $this->exportUsersToCsv($data, $startDate, $endDate);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $data,
                'meta' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => $status
                ]
            ]);
        }

        return view('admin.reports.users', compact('data', 'startDate', 'endDate', 'status'));
    }

    /**
     * Get occupancy report.
     */
    public function getOccupancyReport(Request $request): JsonResponse|View
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location_id' => 'nullable|integer|exists:parking_locations,id',
            'slot_type' => 'nullable|in:regular,premium,handicap',
            'format' => 'nullable|in:json,csv,pdf'
        ]);

        $startDate = $request->get('start_date', Carbon::now()->subMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->toDateString());
        $locationId = $request->get('location_id');
        $slotType = $request->get('slot_type');

        $data = $this->reportService->getOccupancyReport($startDate, $endDate, $locationId, $slotType);

        if ($request->get('format') === 'csv') {
            return $this->exportOccupancyToCsv($data, $startDate, $endDate);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $data,
                'meta' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'location_id' => $locationId,
                    'slot_type' => $slotType
                ]
            ]);
        }

        return view('admin.reports.occupancy', compact('data', 'startDate', 'endDate', 'locationId', 'slotType'));
    }

    /**
     * Export a generic report.
     */
    public function exportReport(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $request->validate([
            'type' => 'required|in:revenue,booking,vehicle,user,occupancy',
            'format' => 'required|in:csv,pdf',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $type = $request->get('type');
        $format = $request->get('format');

        if ($format === 'csv') {
            return $this->exportReportToCsv($type, $request);
        }

        // PDF export would be implemented here
        throw new \Exception('PDF export not yet implemented');
    }

    /**
     * Export revenue report to CSV.
     */
    protected function exportRevenueToCsv(array $data, string $startDate, string $endDate)
    {
        $fileName = "revenue_report_{$startDate}_to_{$endDate}.csv";

        return Response::streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Date', 'Revenue', 'Transactions', 'Average Transaction']);

            foreach ($data['chart_data'] as $item) {
                fputcsv($handle, [
                    $item['date'],
                    $item['amount'],
                    $item['count'],
                    $item['count'] > 0 ? round($item['amount'] / $item['count'], 2) : 0
                ]);
            }

            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv']);
    }

    /**
     * Export booking report to CSV.
     */
    protected function exportBookingsToCsv(array $data, string $startDate, string $endDate)
    {
        $fileName = "booking_report_{$startDate}_to_{$endDate}.csv";

        return Response::streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Booking ID', 'User', 'Vehicle', 'Slot', 'Start Time',
                'End Time', 'Amount', 'Status', 'Created At'
            ]);

            foreach ($data['bookings'] as $booking) {
                fputcsv($handle, [
                    $booking['id'],
                    $booking['user_name'],
                    $booking['vehicle_registration'],
                    $booking['slot_number'],
                    $booking['start_time'],
                    $booking['end_time'],
                    $booking['amount'],
                    $booking['status'],
                    $booking['created_at']
                ]);
            }

            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv']);
    }

    /**
     * Export vehicle report to CSV.
     */
    protected function exportVehiclesToCsv(array $data, string $startDate, string $endDate)
    {
        $fileName = "vehicle_report_{$startDate}_to_{$endDate}.csv";

        return Response::streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Registration', 'Owner', 'Type', 'Brand', 'Model',
                'Verification Status', 'Created At'
            ]);

            foreach ($data['vehicles'] as $vehicle) {
                fputcsv($handle, [
                    $vehicle['registration_number'],
                    $vehicle['owner_name'],
                    $vehicle['type'],
                    $vehicle['brand'],
                    $vehicle['model'],
                    $vehicle['verification_status'],
                    $vehicle['created_at']
                ]);
            }

            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv']);
    }

    /**
     * Export user report to CSV.
     */
    protected function exportUsersToCsv(array $data, string $startDate, string $endDate)
    {
        $fileName = "user_report_{$startDate}_to_{$endDate}.csv";

        return Response::streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Name', 'Mobile', 'Status', 'Vehicles Count',
                'Total Bookings', 'Total Spent', 'Last Login', 'Created At'
            ]);

            foreach ($data['users'] as $user) {
                fputcsv($handle, [
                    $user['name'],
                    $user['mobile'],
                    $user['status'],
                    $user['vehicles_count'],
                    $user['bookings_count'],
                    $user['total_spent'],
                    $user['last_login_at'],
                    $user['created_at']
                ]);
            }

            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv']);
    }

    /**
     * Export occupancy report to CSV.
     */
    protected function exportOccupancyToCsv(array $data, string $startDate, string $endDate)
    {
        $fileName = "occupancy_report_{$startDate}_to_{$endDate}.csv";

        return Response::streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Date', 'Location', 'Total Slots', 'Occupied Slots',
                'Occupancy Rate (%)', 'Peak Hour', 'Peak Occupancy'
            ]);

            foreach ($data['occupancy_data'] as $item) {
                fputcsv($handle, [
                    $item['date'],
                    $item['location_name'],
                    $item['total_slots'],
                    $item['occupied_slots'],
                    round($item['occupancy_rate'], 2),
                    $item['peak_hour'] ?? 'N/A',
                    $item['peak_occupancy'] ?? 'N/A'
                ]);
            }

            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv']);
    }

    /**
     * Generic export method.
     */
    protected function exportReportToCsv(string $type, Request $request)
    {
        switch ($type) {
            case 'revenue':
                return $this->getRevenueReport($request->merge(['format' => 'csv']));
            case 'booking':
                return $this->getBookingReport($request->merge(['format' => 'csv']));
            case 'vehicle':
                return $this->getVehicleReport($request->merge(['format' => 'csv']));
            case 'user':
                return $this->getUserReport($request->merge(['format' => 'csv']));
            case 'occupancy':
                return $this->getOccupancyReport($request->merge(['format' => 'csv']));
            default:
                throw new \InvalidArgumentException("Unknown report type: {$type}");
        }
    }
}
