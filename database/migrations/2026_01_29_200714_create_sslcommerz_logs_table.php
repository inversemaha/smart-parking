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
        Schema::create('sslcommerz_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->index();
            $table->enum('action', ['initiate', 'validation', 'ipn', 'refund', 'cancel']);
            $table->enum('status', ['pending', 'success', 'failed', 'cancelled']);
            $table->json('request_data');
            $table->json('response_data')->nullable();
            $table->integer('response_code')->nullable();
            $table->text('error_message')->nullable();
            $table->decimal('response_time_ms', 8, 2)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('processed_at');
            $table->timestamps();

            $table->index(['transaction_id', 'action']);
            $table->index(['payment_id', 'action']);
            $table->index(['status', 'processed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sslcommerz_logs');
    }
};
