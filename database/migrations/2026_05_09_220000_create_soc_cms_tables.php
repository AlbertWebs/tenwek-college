<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soc_landing_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('section_key', 96);
            $table->json('payload')->nullable();
            $table->timestamps();
            $table->unique(['school_id', 'section_key']);
        });

        Schema::create('soc_testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('designation')->nullable();
            $table->string('organization')->nullable();
            $table->text('quote');
            $table->string('image_path')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('soc_nav_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('soc_nav_items')->cascadeOnDelete();
            $table->string('label');
            $table->string('page_slug')->nullable();
            $table->string('route_name')->nullable();
            $table->string('external_url')->nullable();
            $table->boolean('open_new_tab')->default(false);
            $table->boolean('is_highlight')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('soc_team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('team', 24);
            $table->string('name');
            $table->string('role_title');
            $table->text('bio')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('highlight')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('media_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->string('disk', 32)->default('public');
            $table->string('path', 512);
            $table->string('original_filename');
            $table->string('mime_type', 128)->nullable();
            $table->unsignedInteger('size_bytes')->nullable();
            $table->string('alt_text')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_assets');
        Schema::dropIfExists('soc_team_members');
        Schema::dropIfExists('soc_nav_items');
        Schema::dropIfExists('soc_testimonials');
        Schema::dropIfExists('soc_landing_sections');
    }
};
