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
        Schema::create('vehicle_manual_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('verified_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['approved', 'rejected']);
            $table->text('reason');
            $table->json('documents')->nullable(); // uploaded document references
            $table->text('admin_notes')->nullable();
            $table->timestamp('verified_at');
            $table->timestamps();

            $table->index(['vehicle_id', 'status']);
            $table->index(['verified_by', 'verified_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_manual_verifications');
    }
};
