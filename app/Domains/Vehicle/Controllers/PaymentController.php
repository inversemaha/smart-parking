<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use App\Repositories\PaymentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PaymentController extends Controller
{
    protected $paymentService;
    protected $paymentRepository;

    public function __construct(PaymentService $paymentService, PaymentRepository $paymentRepository)
    {
        $this->middleware('auth:sanctum')->except(['success', 'failure', 'cancel', 'ipn']);
        $this->paymentService = $paymentService;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * Display a listing of user's payments.
     */
    public function index(Request $request)
    {
        try {
            $filters = $request->only([
                'status',
                'gateway',
                'date_from',
                'date_to',
                'per_page'
            ]);

            $payments = $this->paymentRepository->getUserPayments(auth()->id(), $filters);

            return response()->json([
                'success' => true,
                'message' => 'Payments retrieved successfully',
                'data' => $payments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve payments: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        try {
            Gate::authorize('view', $payment);

            $payment->load([
                'booking.vehicle',
                'booking.parkingSlot.parkingLocation',
                'sslcommerzLogs' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment details retrieved successfully',
                'data' => $payment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve payment: ' . $e->getMessage()
            ], 403);
        }
    }

    /**
     * Retry failed payment.
     */
    public function retry(Payment $payment)
    {
        try {
            Gate::authorize('update', $payment);

            if ($payment->status !== 'failed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only failed payments can be retried'
                ], 422);
            }

            $newPayment = $this->paymentService->initiatePayment($payment->booking, [
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'gateway' => $payment->gateway,
            ]);

            if ($newPayment) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment retry initiated successfully',
                    'data' => [
                        'payment' => $newPayment,
                        'payment_url' => $newPayment->payment_url,
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to initiate payment retry'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment retry failed: ' . $e->getMessage()
            ], 500);
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
                return response()->json([
                    'success' => true,
                    'message' => 'Payment completed successfully',
                    'data' => [
                        'redirect_url' => config('app.frontend_url') . '/dashboard?payment=success'
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment verification failed',
                    'data' => [
                        'redirect_url' => config('app.frontend_url') . '/dashboard?payment=failed'
                    ]
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment processing error',
                'data' => [
                    'redirect_url' => config('app.frontend_url') . '/dashboard?payment=error'
                ]
            ], 500);
        }
    }

    /**
     * Handle failed payment callback.
     */
    public function failure(Request $request)
    {
        try {
            $this->paymentService->processPaymentFailure($request->all());

            return response()->json([
                'success' => false,
                'message' => 'Payment failed',
                'data' => [
                    'redirect_url' => config('app.frontend_url') . '/dashboard?payment=failed'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment processing error',
                'data' => [
                    'redirect_url' => config('app.frontend_url') . '/dashboard?payment=error'
                ]
            ], 500);
        }
    }

    /**
     * Handle cancelled payment callback.
     */
    public function cancel(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'Payment was cancelled',
            'data' => [
                'redirect_url' => config('app.frontend_url') . '/dashboard?payment=cancelled'
            ]
        ]);
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
     * Get payment methods.
     */
    public function paymentMethods()
    {
        $methods = [
            [
                'id' => 'sslcommerz',
                'name' => 'SSLCommerz',
                'type' => 'gateway',
                'logo' => '/images/payment/sslcommerz.png',
                'description' => 'Pay securely with SSLCommerz',
                'enabled' => config('services.sslcommerz.enabled', true),
                'currencies' => ['BDT'],
                'fees' => [
                    'percentage' => 1.5,
                    'fixed' => 0,
                ]
            ],
            // Add more payment methods as needed
        ];

        return response()->json([
            'success' => true,
            'message' => 'Payment methods retrieved successfully',
            'data' => $methods
        ]);
    }

    /**
     * Get payment statistics for user.
     */
    public function statistics(Request $request)
    {
        try {
            $period = $request->get('period', 'month');
            $filters = [];

            switch ($period) {
                case 'week':
                    $filters['date_from'] = now()->startOfWeek();
                    $filters['date_to'] = now()->endOfWeek();
                    break;
                case 'month':
                    $filters['date_from'] = now()->startOfMonth();
                    $filters['date_to'] = now()->endOfMonth();
                    break;
                case 'year':
                    $filters['date_from'] = now()->startOfYear();
                    $filters['date_to'] = now()->endOfYear();
                    break;
            }

            $totalPayments = $this->paymentRepository->getUserPayments(auth()->id(), $filters)->total();
            $completedPayments = $this->paymentRepository->getUserPayments(auth()->id(), array_merge($filters, ['status' => 'completed']))->total();
            $totalAmount = $this->paymentRepository->getUserPayments(auth()->id(), array_merge($filters, ['status' => 'completed']))->sum('amount');

            return response()->json([
                'success' => true,
                'message' => 'Payment statistics retrieved successfully',
                'data' => [
                    'total_payments' => $totalPayments,
                    'completed_payments' => $completedPayments,
                    'total_amount' => $totalAmount,
                    'success_rate' => $totalPayments > 0 ? round(($completedPayments / $totalPayments) * 100, 2) : 0,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}
