<?php

namespace Database\Seeders;

use App\Domains\Payment\Models\Invoice;
use App\Domains\Parking\Models\ParkingSession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Phase5BillingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "\n🔄 Seeding Phase 5: Billing & Invoicing...\n";

        // Get completed parking sessions
        $sessions = ParkingSession::where('session_status', 'completed')
            ->with('vehicle.user', 'zone')
            ->get();

        if ($sessions->isEmpty()) {
            echo "❌ No completed sessions found to generate invoices.\n";
            return;
        }

        $invoiceCount = 0;
        $paidCount = 0;
        $pendingCount = 0;
        $overdueCount = 0;

        // Generate invoices from sessions
        foreach ($sessions->take(12) as $session) {
            try {
                // Get user from vehicle relationship
                $user = $session->vehicle?->user;
                if (!$user) {
                    echo "⚠️  Skipping session {$session->id} - No user found\n";
                    continue;
                }

                // Calculate base amount from session
                $baseAmount = $session->total_charge ?? $session->calculateCharges();
                $taxPercentage = 5; // 5% VAT
                $taxAmount = ($baseAmount * $taxPercentage) / 100;
                
                // Random discount for some invoices (1-2%)
                $discountAmount = rand(0, 1) === 1 ? ($baseAmount * 0.01) : 0;
                $totalAmount = $baseAmount + $taxAmount - $discountAmount;

                // Create invoice
                $invoice = Invoice::create([
                    'invoice_number' => Invoice::generateInvoiceNumber(),
                    'user_id' => $user->id,
                    'parking_session_id' => $session->id,
                    'amount' => round($baseAmount, 2),
                    'tax_amount' => round($taxAmount, 2),
                    'discount_amount' => round($discountAmount, 2),
                    'total_amount' => round($totalAmount, 2),
                    'currency' => 'BDT',
                    'issued_at' => $session->exit_time ?? now()->subDays(rand(1, 7)),
                    'due_date' => now()->addDays(rand(3, 7)),
                    'description' => "Parking session: {$session->zone->name}",
                    'metadata' => [
                        'duration_minutes' => $session->duration_minutes ?? 60,
                        'zone_name' => $session->zone->name,
                        'vehicle_plate' => $session->vehicle->license_plate ?? 'N/A',
                        'entry_time' => $session->entry_time,
                        'exit_time' => $session->exit_time,
                    ],
                ]);

                // Randomly assign payment status for better data distribution
                $statusRandom = rand(0, 2);

                if ($statusRandom === 0) {
                    // 33% paid
                    $invoice->update([
                        'status' => Invoice::STATUS_PAID,
                        'payment_status' => Invoice::PAYMENT_STATUS_PAID,
                        'paid_at' => now()->subDays(rand(1, 3)),
                        'payment_method' => rand(0, 1) === 0 ? 'card' : 'cash',
                        'reference_number' => 'REF-' . str_pad($invoiceCount + 1, 6, '0', STR_PAD_LEFT),
                    ]);
                    $paidCount++;
                } elseif ($statusRandom === 1 && now()->diffInDays($invoice->due_date) > 0) {
                    // 33% overdue (past due date but unpaid)
                    $invoice->update([
                        'status' => Invoice::STATUS_OVERDUE,
                        'payment_status' => Invoice::PAYMENT_STATUS_UNPAID,
                        'due_date' => now()->subDays(rand(1, 5)), // Past due
                    ]);
                    $overdueCount++;
                } else {
                    // 33% pending/issued
                    $invoice->update([
                        'status' => Invoice::STATUS_ISSUED,
                        'payment_status' => Invoice::PAYMENT_STATUS_UNPAID,
                    ]);
                    $pendingCount++;
                }

                echo "✅ Generated invoice {$invoice->invoice_number} for session ID {$session->id} | Amount: {$totalAmount} BDT\n";
                $invoiceCount++;

            } catch (\Exception $e) {
                echo "❌ Failed to generate invoice for session {$session->id}: {$e->getMessage()}\n";
            }
        }

        echo "\n📊 Phase 5: Billing & Invoicing Summary:\n";
        echo "  ✓ Total invoices generated: $invoiceCount\n";
        echo "  ✓ Paid invoices: $paidCount\n";
        echo "  ✓ Pending invoices: $pendingCount\n";
        echo "  ✓ Overdue invoices: $overdueCount\n";

        // Calculate and display financial summary
        $totalRevenue = Invoice::where('status', Invoice::STATUS_PAID)->sum('total_amount');
        $totalOutstanding = Invoice::whereIn('status', [Invoice::STATUS_ISSUED, Invoice::STATUS_OVERDUE])
            ->sum('total_amount');
        $totalTax = Invoice::sum('tax_amount');

        echo "\n💰 Financial Summary:\n";
        echo "  💵 Total Revenue (Paid): " . number_format($totalRevenue, 2) . " BDT\n";
        echo "  ⏳ Total Outstanding: " . number_format($totalOutstanding, 2) . " BDT\n";
        echo "  🏛️  Total Tax Collected: " . number_format($totalTax, 2) . " BDT\n";
    }
}
