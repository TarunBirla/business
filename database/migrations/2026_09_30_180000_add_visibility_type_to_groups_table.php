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
        if (Schema::hasTable('groups') && !Schema::hasColumn('groups', 'visibility_type')) {
            Schema::table('groups', function (Blueprint $table) {
                $table->string('visibility_type')->default('public')->after('community_type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('groups') && Schema::hasColumn('groups', 'visibility_type')) {
            Schema::table('groups', function (Blueprint $table) {
                $table->dropColumn('visibility_type');
            });
        }
    }
};
