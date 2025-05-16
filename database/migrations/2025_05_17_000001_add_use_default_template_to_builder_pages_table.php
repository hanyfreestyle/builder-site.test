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
        Schema::table('builder_pages', function (Blueprint $table) {
            $table->boolean('use_default_template')->default(false)
                ->after('template_id')
                ->comment('Flag to determine if the page should use the default site template');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('builder_pages', function (Blueprint $table) {
            $table->dropColumn('use_default_template');
        });
    }
};