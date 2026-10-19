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
        if (!Schema::hasTable('analytics_visitors')) {
            Schema::create('analytics_visitors', function (Blueprint $table) {
                $table->id();
                $table->string('ip_address', 45)->index();
                $table->string('session_id', 100)->nullable()->index();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->text('url')->nullable();
                $table->string('path', 255)->index();
                $table->string('method', 10)->default('GET');
                $table->text('referrer')->nullable();
                $table->text('user_agent')->nullable();
                $table->string('browser', 100)->nullable();
                $table->string('device', 50)->default('desktop');
                $table->string('os', 100)->nullable();
                $table->string('country', 100)->nullable()->index();
                $table->string('country_code', 10)->nullable();
                $table->string('city', 100)->nullable();
                $table->string('region', 100)->nullable();
                $table->boolean('is_unique_daily')->default(false)->index();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_visitors');
    }
};
