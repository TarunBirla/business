<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('banner')->nullable();
            $table->string('event_type')->default('free'); // free, paid
            $table->string('venue')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('United Kingdom');
            $table->string('meeting_url')->nullable();
            $table->dateTime('start_at');
            $table->dateTime('end_at')->nullable();
            $table->integer('capacity')->default(100);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('currency')->default('GBP');
            $table->dateTime('registration_deadline')->nullable();
            $table->string('status')->default('published'); // draft, published, cancelled, completed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
