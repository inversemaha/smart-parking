<?php

namespace App\Domains\Admin\Controllers;

use App\Domains\Payment\Models\Invoice;
use App\Domains\Parking\Models\ParkingSession;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminInvoiceController extends Controller
{
    /**
     * List all invoices with filters and search.
     */
    public function index(Request $request)
    {
        $query = Invoice::with('user', 'parkingSession', 'booking', 'payment');

        // Search by invoice_number or user name/email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'ILIKE', "%{$search}%")
                  ->orWhereHas('user', function ($subQ) use ($search) {
                      $subQ->where('name', 'ILIKE', "%{$search}%")
                           ->orWhere('email', 'ILIKE', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            if ($request->status === 'overdue') {
                $query->where('status', Invoice::STATUS_OVERDUE);
            } else {
                $query->where('status', $request->status);
            }
        }

        // Filter by type (parking or booking)
        if ($request->has('type') && $request->type) {
            if ($request->type === 'parking') {
                $query->whereNotNull('parking_session_id');
            } elseif ($request->type === 'booking') {
                $query->whereNotNull('booking_id');
            }
        }

        // Date range filter
        if ($request->has('from_date') && $request->from_date) {
            $query->where('issued_at', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date) {
            $query->where('issued_at', '<=', $request->to_date . ' 23:59:59');
        }

        // Order by issued_at descending
        $invoices = $query->orderBy('issued_at', 'desc')
                         ->paginate(15)
                         ->appends($request->query());

        return view('admin.invoices.index', compact('invoices'));
    }

    /**
     * Show invoice details.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load('user', 'parkingSession', 'booking', 'payment');

        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Create invoice from parking session
     */
    public function create(Request $request)
    {
        // Get unpaid parking sessions
        $sessions = ParkingSession::completed()
            ->whereNull('invoice_id')
            ->where('session_status', 'completed')
            ->with('vehicle', 'zone', 'user')
            ->paginate(10);

        return view('admin.invoices.create', compact('sessions'));
    }

    /**
     * Generate invoice from parking session
     */
    public function generateFromSession(Request $request, ParkingSession $session)
    {
        $validated = $request->validate([
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Check if invoice already exists for this session
        if ($session->invoice_id) {
            return redirect()->route('admin.invoices.show', $session->invoice)
                           ->with('error', 'Invoice already exists for this session.');
        }

        // Calculate invoice amount
        $baseAmount = $session->total_charge ?? 0;
        $taxAmount = $validated['tax_percentage'] ? ($baseAmount * $validated['tax_percentage'] / 100) : 0;
        $discountAmount = $validated['discount_amount'] ?? 0;
        $totalAmount = $baseAmount + $taxAmount - $discountAmount;

        // Create invoice
        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'user_id' => $session->user_id,
            'parking_session_id' => $session->id,
            'amount' => $baseAmount,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'currency' => 'BDT',
            'status' => Invoice::STATUS_ISSUED,
            'payment_status' => Invoice::PAYMENT_STATUS_UNPAID,
            'issued_at' => now(),
            'due_date' => now()->addDays(7),
            'description' => "Parking session: {$session->zone->name}",
            'notes' => $validated['notes'] ?? null,
            'metadata' => [
                'session_duration' => $session->duration_minutes ?? 0,
                'zone_name' => $session->zone->name,
                'vehicle_plate' => $session->vehicle->license_plate ?? null,
            ],
        ]);

        // Update session with invoice_id
        $session->update(['invoice_id' => $invoice->id]);

        return redirect()->route('admin.invoices.show', $invoice)
                       ->with('success', 'Invoice generated successfully from parking session.');
    }

    /**
     * Download invoice as PDF.
     */
    public function download(Invoice $invoice)
    {
        // TODO: Implement PDF generation using a library like DomPDF
        // For now, return the show page which can be printed
        return $this->show($invoice);
    }

    /**
     * Mark invoice as paid.
     */
    public function markPaid(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string',
        ]);

        // Check if already paid
        if ($invoice->isPaid()) {
            return redirect()->route('admin.invoices.show', $invoice)
                           ->with('error', 'This invoice is already marked as paid.');
        }

        // Mark as paid
        $invoice->update([
            'status' => Invoice::STATUS_PAID,
            'payment_status' => Invoice::PAYMENT_STATUS_PAID,
            'paid_at' => now(),
            'payment_method' => $validated['payment_method'],
            'reference_number' => $validated['reference_number'] ?? null,
        ]);

        return redirect()->route('admin.invoices.show', $invoice)
                       ->with('success', 'Invoice marked as paid successfully.');
    }

    /**
     * Record partial payment
     */
    public function recordPayment(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string',
        ]);

        if ($invoice->isPaid()) {
            return redirect()->route('admin.invoices.show', $invoice)
                           ->with('error', 'This invoice is already fully paid.');
        }

        $remaining = $invoice->getRemainingAmount();
        if ($validated['amount'] > $remaining) {
            return redirect()->back()
                           ->with('error', "Payment exceeds remaining amount of {$remaining}.");
        }

        // Record payment
        $invoice->recordPartialPayment($validated['amount']);
        $invoice->update([
            'payment_method' => $validated['payment_method'],
            'reference_number' => $validated['reference_number'] ?? null,
        ]);

        return redirect()->route('admin.invoices.show', $invoice)
                       ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Cancel invoice
     */
    public function cancel(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'reason' => 'nullable|string',
        ]);

        if (!$invoice->cancel($validated['reason'] ?? null)) {
            return redirect()->route('admin.invoices.show', $invoice)
                           ->with('error', 'Cannot cancel a paid invoice.');
        }

        return redirect()->route('admin.invoices.show', $invoice)
                       ->with('success', 'Invoice cancelled successfully.');
    }

    /**
     * Revenue report
     */
    public function revenue(Request $request)
    {
        $query = Invoice::whereStatus(Invoice::STATUS_PAID);

        // Date range
        if ($request->has('from_date') && $request->from_date) {
            $query->where('paid_at', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date) {
            $query->where('paid_at', '<=', $request->to_date . ' 23:59:59');
        }

        // Overall stats
        $totalRevenue = (clone $query)->sum('total_amount');
        $totalTax = (clone $query)->sum('tax_amount');
        $totalDiscount = (clone $query)->sum('discount_amount');
        $invoiceCount = (clone $query)->count();
        $averageInvoice = $invoiceCount > 0 ? $totalRevenue / $invoiceCount : 0;

        // By type
        $parkingRevenue = (clone $query)->whereNotNull('parking_session_id')->sum('total_amount');
        $bookingRevenue = (clone $query)->whereNotNull('booking_id')->sum('total_amount');

        // By status
        $invoices = $query->orderBy('paid_at', 'desc')->paginate(15);

        return view('admin.invoices.revenue', compact(
            'invoices',
            'totalRevenue',
            'totalTax',
            'totalDiscount',
            'invoiceCount',
            'averageInvoice',
            'parkingRevenue',
            'bookingRevenue'
        ));
    }

    /**
     * Overdue invoices report
     */
    public function overdue(Request $request)
    {
        $invoices = Invoice::overdue()
            ->with('user', 'parkingSession', 'booking')
            ->orderBy('due_date', 'asc')
            ->paginate(15);

        $totalOverdue = Invoice::overdue()->sum('total_amount');
        $overdueCount = Invoice::overdue()->count();

        return view('admin.invoices.overdue', compact('invoices', 'totalOverdue', 'overdueCount'));
    }

    /**
     * Refund processing
     */
    public function refund(Request $request, Invoice $invoice)
    {
        if (!$invoice->isPaid()) {
            return redirect()->route('admin.invoices.show', $invoice)
                           ->with('error', 'Cannot refund an unpaid invoice.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'required|string',
        ]);

        if ($validated['amount'] > $invoice->total_amount) {
            return redirect()->back()
                           ->with('error', 'Refund amount exceeds invoice total.');
        }

        // Refund logic
        $metadata = $invoice->metadata ?? [];
        $metadata['refund'] = [
            'amount' => $validated['amount'],
            'reason' => $validated['reason'],
            'date' => now(),
        ];

        $invoice->update([
            'status' => Invoice::STATUS_ISSUED, // Return to issued status
            'payment_status' => Invoice::PAYMENT_STATUS_UNPAID,
            'metadata' => $metadata,
        ]);

        return redirect()->route('admin.invoices.show', $invoice)
                       ->with('success', "Refund of {$validated['amount']} processed successfully.");
    }

    /**
     * Check and update overdue invoices
     */
    public function checkOverdue()
    {
        $unpaidInvoices = Invoice::whereIn('payment_status', [Invoice::PAYMENT_STATUS_UNPAID, Invoice::PAYMENT_STATUS_PARTIAL])
            ->where('due_date', '<', now())
            ->where('status', '!=', Invoice::STATUS_OVERDUE)
            ->get();

        $count = 0;
        foreach ($unpaidInvoices as $invoice) {
            $invoice->checkAndMarkOverdue();
            $count++;
        }

        return redirect()->back()->with('success', "$count invoices marked as overdue.");
    }
}

