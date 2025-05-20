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
        // 1. جدول القوالب (builder_templates)
        Schema::create('builder_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->json('settings')->nullable();
            $table->json('supported_languages')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. جدول أنواع البلوكات (builder_block_types)
        Schema::create('builder_block_types', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('category')->nullable();
            $table->json('schema');

            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. جدول ربط القوالب بأنواع البلوكات (builder_template_block_types)
        Schema::create('builder_template_block_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('builder_templates')->onDelete('cascade');
            $table->foreignId('block_type_id')->constrained('builder_block_types')->onDelete('cascade');
            $table->json('view_versions'); // ['default', 'centered', 'with-video', etc.]
            $table->string('default_view_version')->default('default');
            $table->boolean('is_enabled')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // معرف فريد للربط بين القالب ونوع البلوك
            $table->unique(['template_id', 'block_type_id']);
        });

        // 4. جدول الصفحات (builder_pages)
        Schema::create('builder_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('builder_templates')->onDelete('cascade');
            $table->boolean('use_default_template')->default(false);
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->json('meta_tags')->nullable();
            $table->json('translations')->nullable();
            $table->boolean('is_homepage')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // 5. جدول البلوكات (builder_blocks)
        Schema::create('builder_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_type_id')->constrained('builder_block_types')->onDelete('cascade');
            $table->json('data');
            $table->json('translations')->nullable();
            $table->string('view_version')->default('default');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 6. جدول القوائم (builder_menus)
        Schema::create('builder_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('builder_templates')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('location')->default('header'); // 'header', 'footer', 'sidebar', etc.
            $table->json('translations')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 7. جدول عناصر القائمة (builder_menu_items)
        Schema::create('builder_menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('builder_menus')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('builder_menu_items')->onDelete('cascade');
            $table->string('title');
            $table->string('type')->default('url'); // 'url', 'page', 'route', 'section'
            $table->string('url')->nullable();
            $table->foreignId('page_id')->nullable()->constrained('builder_pages')->onDelete('set null');
            $table->string('route')->nullable();
            $table->string('icon')->nullable();
            $table->json('translations')->nullable();
            $table->boolean('target_blank')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('builder_block_page', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_id')->constrained('builder_blocks')->onDelete('cascade');
            $table->foreignId('page_id')->constrained('builder_pages')->onDelete('cascade');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Add a unique constraint to prevent duplicate relationships
            $table->unique(['block_id', 'page_id']);
        });

    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('builder_block_page');
        Schema::dropIfExists('builder_menu_items');
        Schema::dropIfExists('builder_menus');
        Schema::dropIfExists('builder_blocks');
        Schema::dropIfExists('builder_pages');
        Schema::dropIfExists('builder_template_block_types');
        Schema::dropIfExists('builder_block_types');
        Schema::dropIfExists('builder_templates');
    }
};
