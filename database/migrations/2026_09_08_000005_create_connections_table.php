<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, accepted, rejected, cancelled
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();

            $table->unique(['sender_id', 'receiver_id', 'group_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('connections');
    }
};
