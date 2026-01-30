<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Booking\Models\Booking;
use App\Domains\Payment\Models\Payment;

class PaymentSeeder extends Seeder
{
    /**
     * Create test payments for bookings
     */
    public function run(): void
    {
        $this->command->info('🔄 Creating test payments...');

        $bookings = Booking::whereIn('status', ['confirmed', 'active', 'completed'])->get();

        if ($bookings->isEmpty()) {
            $this->command->warn('⚠️ No bookings found. Run BookingSeeder first.');
            return;
        }

        $paymentMethods = ['card', 'mobile_banking', 'internet_banking'];
        $gateways = ['sslcommerz', 'bkash', 'nagad'];
        $createdCount = 0;
        $skippedCount = 0;

        foreach ($bookings as $booking) {
            // Check if payment already exists for this booking
            $existingPayment = Payment::where('payable_type', 'App\\Domains\\Booking\\Models\\Booking')
                ->where('payable_id', $booking->id)
                ->first();

            if (!$existingPayment) {
                $paymentStatus = 'paid';

                // Some failed payments for cancelled bookings
                if ($booking->status === 'cancelled') {
                    $paymentStatus = collect(['failed', 'refunded'])->random();
                } elseif (rand(1, 20) === 1) { // 5% failed payments
                    $paymentStatus = 'failed';
                }

                $gateway = collect($gateways)->random();
                $paymentMethod = collect($paymentMethods)->random();

                Payment::create([
                    'payment_id' => 'PAY' . $booking->created_at->format('Ymd') . strtoupper(substr(md5(uniqid()), 0, 8)),
                    'user_id' => $booking->user_id,
                    'payable_type' => 'App\\Domains\\Booking\\Models\\Booking',
                    'payable_id' => $booking->id,
                    'payment_method' => $paymentMethod,
                    'gateway' => $gateway,
                    'amount' => $booking->total_amount,
                    'currency' => 'BDT',
                    'status' => $paymentStatus,
                    'gateway_transaction_id' => strtoupper($gateway) . time() . rand(1000, 9999),
                    'gateway_response' => [
                        'status' => $paymentStatus === 'paid' ? 'VALID' : 'FAILED',
                        'tran_date' => $booking->created_at->format('Y-m-d H:i:s'),
                        'amount' => $booking->total_amount,
                        'currency' => 'BDT',
                        'method' => $paymentMethod
                    ],
                    'initiated_at' => $booking->created_at->addMinutes(2),
                    'paid_at' => $paymentStatus === 'paid' ? $booking->created_at->addMinutes(5) : null,
                    'failed_at' => $paymentStatus === 'failed' ? $booking->created_at->addMinutes(10) : null,
                    'notes' => $paymentStatus === 'paid' ? 'Payment successful' : 'Payment ' . $paymentStatus,
                    'created_at' => $booking->created_at->addMinutes(2),
                    'updated_at' => $paymentStatus === 'paid' ? $booking->created_at->addMinutes(5) : $booking->created_at->addMinutes(10)
                ]);

                $createdCount++;
            } else {
                $skippedCount++;
            }
        }

        $this->command->info("✅ Created {$createdCount} payments, skipped {$skippedCount} existing payments");
        $this->command->info('✅ Payment seeding completed.');
    }
}
