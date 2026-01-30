<?php

namespace App\Domains\User\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Payment\Models\Payment;
use App\Domains\Payment\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PaymentController extends Controller
{
    protected $paymentService;
    protected $paymentRepository;

    public function __construct(PaymentService $paymentService, PaymentRepository $paymentRepository)
    {
        $this->middleware('auth');
        $this->paymentService = $paymentService;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * Display a listing of user's payments.
     */
    public function index(Request $request)
    {
        $filters = $request->only([
            'status',
            'gateway',
            'date_from',
            'date_to',
            'per_page'
        ]);

        $payments = $this->paymentRepository->getUserPayments(auth()->id(), $filters);

        return view('user.payments.index', compact('payments', 'filters'));
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        Gate::authorize('view', $payment);

        $payment->load([
            'booking.vehicle',
            'booking.parkingSlot.parkingLocation',
            'sslcommerzLogs' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ]);

        return view('user.payments.show', compact('payment'));
    }

    /**
     * Process payment.
     */
    public function process(Payment $payment)
    {
        Gate::authorize('update', $payment);

        if ($payment->status !== 'pending') {
            return redirect()->route('user.payments.show', $payment)
                           ->with('error', __('This payment cannot be processed.'));
        }

        // If payment URL exists, redirect to gateway
        if ($payment->payment_url) {
            return redirect($payment->payment_url);
        }

        // Otherwise, show payment form
        return view('user.payments.process', compact('payment'));
    }

    /**
     * Retry failed payment.
     */
    public function retry(Payment $payment)
    {
        Gate::authorize('update', $payment);

        if ($payment->status !== 'failed') {
            return response()->json([
                'success' => false,
                'message' => __('Only failed payments can be retried.'),
            ]);
        }

        try {
            $newPayment = $this->paymentService->initiatePayment($payment->booking, [
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'gateway' => $payment->gateway,
            ]);

            if ($newPayment) {
                return response()->json([
                    'success' => true,
                    'message' => __('Payment retry initiated successfully.'),
                    'redirect' => route('user.payments.process', $newPayment),
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('Failed to initiate payment retry.'),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Payment retry failed: :message', ['message' => $e->getMessage()]),
            ]);
        }
    }

    /**
     * Handle successful payment callback.
     */
    public function success(Request $request)
    {
        try {
            $result = $this->paymentService->processPaymentSuccess($request->all());

            if ($result) {
                return redirect()->route('user.dashboard')
                               ->with('success', __('Payment completed successfully!'));
            } else {
                return redirect()->route('user.dashboard')
                               ->with('error', __('Payment verification failed. Please contact support.'));
            }
        } catch (\Exception $e) {
            return redirect()->route('user.dashboard')
                           ->with('error', __('Payment processing error. Please contact support.'));
        }
    }

    /**
     * Handle failed payment callback.
     */
    public function failure(Request $request)
    {
        try {
            $this->paymentService->processPaymentFailure($request->all());

            return redirect()->route('user.dashboard')
                           ->with('error', __('Payment failed. You can try again from your bookings page.'));
        } catch (\Exception $e) {
            return redirect()->route('user.dashboard')
                           ->with('error', __('Payment processing error. Please contact support.'));
        }
    }

    /**
     * Handle cancelled payment callback.
     */
    public function cancel(Request $request)
    {
        return redirect()->route('user.dashboard')
                       ->with('warning', __('Payment was cancelled. You can try again from your bookings page.'));
    }

    /**
     * Handle IPN (Instant Payment Notification).
     */
    public function ipn(Request $request)
    {
        try {
            $result = $this->paymentService->processPaymentSuccess($request->all());

            return response('OK', 200);
        } catch (\Exception $e) {
            \Log::error('IPN processing failed: ' . $e->getMessage(), $request->all());
            return response('ERROR', 400);
        }
    }

    /**
     * Download payment receipt.
     */
    public function receipt(Payment $payment)
    {
        Gate::authorize('view', $payment);

        if ($payment->status !== 'completed') {
            return redirect()->back()
                           ->with('error', __('Receipt is only available for completed payments.'));
        }

        $payment->load([
            'user',
            'booking.vehicle',
            'booking.parkingSlot.parkingLocation'
        ]);

        return view('user.payments.receipt', compact('payment'));
    }

    /**
     * Download payment receipt as PDF.
     */
    public function downloadReceipt(Payment $payment)
    {
        Gate::authorize('view', $payment);

        if ($payment->status !== 'completed') {
            return redirect()->back()
                           ->with('error', __('Receipt is only available for completed payments.'));
        }

        // This would integrate with a PDF library like TCPDF or DomPDF
        // For now, return the receipt view
        return $this->receipt($payment);
    }
}
