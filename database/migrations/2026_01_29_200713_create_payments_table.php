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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('payable_type'); // booking, vehicle_exit, fine, etc.
            $table->unsignedBigInteger('payable_id');
            $table->decimal('amount', 8, 2);
            $table->string('currency', 3)->default('BDT');
            $table->enum('status', ['initiated', 'pending', 'processing', 'paid', 'failed', 'cancelled', 'refunded']);
            $table->string('payment_method')->nullable(); // card, mobile_banking, cash, etc.
            $table->string('gateway'); // sslcommerz, bkash, nagad, etc.
            $table->string('gateway_transaction_id')->nullable();
            $table->json('gateway_response')->nullable();
            $table->timestamp('initiated_at');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['payable_type', 'payable_id']);
            $table->index(['user_id', 'status']);
            $table->index(['status', 'initiated_at']);
            $table->index(['gateway', 'gateway_transaction_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
