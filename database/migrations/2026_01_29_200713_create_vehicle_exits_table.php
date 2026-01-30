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
        Schema::create('vehicle_exits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_entry_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('parking_location_id')->constrained()->onDelete('cascade');
            $table->string('gate_number')->nullable();
            $table->timestamp('exit_time');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('exit_method'); // manual, automatic, qr_code, etc.
            $table->integer('duration_minutes');
            $table->decimal('calculated_fee', 8, 2)->default(0);
            $table->decimal('paid_amount', 8, 2)->default(0);
            $table->enum('payment_status', ['pending', 'paid', 'waived', 'disputed']);
            $table->json('exit_data')->nullable(); // sensor data, photos, etc.
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['vehicle_id', 'exit_time']);
            $table->index(['parking_location_id', 'exit_time']);
            $table->index(['booking_id', 'exit_time']);
            $table->index(['payment_status', 'exit_time']);
            $table->index('exit_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_exits');
    }
};
