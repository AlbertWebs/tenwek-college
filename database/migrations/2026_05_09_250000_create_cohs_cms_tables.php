<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cohs_landing_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('section_key', 96);
            $table->json('payload')->nullable();
            $table->timestamps();
            $table->unique(['school_id', 'section_key']);
        });

        Schema::create('cohs_testimonials', function (Blueprint $table) {
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

        Schema::create('cohs_nav_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('cohs_nav_items')->cascadeOnDelete();
            $table->string('mega_id', 64)->nullable();
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
    }

    public function down(): void
    {
        Schema::dropIfExists('cohs_nav_items');
        Schema::dropIfExists('cohs_testimonials');
        Schema::dropIfExists('cohs_landing_sections');
    }
};
