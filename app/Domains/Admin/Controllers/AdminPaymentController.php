<?php

namespace App\Domains\Admin\Controllers;

use App\Domains\Payment\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminPaymentController extends Controller
{
    /**
     * List all payments with filters and search.
     */
    public function index(Request $request)
    {
        $query = Payment::with('user', 'booking', 'sslcommerzLogs');

        // Search by payment_id or user name/email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('payment_id', 'ILIKE', "%{$search}%")
                  ->orWhereHas('user', function ($subQ) use ($search) {
                      $subQ->where('name', 'ILIKE', "%{$search}%")
                           ->orWhere('email', 'ILIKE', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        // Order by initiated_at descending
        $payments = $query->orderBy('initiated_at', 'desc')
                         ->paginate(15)
                         ->appends($request->query());

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Show payment details.
     */
    public function show(Payment $payment)
    {
        $payment->load('user', 'booking', 'sslcommerzLogs');

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Refund a payment.
     */
    public function refund(Request $request, Payment $payment)
    {
        // Validate only paid payments can be refunded
        if ($payment->status !== 'paid') {
            return redirect()->route('admin.payments.show', $payment)
                           ->with('error', 'Only paid payments can be refunded.');
        }

        // Mark as refunded (update status to refunded)
        $payment->update([
            'status' => 'refunded',
            'notes' => ($payment->notes ? $payment->notes . "\n" : '') . 'Refunded at ' . now()->format('M d, Y H:i:s'),
        ]);

        // TODO: Call payment gateway to process refund
        // Example: $payment->gateway_transaction_id can be used to refund via gateway

        return redirect()->route('admin.payments.show', $payment)
                       ->with('success', 'Payment refunded successfully.');
    }
}
