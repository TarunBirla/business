<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cms_pages', function (Blueprint $table) {
            $table->foreignId('group_id')->nullable()->after('id')->constrained('groups')->onDelete('cascade');
            $table->string('page_type')->default('other')->after('slug'); // terms, privacy, other
        });
    }

    public function down(): void
    {
        Schema::table('cms_pages', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn(['group_id', 'page_type']);
        });
    }
};
