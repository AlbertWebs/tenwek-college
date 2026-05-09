<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('download_categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug', 192)->unique();
            $table->text('description')->nullable();
            $table->string('file_path', 512)->nullable();
            $table->string('original_filename', 255)->nullable();
            $table->string('mime', 128)->nullable();
            $table->unsignedBigInteger('size_bytes')->default(0);
            $table->string('extension', 16)->nullable();
            $table->unsignedBigInteger('download_count')->default(0);
            $table->string('preview_image_path', 512)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->string('seo_title', 192)->nullable();
            $table->string('seo_description', 512)->nullable();
            $table->timestamps();

            $table->index(['school_id', 'category_id', 'is_active']);
        });

        Schema::create('download_related', function (Blueprint $table) {
            $table->foreignId('download_id')->constrained('downloads')->cascadeOnDelete();
            $table->foreignId('related_download_id')->constrained('downloads')->cascadeOnDelete();
            $table->primary(['download_id', 'related_download_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('download_related');
        Schema::dropIfExists('downloads');
    }
};
