<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parking_session_id')->nullable()->constrained('parking_sessions')->onDelete('cascade');
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('cascade');
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('set null');
            
            // Amounts
            $table->decimal('amount', 10, 2)->comment('Base amount');
            $table->decimal('tax_amount', 10, 2)->default(0)->comment('Tax/VAT');
            $table->decimal('discount_amount', 10, 2)->default(0)->comment('Discount applied');
            $table->decimal('total_amount', 10, 2)->comment('Total payable');
            
            // Status and dates
            $table->enum('status', [
                'draft',
                'issued',
                'partially_paid',
                'paid',
                'overdue',
                'cancelled',
            ])->default('draft')->comment('Invoice status');
            
            $table->enum('payment_status', [
                'unpaid',
                'partial',
                'paid',
            ])->default('unpaid')->comment('Payment status');
            
            $table->string('currency', 3)->default('BDT');
            $table->timestamp('issued_at')->nullable()->comment('Invoice issued date');
            $table->timestamp('due_date')->nullable()->comment('Payment due date');
            $table->timestamp('paid_at')->nullable()->comment('Payment received date');
            
            // Payment details
            $table->string('payment_method')->nullable()->comment('Payment method used (cash, card, etc)');
            $table->string('reference_number')->nullable()->comment('Payment reference/receipt number');
            
            // Additional fields
            $table->text('description')->nullable()->comment('Invoice description/notes');
            $table->text('notes')->nullable()->comment('Internal notes');
            $table->json('metadata')->nullable()->comment('Additional data');
            
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['invoice_number']);
            $table->index(['user_id']);
            $table->index(['parking_session_id']);
            $table->index(['booking_id']);
            $table->index(['status']);
            $table->index(['payment_status']);
            $table->index(['issued_at']);
            $table->index(['due_date']);
            $table->index(['user_id', 'status']);
            $table->index(['issued_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
