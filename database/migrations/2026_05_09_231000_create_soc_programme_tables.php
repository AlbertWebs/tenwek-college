<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soc_programme_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('heading');
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('soc_programme_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('soc_programme_group_id')->constrained('soc_programme_groups')->cascadeOnDelete();
            $table->string('slug', 160);
            $table->string('title');
            $table->string('badge')->nullable();
            $table->text('summary');
            $table->longText('body')->nullable();
            $table->string('seo_title', 192)->nullable();
            $table->string('seo_description', 512)->nullable();
            $table->string('seo_keywords', 512)->nullable();
            $table->string('og_title', 192)->nullable();
            $table->string('og_image_path', 512)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->unique(['school_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soc_programme_items');
        Schema::dropIfExists('soc_programme_groups');
    }
};
