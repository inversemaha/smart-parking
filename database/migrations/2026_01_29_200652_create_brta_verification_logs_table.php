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
        Schema::create('brta_verification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('brta_config_id')->constrained()->onDelete('cascade');
            $table->string('registration_number');
            $table->enum('status', ['pending', 'success', 'failed', 'timeout', 'error']);
            $table->json('request_data');
            $table->json('response_data')->nullable();
            $table->integer('response_code')->nullable();
            $table->text('error_message')->nullable();
            $table->integer('attempt_number')->default(1);
            $table->decimal('response_time_ms', 8, 2)->nullable();
            $table->timestamp('requested_at');
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->index(['vehicle_id', 'status']);
            $table->index(['registration_number', 'status']);
            $table->index('requested_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brta_verification_logs');
    }
};
