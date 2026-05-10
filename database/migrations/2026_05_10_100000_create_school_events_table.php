<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug', 192);
            $table->string('excerpt', 2000)->nullable();
            $table->longText('body')->nullable();
            $table->string('image_path', 512)->nullable();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->string('location', 500)->nullable();
            $table->string('registration_url', 512)->nullable();
            $table->timestamp('published_at')->nullable();
            $table->string('seo_title', 192)->nullable();
            $table->string('seo_description', 512)->nullable();
            $table->timestamps();

            $table->unique(['school_id', 'slug']);
            $table->index(['school_id', 'published_at']);
            $table->index(['school_id', 'starts_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_events');
    }
};
