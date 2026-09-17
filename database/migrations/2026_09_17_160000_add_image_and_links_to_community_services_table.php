<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('community_services', function (Blueprint $table) {
            $table->string('image')->nullable()->after('category');
            $table->string('website_url')->nullable()->after('image');
            $table->string('video_url')->nullable()->after('website_url');
        });
    }

    public function down(): void
    {
        Schema::table('community_services', function (Blueprint $table) {
            $table->dropColumn(['image', 'website_url', 'video_url']);
        });
    }
};
