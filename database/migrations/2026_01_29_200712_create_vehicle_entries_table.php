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
        Schema::create('vehicle_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('parking_location_id')->constrained()->onDelete('cascade');
            $table->foreignId('parking_slot_id')->nullable()->constrained()->onDelete('set null');
            $table->string('gate_number')->nullable();
            $table->timestamp('entry_time');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('entry_method'); // manual, automatic, qr_code, etc.
            $table->json('entry_data')->nullable(); // sensor data, photos, etc.
            $table->string('ticket_number')->nullable();
            $table->boolean('is_valid_entry')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['vehicle_id', 'entry_time']);
            $table->index(['parking_location_id', 'entry_time']);
            $table->index(['booking_id', 'entry_time']);
            $table->index('entry_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_entries');
    }
};
