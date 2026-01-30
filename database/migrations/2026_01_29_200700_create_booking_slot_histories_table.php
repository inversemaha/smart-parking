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
        Schema::create('booking_slot_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('parking_slot_id')->constrained()->onDelete('cascade');
            $table->enum('action', ['assigned', 'released', 'changed', 'locked', 'unlocked']);
            $table->foreignId('previous_slot_id')->nullable()->constrained('parking_slots')->onDelete('set null');
            $table->timestamp('action_at');
            $table->foreignId('action_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('reason')->nullable();
            $table->json('metadata')->nullable(); // additional context data
            $table->timestamps();

            $table->index(['booking_id', 'action_at']);
            $table->index(['parking_slot_id', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_slot_histories');
    }
};
