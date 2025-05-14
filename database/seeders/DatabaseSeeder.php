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

    }
}
