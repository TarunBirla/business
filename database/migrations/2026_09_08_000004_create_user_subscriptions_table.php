<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained('group_subscriptions')->onDelete('set null');
            $table->string('payment_id')->nullable();
            $table->timestamp('start_date')->useCurrent();
            $table->timestamp('expiry_date')->nullable();
            $table->string('status')->default('active'); // active, expiring_soon, expired, cancelled, suspended
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};
