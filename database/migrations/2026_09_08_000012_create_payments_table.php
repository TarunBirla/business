<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('group_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('subscription_id')->nullable()->constrained('group_subscriptions')->onDelete('set null');
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('GBP');
            $table->string('provider')->default('stripe'); // stripe, manual, test
            $table->string('transaction_id')->unique();
            $table->string('status')->default('completed'); // pending, completed, failed, refunded
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
