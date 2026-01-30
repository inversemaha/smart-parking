<?php

namespace App\Domains\User\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Domains\Payment\Models\Payment;
use App\Domains\Booking\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Get user's payments.
     */
    public function index(Request $request): JsonResponse
    {
        $query = $request->user()->payments()
            ->with(['booking.vehicle', 'booking.parkingSlot.parkingArea']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }

    /**
     * Create payment for booking.
     */
    public function store(Request $request, Booking $booking): JsonResponse
    {
        // Check ownership
        if ($booking->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($booking->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Booking must be pending to create payment'
            ], 400);
        }

        // Check if payment already exists
        if ($booking->payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment already exists for this booking'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:sslcommerz,bkash,nagad,rocket,card,cash',
            'amount' => 'required|numeric|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validate amount matches booking total
        if ($request->amount != $booking->total_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Payment amount does not match booking total'
            ], 400);
        }

        try {
            // Create payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'user_id' => $request->user()->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'currency' => 'BDT',
                'status' => 'pending',
                'transaction_id' => $this->generateTransactionId()
            ]);

            // Process payment based on method
            $paymentResult = $this->processPayment($payment, $request);

            if ($paymentResult['success']) {
                $payment->update([
                    'status' => 'processing',
                    'gateway_transaction_id' => $paymentResult['gateway_transaction_id'] ?? null,
                    'gateway_response' => $paymentResult['gateway_response'] ?? null
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment initiated successfully',
                    'data' => [
                        'payment' => $payment,
                        'payment_url' => $paymentResult['payment_url'] ?? null,
                        'qr_code' => $paymentResult['qr_code'] ?? null
                    ]
                ]);
            } else {
                $payment->update([
                    'status' => 'failed',
                    'failure_reason' => $paymentResult['error'] ?? 'Payment processing failed'
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment processing failed',
                    'error' => $paymentResult['error'] ?? 'Unknown error'
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Payment creation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Payment creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific payment.
     */
    public function show(Request $request, Payment $payment): JsonResponse
    {
        // Check ownership
        if ($payment->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $payment->load(['booking.vehicle', 'booking.parkingSlot.parkingArea']);

        return response()->json([
            'success' => true,
            'data' => $payment
        ]);
    }

    /**
     * Get payment receipt.
     */
    public function receipt(Request $request, Payment $payment): JsonResponse
    {
        // Check ownership
        if ($payment->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($payment->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Receipt only available for completed payments'
            ], 400);
        }

        $payment->load(['booking.vehicle', 'booking.parkingSlot.parkingArea', 'user']);

        $receipt = [
            'payment_id' => $payment->id,
            'transaction_id' => $payment->transaction_id,
            'gateway_transaction_id' => $payment->gateway_transaction_id,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'payment_method' => $payment->payment_method,
            'payment_date' => $payment->paid_at,
            'booking' => [
                'reference' => $payment->booking->booking_reference,
                'start_time' => $payment->booking->start_time,
                'end_time' => $payment->booking->end_time,
                'parking_area' => $payment->booking->parkingSlot->parkingArea->name,
                'slot_number' => $payment->booking->parkingSlot->slot_number,
                'vehicle' => [
                    'license_plate' => $payment->booking->vehicle->license_plate,
                    'brand' => $payment->booking->vehicle->brand,
                    'model' => $payment->booking->vehicle->model
                ]
            ],
            'user' => [
                'name' => $payment->user->name,
                'email' => $payment->user->email,
                'phone' => $payment->user->phone
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $receipt
        ]);
    }

    /**
     * Payment success callback.
     */
    public function success(Request $request): JsonResponse
    {
        $transactionId = $request->get('transaction_id');
        $gatewayTransactionId = $request->get('gateway_transaction_id');

        if (!$transactionId) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction ID is required'
            ], 400);
        }

        try {
            $payment = Payment::where('transaction_id', $transactionId)->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found'
                ], 404);
            }

            // Verify payment with gateway
            $verificationResult = $this->verifyPayment($payment, $gatewayTransactionId);

            if ($verificationResult['success']) {
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                    'gateway_transaction_id' => $gatewayTransactionId,
                    'gateway_response' => $verificationResult['response'] ?? null
                ]);

                // Update booking status
                $payment->booking->update(['status' => 'confirmed']);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment completed successfully',
                    'data' => $payment
                ]);
            } else {
                $payment->update([
                    'status' => 'failed',
                    'failure_reason' => $verificationResult['error'] ?? 'Payment verification failed'
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment verification failed',
                    'error' => $verificationResult['error']
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Payment success processing failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Payment failure callback.
     */
    public function failure(Request $request): JsonResponse
    {
        $transactionId = $request->get('transaction_id');

        if (!$transactionId) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction ID is required'
            ], 400);
        }

        try {
            $payment = Payment::where('transaction_id', $transactionId)->first();

            if ($payment) {
                $payment->update([
                    'status' => 'failed',
                    'failure_reason' => $request->get('error_message', 'Payment failed')
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Payment failed',
                'data' => ['transaction_id' => $transactionId]
            ]);

        } catch (\Exception $e) {
            Log::error('Payment failure processing failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Payment failure processing failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Payment cancel callback.
     */
    public function cancel(Request $request): JsonResponse
    {
        $transactionId = $request->get('transaction_id');

        if (!$transactionId) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction ID is required'
            ], 400);
        }

        try {
            $payment = Payment::where('transaction_id', $transactionId)->first();

            if ($payment) {
                $payment->update([
                    'status' => 'cancelled',
                    'failure_reason' => 'Payment cancelled by user'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment cancelled',
                'data' => ['transaction_id' => $transactionId]
            ]);

        } catch (\Exception $e) {
            Log::error('Payment cancellation processing failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Payment cancellation processing failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Helper methods

    private function processPayment(Payment $payment, Request $request): array
    {
        switch ($payment->payment_method) {
            case 'sslcommerz':
                return $this->processSSLCommerzPayment($payment, $request);

            case 'bkash':
                return $this->processBkashPayment($payment, $request);

            case 'nagad':
                return $this->processNagadPayment($payment, $request);

            case 'rocket':
                return $this->processRocketPayment($payment, $request);

            case 'card':
                return $this->processCardPayment($payment, $request);

            case 'cash':
                return $this->processCashPayment($payment, $request);

            default:
                return ['success' => false, 'error' => 'Unsupported payment method'];
        }
    }

    private function processSSLCommerzPayment(Payment $payment, Request $request): array
    {
        // Implement SSLCommerz integration
        try {
            // This is a simplified implementation - integrate with actual SSLCommerz API
            $sessionUrl = 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php'; // Use production URL for live

            return [
                'success' => true,
                'payment_url' => $sessionUrl,
                'gateway_transaction_id' => 'SSL_' . time()
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function processBkashPayment(Payment $payment, Request $request): array
    {
        // Implement bKash integration
        try {
            // This is a simplified implementation - integrate with actual bKash API
            return [
                'success' => true,
                'qr_code' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgAHggJ/PchI7wAAAABJRU5ErkJggg==',
                'gateway_transaction_id' => 'BKASH_' . time()
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function processNagadPayment(Payment $payment, Request $request): array
    {
        // Implement Nagad integration
        try {
            return [
                'success' => true,
                'payment_url' => 'https://nagad.com/pay/' . $payment->transaction_id,
                'gateway_transaction_id' => 'NAGAD_' . time()
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function processRocketPayment(Payment $payment, Request $request): array
    {
        // Implement Rocket integration
        try {
            return [
                'success' => true,
                'payment_url' => 'https://rocket.com.bd/pay/' . $payment->transaction_id,
                'gateway_transaction_id' => 'ROCKET_' . time()
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function processCardPayment(Payment $payment, Request $request): array
    {
        // Implement card payment processing
        try {
            return [
                'success' => true,
                'payment_url' => route('payments.card.form', ['payment' => $payment->id]),
                'gateway_transaction_id' => 'CARD_' . time()
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function processCashPayment(Payment $payment, Request $request): array
    {
        // For cash payments, mark as pending for manual verification
        return [
            'success' => true,
            'message' => 'Cash payment marked as pending. Please pay at the parking location.',
            'gateway_transaction_id' => 'CASH_' . time()
        ];
    }

    private function verifyPayment(Payment $payment, ?string $gatewayTransactionId): array
    {
        // Implement payment verification logic based on payment method
        switch ($payment->payment_method) {
            case 'sslcommerz':
                return $this->verifySSLCommerzPayment($payment, $gatewayTransactionId);

            case 'bkash':
                return $this->verifyBkashPayment($payment, $gatewayTransactionId);

            default:
                return ['success' => true]; // For other methods, assume verification is successful
        }
    }

    private function verifySSLCommerzPayment(Payment $payment, ?string $gatewayTransactionId): array
    {
        // Implement actual SSLCommerz verification
        try {
            // This is a simplified implementation
            return ['success' => true, 'response' => 'Payment verified'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function verifyBkashPayment(Payment $payment, ?string $gatewayTransactionId): array
    {
        // Implement actual bKash verification
        try {
            return ['success' => true, 'response' => 'Payment verified'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function generateTransactionId(): string
    {
        return 'TXN' . date('Ymd') . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
    }
}
