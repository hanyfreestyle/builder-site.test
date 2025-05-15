<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {

        Schema::create('builder_template_block_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('type')->unique(); // نوع البلوك (مثل hero)
            $table->json('name')->nullable(); // الاسم متعدد اللغات
            $table->json('schema'); // شكل البيانات (form structure)
        });

        Schema::create('builder_template', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->json('name')->nullable();
            $table->json('des')->nullable();
            $table->string('photo')->nullable();
            $table->string('photo_thumbnail')->nullable();
            $table->boolean('is_active')->default(true);
        });

        Schema::create('builder_template_layout', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('builder_template')->onDelete('cascade');
            $table->enum('type', ['header', 'footer']);
            $table->string('slug');
            $table->json('name')->nullable();
            $table->string('photo')->nullable();
            $table->string('photo_thumbnail')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('position')->default(0);
            $table->unique(['template_id', 'slug']);
        });

        Schema::create('builder_template_blocks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('template_id')
                ->constrained('builder_template')
                ->onDelete('cascade');

            $table->foreignId('block_definition_id')
                ->constrained('builder_template_block_definitions')
                ->onDelete('cascade');

            $table->string('photo')->nullable(); // صورة مخصصة للبلوك داخل القالب
            $table->string('photo_thumbnail')->nullable(); // صورة مصغرة
            $table->boolean('is_active')->default(true);
            $table->integer('position')->default(0);

        });

        Schema::create('builder_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('builder_template')->onDelete('cascade');

            $table->string('slug')->unique(); // رابط الصفحة
            $table->json('title'); // العنوان بلغات متعددة
            $table->string('locale', 10)->default('ar'); // اللغة الأساسية للصفحة
            $table->boolean('is_active')->default(true);

        });


        Schema::create('builder_blocks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('page_id')
                ->constrained('builder_pages')
                ->onDelete('cascade');

            $table->string('type'); // نفس type الموجود في builder_template_block_definitions

            $table->json('data')->nullable(); // المحتوى الفعلي للبلوك
            $table->integer('position')->default(0);

        });



    }

    public function down(): void {

        Schema::dropIfExists('builder_blocks');
        Schema::dropIfExists('builder_pages');
        Schema::dropIfExists('builder_template_blocks');
        Schema::dropIfExists('builder_template_layout');
        Schema::dropIfExists('builder_template');
        Schema::dropIfExists('builder_template_block_definitions');

    }
};
