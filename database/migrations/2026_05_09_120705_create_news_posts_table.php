<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug', 192)->unique();
            $table->string('excerpt', 512)->nullable();
            $table->longText('body')->nullable();
            $table->string('featured_image_path', 512)->nullable();
            $table->timestamp('published_at')->nullable();
            $table->string('seo_title', 192)->nullable();
            $table->string('seo_description', 512)->nullable();
            $table->timestamps();

            $table->index(['published_at', 'school_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_posts');
    }
};
