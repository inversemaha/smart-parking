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
        Schema::create('parking_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parking_location_id')->constrained()->onDelete('cascade');
            $table->string('slot_number');
            $table->string('floor')->nullable();
            $table->string('section')->nullable();
            $table->enum('slot_type', ['regular', 'vip', 'disabled', 'electric']);
            $table->json('vehicle_types'); // ["car", "motorcycle"]
            $table->enum('status', ['available', 'occupied', 'reserved', 'maintenance', 'blocked']);
            $table->decimal('length_meters', 5, 2)->nullable();
            $table->decimal('width_meters', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_occupied_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['parking_location_id', 'slot_number']);
            $table->index(['parking_location_id', 'status', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_slots');
    }
};
