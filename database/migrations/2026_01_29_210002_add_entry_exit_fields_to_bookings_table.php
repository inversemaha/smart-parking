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
        Schema::table('bookings', function (Blueprint $table) {
            $table->timestamp('entry_time')->nullable()->after('end_time');
            $table->timestamp('exit_time')->nullable()->after('entry_time');
            $table->integer('parking_duration_minutes')->nullable()->after('exit_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['entry_time', 'exit_time', 'parking_duration_minutes']);
        });
    }
};
