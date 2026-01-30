<?php

namespace App\Domains\Payment\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Payment\Services\PaymentService;
use App\Domains\Payment\Services\SSLCommerzService;
use App\Domains\Payment\Models\Payment;
use App\Domains\Booking\Models\Booking;
use App\Shared\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VisitorPaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
        protected SSLCommerzService $sslCommerzService,
        protected NotificationService $notificationService
    ) {
        $this->middleware(['auth', 'verified'])->except([
            'gatewaySuccess',
            'gatewayFailure',
            'gatewayCancel',
            'gatewayWebhook',
            'checkStatus'
        ]);
        $this->middleware('throttle:payments')->only(['store', 'apiStore']);
    }

    /**
     * List visitor payments
     */
    public function index(Request $request): View
    {
        $filters = [
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'payment_method' => $request->payment_method,
        ];

        $payments = $this->paymentService->getUserPayments(auth()->id(), $filters);

        return view('visitor.payments.index', compact('payments', 'filters'));
    }

    /**
     * Show create payment form for booking
     */
    public function create(Booking $booking): View
    {
        $this->authorize('pay', $booking);

        // Check if booking already has a successful payment
        if ($booking->isPaid()) {
            return redirect()->route('visitor.bookings.show', $booking)
                           ->with('info', __('payments.already_paid'));
        }

        $booking->load(['location', 'vehicle', 'slotType']);
        $paymentMethods = $this->paymentService->getAvailablePaymentMethods();

        return view('visitor.payments.create', compact('booking', 'paymentMethods'));
    }

    /**
     * Process payment
     */
    public function store(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorize('pay', $booking);

        $request->validate([
            'payment_method' => ['required', 'in:sslcommerz,bkash,nagad,rocket'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email'],
            'customer_phone' => ['required', 'string', 'regex:/^01[3-9]\d{8}$/'],
        ]);

        // Check if booking already paid
        if ($booking->isPaid()) {
            return redirect()->route('visitor.bookings.show', $booking)
                           ->with('info', __('payments.already_paid'));
        }

        DB::beginTransaction();
        try {
            // Create payment record
            $payment = $this->paymentService->createPayment([
                'user_id' => auth()->id(),
                'booking_id' => $booking->id,
                'amount' => $booking->total_cost,
                'currency' => 'BDT',
                'payment_method' => $request->payment_method,
                'gateway' => $this->getGatewayFromMethod($request->payment_method),
                'status' => 'pending',
                'customer_info' => [
                    'name' => $request->customer_name,
                    'email' => $request->customer_email,
                    'phone' => $request->customer_phone,
                ],
            ]);

            // Process payment based on method
            $paymentUrl = match ($request->payment_method) {
                'sslcommerz' => $this->processSSLCommerzPayment($payment, $request),
                'bkash' => $this->processBkashPayment($payment, $request),
                'nagad' => $this->processNagadPayment($payment, $request),
                'rocket' => $this->processRocketPayment($payment, $request),
                default => throw new \Exception('Unsupported payment method')
            };

            DB::commit();

            // Redirect to payment gateway
            return redirect()->away($paymentUrl);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Payment creation failed', [
                'booking_id' => $booking->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['payment' => __('payments.creation_failed')])
                        ->withInput();
        }
    }

    /**
     * Show payment details
     */
    public function show(Payment $payment): View
    {
        $this->authorize('view', $payment);

        $payment->load(['booking.location', 'booking.vehicle']);

        return view('visitor.payments.show', compact('payment'));
    }

    /**
     * Show payment invoice
     */
    public function invoice(Payment $payment): View
    {
        $this->authorize('view', $payment);

        $payment->load([
            'booking.location',
            'booking.vehicle',
            'booking.user',
            'user'
        ]);

        return view('visitor.payments.invoice', compact('payment'));
    }

    /**
     * Download payment invoice
     */
    public function download(Payment $payment): \Illuminate\Http\Response
    {
        $this->authorize('view', $payment);

        $pdf = $this->paymentService->generateInvoicePdf($payment);

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="payment-invoice-' . $payment->transaction_id . '.pdf"',
        ]);
    }

    // Gateway Callback Methods

    /**
     * Handle successful payment callback
     */
    public function gatewaySuccess(Request $request): RedirectResponse
    {
        $transactionId = $request->tran_id ?? $request->transaction_id;

        if (!$transactionId) {
            return redirect()->route('visitor.dashboard')
                           ->with('error', __('payments.invalid_response'));
        }

        DB::beginTransaction();
        try {
            $result = $this->paymentService->handleSuccessCallback($transactionId, $request->all());

            if ($result['success']) {
                $payment = $result['payment'];

                // Send payment success notification
                $this->notificationService->sendPaymentSuccess($payment);

                DB::commit();

                return redirect()->route('visitor.payments.show', $payment)
                               ->with('success', __('payments.success'));
            } else {
                DB::rollBack();

                return redirect()->route('visitor.dashboard')
                               ->with('error', $result['message']);
            }

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Payment success callback failed', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return redirect()->route('visitor.dashboard')
                           ->with('error', __('payments.processing_failed'));
        }
    }

    /**
     * Handle failed payment callback
     */
    public function gatewayFailure(Request $request): RedirectResponse
    {
        $transactionId = $request->tran_id ?? $request->transaction_id;

        if ($transactionId) {
            $this->paymentService->handleFailureCallback($transactionId, $request->all());
        }

        return redirect()->route('visitor.dashboard')
                       ->with('error', __('payments.failed'));
    }

    /**
     * Handle cancelled payment callback
     */
    public function gatewayCancel(Request $request): RedirectResponse
    {
        $transactionId = $request->tran_id ?? $request->transaction_id;

        if ($transactionId) {
            $this->paymentService->handleCancelCallback($transactionId, $request->all());
        }

        return redirect()->route('visitor.dashboard')
                       ->with('info', __('payments.cancelled'));
    }

    /**
     * Handle payment webhook (IPN)
     */
    public function gatewayWebhook(Request $request): JsonResponse
    {
        Log::info('Payment webhook received', $request->all());

        try {
            $result = $this->paymentService->handleWebhook($request->all());

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'] ?? 'OK'
            ]);

        } catch (\Exception $e) {
            Log::error('Payment webhook failed', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Webhook processing failed'
            ], 500);
        }
    }

    /**
     * Check payment status
     */
    public function checkStatus(Payment $payment): JsonResponse
    {
        $status = $this->paymentService->checkPaymentStatus($payment->id);

        return response()->json([
            'success' => true,
            'data' => [
                'payment' => $payment,
                'status' => $status
            ]
        ]);
    }

    // Payment Processing Methods

    /**
     * Process SSLCommerz payment
     */
    protected function processSSLCommerzPayment(Payment $payment, Request $request): string
    {
        $paymentData = [
            'total_amount' => $payment->amount,
            'currency' => $payment->currency,
            'tran_id' => $payment->transaction_id,
            'product_name' => 'Parking Booking - ' . $payment->booking->location->name,
            'product_category' => 'Parking',
            'product_profile' => 'general',
            'cus_name' => $request->customer_name,
            'cus_email' => $request->customer_email,
            'cus_add1' => $payment->booking->location->address,
            'cus_city' => $payment->booking->location->city,
            'cus_country' => 'Bangladesh',
            'cus_phone' => $request->customer_phone,
            'ship_name' => $request->customer_name,
            'ship_add1' => $payment->booking->location->address,
            'ship_city' => $payment->booking->location->city,
            'ship_country' => 'Bangladesh',
            'success_url' => route('visitor.payments.gateway.success'),
            'fail_url' => route('visitor.payments.gateway.failure'),
            'cancel_url' => route('visitor.payments.gateway.cancel'),
            'ipn_url' => route('visitor.payments.gateway.webhook'),
        ];

        return $this->sslCommerzService->createPaymentSession($paymentData);
    }

    /**
     * Process Bkash payment
     */
    protected function processBkashPayment(Payment $payment, Request $request): string
    {
        // Implement Bkash payment logic
        // For now, redirect to a placeholder
        return route('visitor.payments.gateway.success', ['tran_id' => $payment->transaction_id]);
    }

    /**
     * Process Nagad payment
     */
    protected function processNagadPayment(Payment $payment, Request $request): string
    {
        // Implement Nagad payment logic
        // For now, redirect to a placeholder
        return route('visitor.payments.gateway.success', ['tran_id' => $payment->transaction_id]);
    }

    /**
     * Process Rocket payment
     */
    protected function processRocketPayment(Payment $payment, Request $request): string
    {
        // Implement Rocket payment logic
        // For now, redirect to a placeholder
        return route('visitor.payments.gateway.success', ['tran_id' => $payment->transaction_id]);
    }

    /**
     * Get gateway from payment method
     */
    protected function getGatewayFromMethod(string $method): string
    {
        return match ($method) {
            'sslcommerz' => 'sslcommerz',
            'bkash' => 'bkash',
            'nagad' => 'nagad',
            'rocket' => 'rocket',
            default => 'sslcommerz'
        };
    }

    // API Methods

    /**
     * API: List payments
     */
    public function apiIndex(Request $request): JsonResponse
    {
        $filters = [
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'payment_method' => $request->payment_method,
            'per_page' => $request->per_page ?? 20,
        ];

        $payments = $this->paymentService->getUserPayments(auth()->id(), $filters);

        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }

    /**
     * API: Show payment
     */
    public function apiShow(Payment $payment): JsonResponse
    {
        $this->authorize('view', $payment);

        $payment->load(['booking.location', 'booking.vehicle']);

        return response()->json([
            'success' => true,
            'data' => $payment
        ]);
    }

    /**
     * API: Create payment
     */
    public function apiStore(Request $request, Booking $booking): JsonResponse
    {
        $this->authorize('pay', $booking);

        $request->validate([
            'payment_method' => ['required', 'in:sslcommerz,bkash,nagad,rocket'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email'],
            'customer_phone' => ['required', 'string', 'regex:/^01[3-9]\d{8}$/'],
        ]);

        // Check if booking already paid
        if ($booking->isPaid()) {
            return response()->json([
                'success' => false,
                'message' => __('payments.already_paid')
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Create payment record
            $payment = $this->paymentService->createPayment([
                'user_id' => auth()->id(),
                'booking_id' => $booking->id,
                'amount' => $booking->total_cost,
                'currency' => 'BDT',
                'payment_method' => $request->payment_method,
                'gateway' => $this->getGatewayFromMethod($request->payment_method),
                'status' => 'pending',
                'customer_info' => [
                    'name' => $request->customer_name,
                    'email' => $request->customer_email,
                    'phone' => $request->customer_phone,
                ],
            ]);

            // Get payment URL
            $paymentUrl = match ($request->payment_method) {
                'sslcommerz' => $this->processSSLCommerzPayment($payment, $request),
                'bkash' => $this->processBkashPayment($payment, $request),
                'nagad' => $this->processNagadPayment($payment, $request),
                'rocket' => $this->processRocketPayment($payment, $request),
                default => throw new \Exception('Unsupported payment method')
            };

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'payment' => $payment,
                    'payment_url' => $paymentUrl
                ],
                'message' => __('payments.created_successfully')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => __('payments.creation_failed'),
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
