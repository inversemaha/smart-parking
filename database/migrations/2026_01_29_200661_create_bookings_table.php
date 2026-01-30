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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('parking_location_id')->constrained()->onDelete('cascade');
            $table->foreignId('parking_slot_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->integer('duration_hours');
            $table->decimal('hourly_rate', 8, 2);
            $table->decimal('total_amount', 8, 2);
            $table->enum('status', ['pending', 'confirmed', 'active', 'completed', 'cancelled', 'expired', 'no_show']);
            $table->enum('payment_status', ['pending', 'paid', 'refunded', 'failed']);
            $table->text('notes')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('cancellation_reason')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['vehicle_id', 'status']);
            $table->index(['parking_location_id', 'status']);
            $table->index(['start_time', 'end_time']);
            $table->index('booking_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
