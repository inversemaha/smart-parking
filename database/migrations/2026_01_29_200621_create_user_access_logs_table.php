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
        Schema::create('user_access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action'); // login, logout, failed_login, password_change, etc.
            $table->string('ip_address', 45);
            $table->text('user_agent');
            $table->string('method'); // GET, POST, PUT, DELETE
            $table->string('url');
            $table->json('request_data')->nullable();
            $table->string('session_id')->nullable();
            $table->string('device_id')->nullable();
            $table->enum('status', ['success', 'failed', 'blocked']);
            $table->text('message')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'action']);
            $table->index(['ip_address', 'created_at']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_access_logs');
    }
};
