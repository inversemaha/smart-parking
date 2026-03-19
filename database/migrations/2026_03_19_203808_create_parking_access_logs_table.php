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
        Schema::create('parking_access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('gate_id')->constrained('parking_gates')->cascadeOnDelete();
            $table->foreignId('zone_id')->constrained('parking_zones')->cascadeOnDelete();
            $table->string('license_plate')->nullable();  // Captured or provided
            $table->enum('access_type', ['entry', 'exit'])->default('entry');
            $table->enum('access_status', ['allowed', 'denied', 'pending', 'alert'])->default('pending');
            $table->string('access_method')->nullable();  // 'qr_code', 'rfid', 'mobile', 'manual'
            $table->text('denial_reason')->nullable();  // Why access was denied
            $table->text('notes')->nullable();  // Staff notes
            $table->string('staff_member')->nullable();  // Who manually approved/denied
            $table->string('vehicle_type')->nullable();  // Detected or provided vehicle type
            $table->json('sensor_data')->nullable();  // Loop detector, image recognition data
            $table->timestamp('accessed_at');  // When gate was triggered
            $table->timestamps();
            $table->index('vehicle_id');
            $table->index('gate_id');
            $table->index('zone_id');
            $table->index('accessed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_access_logs');
    }
};
