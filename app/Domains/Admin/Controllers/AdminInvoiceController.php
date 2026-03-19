<?php

namespace App\Domains\Admin\Controllers;

use App\Domains\Payment\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminInvoiceController extends Controller
{
    /**
     * List all invoices with filters and search.
     */
    public function index(Request $request)
    {
        $query = Invoice::with('user', 'booking', 'payment');

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
                $query->where('status', 'unpaid')
                      ->where('due_date', '<', now());
            } else {
                $query->where('status', $request->status);
            }
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
        $invoice->load('user', 'booking', 'payment');

        return view('admin.invoices.show', compact('invoice'));
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
        // Validate only unpaid invoices can be marked as paid
        if ($invoice->status === 'paid') {
            return redirect()->route('admin.invoices.show', $invoice)
                           ->with('error', 'This invoice is already marked as paid.');
        }

        // Mark as paid
        $invoice->markAsPaid(now());

        return redirect()->route('admin.invoices.show', $invoice)
                       ->with('success', 'Invoice marked as paid successfully.');
    }
}
