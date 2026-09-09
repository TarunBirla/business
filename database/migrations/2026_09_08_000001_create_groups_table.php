<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->text('description')->nullable();
            $table->text('purpose')->nullable();
            $table->text('why_join')->nullable();
            $table->json('who_can_join')->nullable();
            $table->json('benefits')->nullable();
            $table->text('rules')->nullable();
            $table->string('community_type')->default('free'); // free, paid
            $table->string('country')->default('United Kingdom');
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('status')->default('active'); // draft, active, suspended, archived
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
