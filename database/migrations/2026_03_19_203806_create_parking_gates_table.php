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
        Schema::create('parking_gates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained('parking_zones')->cascadeOnDelete();
            $table->foreignId('floor_id')->nullable()->constrained('parking_floors')->cascadeOnDelete();
            $table->string('name')->unique();  // e.g., 'Zone A Entrance', 'Zone B Exit'
            $table->text('description')->nullable();
            $table->enum('gate_type', ['entry', 'exit', 'bidirectional'])->default('bidirectional');
            $table->enum('gate_status', ['operational', 'maintenance', 'closed'])->default('operational');
            $table->string('location')->nullable();  // Physical location of gate
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('camera_url')->nullable();  // IP camera stream URL
            $table->json('device_settings')->nullable();  // Barrier, sensor settings
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index('zone_id');
            $table->index('floor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_gates');
    }
};
