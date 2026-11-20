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
        // 1. Add last_login_at, last_logout_at, last_seen_at columns to users table safely
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('users', 'last_logout_at')) {
                $table->timestamp('last_logout_at')->nullable()->after('last_login_at');
            }
            if (!Schema::hasColumn('users', 'last_seen_at')) {
                $table->timestamp('last_seen_at')->nullable()->after('last_logout_at');
            }
        });

        // 2. Create user_activities table for detailed login, logout, and page views audit trail
        if (!Schema::hasTable('user_activities')) {
            Schema::create('user_activities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('group_id')->nullable()->constrained('groups')->nullOnDelete();
                $table->string('activity_type', 50)->default('page_view'); // login, logout, page_view, connection, survey, etc.
                $table->string('description', 500);
                $table->string('path', 500)->nullable();
                $table->text('url')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'created_at']);
                $table->index(['group_id', 'created_at']);
                $table->index('activity_type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activities');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'last_seen_at')) {
                $table->dropColumn('last_seen_at');
            }
            if (Schema::hasColumn('users', 'last_logout_at')) {
                $table->dropColumn('last_logout_at');
            }
            if (Schema::hasColumn('users', 'last_login_at')) {
                $table->dropColumn('last_login_at');
            }
        });
    }
};
