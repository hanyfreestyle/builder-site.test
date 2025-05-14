<?php

namespace App\Traits\Admin\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait DataMigrationsTraits {

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function CatagoryTable($name,$foreign,$funType) {
        if ($funType == 'up') {
            Schema::create("$name", function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->uuid()->nullable()->unique();
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->integer('deep')->default(0);
                $table->string("photo")->nullable();
                $table->string("photo_thumbnail")->nullable();
                $table->string("icon")->nullable();
                $table->boolean("is_active")->default(true);
                $table->integer('position')->default(0);
                $table->timestamps();
                $table->softDeletes();
            });


            Schema::create("$name".'_lang', function (Blueprint $table) use ($name,$foreign) {
                $table->bigIncrements('id');
                $table->bigInteger($foreign)->unsigned();
                $table->string('locale')->index();
                $table->string('slug')->nullable();
                $table->string('name')->nullable();
                $table->longText('des')->nullable();
                $table->string('g_title')->nullable();
                $table->text('g_des')->nullable();
                $table->unique([$foreign, 'locale']);
                $table->unique(['locale', 'slug']);
                $table->foreign($foreign)->references('id')->on($name)->onDelete('cascade');
            });


        } elseif ($funType == 'down') {
            Schema::dropIfExists("$name".'_lang');
            Schema::dropIfExists("$name");
        }
    }

}
