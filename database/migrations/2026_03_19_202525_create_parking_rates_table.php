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
        Schema::create('parking_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained('parking_zones')->cascadeOnDelete();
            $table->foreignId('vehicle_type_id')->constrained('vehicle_types')->cascadeOnDelete();
            $table->decimal('hourly_rate', 10, 2);
            $table->decimal('daily_rate', 10, 2);
            $table->decimal('peak_hour_rate', 10, 2)->nullable();
            $table->decimal('off_peak_rate', 10, 2)->nullable();
            $table->time('peak_hours_start')->nullable();
            $table->time('peak_hours_end')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['zone_id', 'vehicle_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_rates');
    }
};
