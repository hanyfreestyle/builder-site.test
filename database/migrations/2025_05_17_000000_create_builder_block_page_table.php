<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('builder_block_page', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_id')->constrained('builder_blocks')->onDelete('cascade');
            $table->foreignId('page_id')->constrained('builder_pages')->onDelete('cascade');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Add a unique constraint to prevent duplicate relationships
            $table->unique(['block_id', 'page_id']);
        });

        // Copy existing blocks' page_id to the new pivot table
        $this->migrateExistingBlocks();

        // Optionally remove page_id from blocks table
        // Schema::table('builder_blocks', function (Blueprint $table) {
        //     $table->dropForeign(['page_id']);
        //     $table->dropColumn('page_id');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('builder_block_page');
    }

    /**
     * Migrate existing blocks to the new pivot table.
     */
    private function migrateExistingBlocks(): void
    {
        $blocks = DB::table('builder_blocks')
            ->whereNotNull('page_id')
            ->whereNull('deleted_at')
            ->get();

        foreach ($blocks as $block) {
            DB::table('builder_block_page')->insert([
                'block_id' => $block->id,
                'page_id' => $block->page_id,
                'sort_order' => $block->sort_order,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};
