<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('config_meta_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cat_id')->unique()->index();
            $table->string('photo')->nullable();
            $table->string('photo_thumbnail')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('config_meta_tag_lang', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meta_tag_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name')->nullable();
            $table->text('des')->nullable();
            $table->string('g_title')->nullable();
            $table->text('g_des')->nullable();
            $table->unique(['meta_tag_id', 'locale']);
            $table->foreign('meta_tag_id')->references('id')->on('config_meta_tag')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('config_meta_tag_lang');
        Schema::dropIfExists('config_meta_tag');
    }
};
