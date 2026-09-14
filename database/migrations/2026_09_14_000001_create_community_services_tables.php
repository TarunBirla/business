<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('community_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('category')->default('General');
            $table->string('price_type')->default('quote'); // fixed, hourly, free, quote
            $table->decimal('price', 10, 2)->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
        });

        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('community_services')->onDelete('cascade');
            $table->foreignId('requester_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('provider_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, accepted, rejected
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
        Schema::dropIfExists('community_services');
    }
};
