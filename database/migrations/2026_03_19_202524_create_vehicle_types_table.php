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
        Schema::create('vehicle_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->decimal('width', 8, 2)->nullable()->comment('in meters');
            $table->decimal('height', 8, 2)->nullable()->comment('in meters');
            $table->decimal('length', 8, 2)->nullable()->comment('in meters');
            $table->decimal('rate_multiplier', 5, 2)->default(1.0)->comment('multiplier for parking rate');
            $table->string('icon_url')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_types');
    }
};
