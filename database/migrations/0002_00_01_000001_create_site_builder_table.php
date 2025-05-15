<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
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
        });

        Schema::create('builder_template_block', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('builder_template')->onDelete('cascade');
            $table->string('type');
            $table->json('name')->nullable();
            $table->string('photo')->nullable();
            $table->string('photo_thumbnail')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('position')->default(0);
        });

    }

    public function down(): void {
        Schema::dropIfExists('builder_template_block');
        Schema::dropIfExists('builder_template_layout');
        Schema::dropIfExists('builder_template');
    }
};
