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
        Schema::create('parking_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->text('address');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('total_capacity');
            $table->decimal('hourly_rate', 8, 2)->default(0);
            $table->json('operating_hours'); // {"monday": {"open": "08:00", "close": "22:00"}, ...}
            $table->json('vehicle_types')->nullable(); // ["car", "motorcycle", "truck"]
            $table->json('amenities')->nullable(); // ["security", "cctv", "lighting"]
            $table->boolean('is_active')->default(true);
            $table->timestamp('opened_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['is_active', 'opened_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_locations');
    }
};
