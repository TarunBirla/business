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
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'banner_img')) {
                $table->string('banner_img')->nullable()->after('banner');
            }
            if (!Schema::hasColumn('events', 'event_img')) {
                $table->string('event_img')->nullable()->after('banner_img');
            }
            if (!Schema::hasColumn('events', 'overview')) {
                $table->text('overview')->nullable()->after('description');
            }
            if (!Schema::hasColumn('events', 'agenda')) {
                $table->text('agenda')->nullable()->after('overview');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['banner_img', 'event_img', 'overview', 'agenda']);
        });
    }
};
