<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug', 160);
            $table->string('excerpt', 512)->nullable();
            $table->longText('body')->nullable();
            $table->string('template', 64)->default('default');
            $table->timestamp('published_at')->nullable();
            $table->string('seo_title', 192)->nullable();
            $table->string('seo_description', 512)->nullable();
            $table->string('canonical_path', 512)->nullable();
            $table->string('og_image_path', 512)->nullable();
            $table->string('robots', 64)->nullable();
            $table->timestamps();

            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
