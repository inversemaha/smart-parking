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
        Schema::create('brta_configs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('api_url');
            $table->text('api_key'); // encrypted
            $table->text('api_secret')->nullable(); // encrypted
            $table->json('headers')->nullable();
            $table->json('auth_config')->nullable();
            $table->integer('timeout')->default(30);
            $table->integer('retry_attempts')->default(3);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_sandbox')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brta_configs');
    }
};
