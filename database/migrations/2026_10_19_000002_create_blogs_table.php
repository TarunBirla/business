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
        if (!Schema::hasTable('blogs')) {
            Schema::create('blogs', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('excerpt')->nullable();
                $table->longText('content');
                $table->string('image')->nullable();
                $table->string('author_name')->default('Bizconn Editorial');
                $table->unsignedBigInteger('author_id')->nullable();
                $table->string('category')->default('Business Networking');
                $table->string('status')->default('active'); // active, inactive
                $table->unsignedBigInteger('views_count')->default(0);
                $table->string('reading_time')->default('3 min read');
                // SEO & ARO (Audience Optimization) Fields
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->text('meta_keywords')->nullable();
                // GEO Targeting Fields
                $table->string('geo_region')->default('GB-UKM')->nullable(); // e.g. GB-UKM, United Kingdom
                $table->string('geo_placename')->default('United Kingdom')->nullable(); // e.g. London, UK
                $table->timestamp('published_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
