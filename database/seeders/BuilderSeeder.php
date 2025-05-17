<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\Builder\BlockTypeSeeder;
use Database\Seeders\Builder\TemplateSeeder;
use Database\Seeders\Builder\DemoContentSeeder;

class BuilderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
//            BlockTypeSeeder::class,
//            TemplateSeeder::class,
//            DemoContentSeeder::class,
        ]);
    }
}
