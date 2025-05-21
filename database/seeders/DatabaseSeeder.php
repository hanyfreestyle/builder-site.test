<?php

namespace Database\Seeders;

use Database\Seeders\DefaultSeeder\ConfigDataSeeder;
use Database\Seeders\DefaultSeeder\UserSeeder;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call(UserSeeder::class);
        $this->call(ConfigDataSeeder::class);
        loadSeederFromFileWithLang('user_guide', true);
        loadSeederFromFileWithLang('user_guide_photo', true);
        loadSeederFromFile('builder_templates', true);
        loadSeederFromFile('builder_block_types', true);
        loadSeederFromFile('builder_template_block_types', true);
//        loadSeederFromFile('builder_pages', true);
//        loadSeederFromFile('builder_blocks', true);
//        loadSeederFromFile('builder_menus', true);
//        loadSeederFromFile('builder_menu_items', true);
//        loadSeederFromFile('builder_block_page', true);

        // Run Site Builder seeders
//        $this->call(BuilderSeeder::class);
    }
}
