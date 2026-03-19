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
        Schema::create('parking_floors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained('parking_zones')->cascadeOnDelete();
            $table->integer('floor_number');
            $table->string('floor_name');
            $table->text('description')->nullable();
            $table->integer('total_capacity')->default(0);
            $table->integer('current_occupancy')->default(0);
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->decimal('daily_rate', 8, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            // Composite unique constraint: each zone can have only one floor with a given floor_number
            $table->unique(['zone_id', 'floor_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_floors');
    }
};
