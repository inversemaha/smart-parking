<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Vehicle;
use App\Models\ParkingSlot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

/**
 * Job to generate various reports
 */
class GenerateReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $reportType;
    protected array $filters;
    protected User $requestedBy;
    protected string $format;

    /**
     * Create a new job instance.
     */
    public function __construct(string $reportType, array $filters, User $requestedBy, string $format = 'csv')
    {
        $this->reportType = $reportType;
        $this->filters = $filters;
        $this->requestedBy = $requestedBy;
        $this->format = $format;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info("Generating {$this->reportType} report", [
                'requested_by' => $this->requestedBy->id,
                'filters' => $this->filters,
                'format' => $this->format
            ]);

            $data = $this->generateReportData();
            $filename = $this->saveReport($data);

            Log::info('Report generated successfully', [
                'report_type' => $this->reportType,
                'filename' => $filename,
                'requested_by' => $this->requestedBy->id
            ]);

            // TODO: Send notification to user that report is ready
            // TODO: Store report metadata in database for tracking

        } catch (\Exception $e) {
            Log::error('Error generating report: ' . $e->getMessage(), [
                'report_type' => $this->reportType,
                'requested_by' => $this->requestedBy->id
            ]);
            throw $e;
        }
    }

    /**
     * Generate report data based on type.
     */
    protected function generateReportData(): array
    {
        return match($this->reportType) {
            'revenue' => $this->generateRevenueReport(),
            'bookings' => $this->generateBookingReport(),
            'vehicles' => $this->generateVehicleReport(),
            'users' => $this->generateUserReport(),
            'occupancy' => $this->generateOccupancyReport(),
            'parking_usage' => $this->generateParkingUsageReport(),
            default => throw new \InvalidArgumentException("Unknown report type: {$this->reportType}")
        };
    }

    /**
     * Generate revenue report data.
     */
    protected function generateRevenueReport(): array
    {
        $query = Payment::with(['booking.parkingSlot.parkingLocation'])
            ->where('status', 'completed');

        // Apply date filters
        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        $payments = $query->get();

        $data = [
            'headers' => ['Date', 'Booking ID', 'Amount', 'Gateway', 'Location', 'Vehicle', 'Customer'],
            'rows' => []
        ];

        foreach ($payments as $payment) {
            $data['rows'][] = [
                'Date' => $payment->created_at->format('Y-m-d H:i'),
                'Booking ID' => $payment->booking_id,
                'Amount' => 'BDT ' . number_format($payment->amount, 2),
                'Gateway' => strtoupper($payment->gateway),
                'Location' => $payment->booking->parkingSlot->parkingLocation->name ?? 'N/A',
                'Vehicle' => $payment->booking->vehicle->number_plate ?? 'N/A',
                'Customer' => $payment->booking->user->name ?? 'N/A',
            ];
        }

        return $data;
    }

    /**
     * Generate booking report data.
     */
    protected function generateBookingReport(): array
    {
        $query = Booking::with(['user', 'vehicle', 'parkingSlot.parkingLocation']);

        // Apply filters
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        $bookings = $query->get();

        $data = [
            'headers' => ['Booking ID', 'User', 'Vehicle', 'Location', 'Slot', 'Status', 'Created', 'Entry Time', 'Exit Time'],
            'rows' => []
        ];

        foreach ($bookings as $booking) {
            $data['rows'][] = [
                'Booking ID' => $booking->id,
                'User' => $booking->user->name,
                'Vehicle' => $booking->vehicle->number_plate,
                'Location' => $booking->parkingSlot->parkingLocation->name ?? 'N/A',
                'Slot' => $booking->parkingSlot->slot_number ?? 'N/A',
                'Status' => ucfirst($booking->status),
                'Created' => $booking->created_at->format('Y-m-d H:i'),
                'Entry Time' => $booking->entry_time ? $booking->entry_time->format('Y-m-d H:i') : 'N/A',
                'Exit Time' => $booking->exit_time ? $booking->exit_time->format('Y-m-d H:i') : 'N/A',
            ];
        }

        return $data;
    }

    /**
     * Generate vehicle report data.
     */
    protected function generateVehicleReport(): array
    {
        $query = Vehicle::with(['user']);

        // Apply filters
        if (!empty($this->filters['verification_status'])) {
            $query->where('verification_status', $this->filters['verification_status']);
        }
        if (!empty($this->filters['vehicle_type'])) {
            $query->where('vehicle_type', $this->filters['vehicle_type']);
        }

        $vehicles = $query->get();

        $data = [
            'headers' => ['Number Plate', 'Type', 'Owner', 'Mobile', 'Status', 'Verified', 'BRTA Verified', 'Registered'],
            'rows' => []
        ];

        foreach ($vehicles as $vehicle) {
            $data['rows'][] = [
                'Number Plate' => $vehicle->number_plate,
                'Type' => ucfirst($vehicle->vehicle_type),
                'Owner' => $vehicle->owner_name,
                'Mobile' => $vehicle->owner_mobile,
                'Status' => ucfirst($vehicle->verification_status ?? 'pending'),
                'Verified' => $vehicle->verified_at ? $vehicle->verified_at->format('Y-m-d') : 'No',
                'BRTA Verified' => $vehicle->brta_verified ? 'Yes' : 'No',
                'Registered' => $vehicle->created_at->format('Y-m-d'),
            ];
        }

        return $data;
    }

    /**
     * Generate user report data.
     */
    protected function generateUserReport(): array
    {
        $query = User::with(['roles']);

        // Apply filters
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        $users = $query->get();

        $data = [
            'headers' => ['Name', 'Mobile', 'Email', 'Status', 'Roles', 'Last Login', 'Registered'],
            'rows' => []
        ];

        foreach ($users as $user) {
            $data['rows'][] = [
                'Name' => $user->name,
                'Mobile' => $user->mobile,
                'Email' => $user->email ?? 'N/A',
                'Status' => ucfirst($user->status),
                'Roles' => $user->roles->pluck('name')->join(', '),
                'Last Login' => $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : 'Never',
                'Registered' => $user->created_at->format('Y-m-d'),
            ];
        }

        return $data;
    }

    /**
     * Generate occupancy report data.
     */
    protected function generateOccupancyReport(): array
    {
        // This would require complex queries to calculate occupancy rates
        // For now, return a simple structure
        $data = [
            'headers' => ['Location', 'Total Slots', 'Occupied', 'Available', 'Occupancy Rate'],
            'rows' => []
        ];

        // TODO: Implement occupancy calculation logic

        return $data;
    }

    /**
     * Generate parking usage report data.
     */
    protected function generateParkingUsageReport(): array
    {
        // TODO: Implement parking usage analytics
        $data = [
            'headers' => ['Location', 'Date', 'Peak Hours', 'Total Bookings', 'Average Duration'],
            'rows' => []
        ];

        return $data;
    }

    /**
     * Save report data to file.
     */
    protected function saveReport(array $data): string
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "reports/{$this->reportType}_{$timestamp}.{$this->format}";

        if ($this->format === 'csv') {
            $content = $this->generateCSV($data);
        } else {
            throw new \InvalidArgumentException("Unsupported format: {$this->format}");
        }

        Storage::disk('local')->put($filename, $content);

        return $filename;
    }

    /**
     * Generate CSV content from data.
     */
    protected function generateCSV(array $data): string
    {
        $output = fopen('php://temp', 'r+');

        // Write headers
        fputcsv($output, $data['headers']);

        // Write rows
        foreach ($data['rows'] as $row) {
            fputcsv($output, $row);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }

    /**
     * Handle job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('GenerateReport job failed: ' . $exception->getMessage(), [
            'report_type' => $this->reportType,
            'requested_by' => $this->requestedBy->id
        ]);
    }
}
