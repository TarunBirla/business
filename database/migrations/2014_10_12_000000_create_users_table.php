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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('profile_photo')->nullable();
            $table->string('profession')->nullable();
            $table->string('company')->nullable();
            $table->string('job_title')->nullable();
            $table->string('country')->default('United Kingdom');
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->string('linkedin')->nullable();
            $table->json('privacy_settings')->nullable(); // {show_email: false, show_phone: false, allow_connections: true, allow_contact_requests: true}
            $table->string('global_role')->default('user'); // super_admin, user
            $table->string('status')->default('active'); // active, suspended
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
