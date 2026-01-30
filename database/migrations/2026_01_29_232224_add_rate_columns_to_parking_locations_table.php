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
        Schema::table('parking_locations', function (Blueprint $table) {
            $table->decimal('daily_rate', 8, 2)->default(0)->after('hourly_rate');
            $table->decimal('monthly_rate', 8, 2)->default(0)->after('daily_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parking_locations', function (Blueprint $table) {
            $table->dropColumn(['daily_rate', 'monthly_rate']);
        });
    }
};
