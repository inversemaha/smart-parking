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
        Schema::create('parking_qr_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained('parking_zones')->cascadeOnDelete();
            $table->foreignId('floor_id')->nullable()->constrained('parking_floors')->cascadeOnDelete();
            $table->string('code')->unique();  // Unique QR code identifier
            $table->longText('qr_data');  // Base64 encoded QR code image
            $table->enum('type', ['zone', 'floor', 'slot', 'instructions'])->default('zone');  // What the QR links to
            $table->text('redirect_url')->nullable();  // URL to navigate to when scanned
            $table->json('metadata')->nullable();  // Additional data (slot details, instructions)
            $table->integer('scan_count')->default(0);  // Track scan usage
            $table->timestamp('first_scanned_at')->nullable();
            $table->timestamp('last_scanned_at')->nullable();
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
        Schema::dropIfExists('parking_qr_codes');
    }
};
