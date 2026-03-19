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
        Schema::create('parking_sessions', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('zone_id')->constrained('parking_zones')->cascadeOnDelete();
            $table->foreignId('floor_id')->nullable()->constrained('parking_floors')->cascadeOnDelete();
            $table->foreignId('entry_gate_id')->nullable()->constrained('parking_gates')->nullOnDelete();
            $table->foreignId('exit_gate_id')->nullable()->constrained('parking_gates')->nullOnDelete();
            $table->foreignId('qr_code_id')->nullable()->constrained('parking_qr_codes')->nullOnDelete();
            $table->foreignId('parking_rate_id')->nullable()->constrained('parking_rates')->nullOnDelete();
            
            // Session Details
            $table->string('license_plate', 50)->index();
            $table->string('session_status')->default('active'); // active, completed, cancelled, extended
            $table->text('vehicle_category')->nullable(); // Stored vehicle type name for historical accuracy
            
            // Time Tracking
            $table->dateTime('entry_time')->index();
            $table->dateTime('exit_time')->nullable();
            $table->integer('duration_minutes')->nullable(); // Calculated duration in minutes
            $table->integer('extension_count')->default(0)->comment('Number of times session was extended');
            
            // Pricing & Charges
            $table->decimal('base_rate_per_hour', 10, 2)->nullable(); // Base hourly rate at time of entry
            $table->decimal('total_hours', 8, 2)->nullable(); // Decimal hours for pricing
            $table->decimal('peak_hours', 8, 2)->default(0); // Hours during peak pricing
            $table->decimal('vehicle_multiplier', 5, 2)->default(1.0); // Vehicle type rate multiplier
            $table->decimal('base_charge', 10, 2)->nullable(); // Before adjustments
            $table->decimal('peak_charge', 10, 2)->default(0); // Additional charge for peak hours
            $table->decimal('discount_amount', 10, 2)->default(0); // Discounts applied
            $table->decimal('total_charge', 10, 2)->nullable(); // Final amount due
            $table->string('charging_status')->default('pending'); // pending, calculated, collected, refunded
            
            // Payment Tracking
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->text('payment_notes')->nullable();
            
            // Session Metadata
            $table->json('entry_metadata')->nullable()->comment('Entry sensor/gate data');
            $table->json('exit_metadata')->nullable()->comment('Exit sensor/gate data');
            $table->text('notes')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->string('cancellation_by')->nullable(); // admin, system, user
            
            // Flags & Tracking
            $table->boolean('is_extended')->default(false);
            $table->boolean('is_grace_period_applied')->default(false);
            $table->boolean('is_overstayed')->default(false);
            $table->boolean('is_billing_alert_sent')->default(false);
            
            // Indices
            $table->index('vehicle_id');
            $table->index('zone_id');
            $table->index(['entry_time', 'exit_time']);
            $table->index('charging_status');
            $table->index('session_status');
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_sessions');
    }
};
