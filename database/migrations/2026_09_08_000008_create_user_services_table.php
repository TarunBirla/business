<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->string('type')->default('offer'); // offer, need
            $table->timestamps();

            $table->unique(['user_id', 'service_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_services');
    }
};
