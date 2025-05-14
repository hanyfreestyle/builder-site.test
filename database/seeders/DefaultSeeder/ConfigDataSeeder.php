<?php

namespace Database\Seeders\DefaultSeeder;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ConfigDataSeeder extends Seeder {
    public function run(): void {

        loadSeederFromClientOrDef('config_setting', 'var_config_setting/');
        loadSeederFromClientOrDef('config_def_photos', 'var_config_def_photos/');
        loadSeederFromClientOrDef('config_meta_tag', 'var_config_meta_tag/');
        loadSeederFromClientOrDef('config_upload_filter', 'var_config_upload_filter/');
        loadSeederFromClientOrDef('config_upload_filter_sizes', 'var_config_upload_filter/');
        loadSeederFromClientOrDef('config_web_privacy', 'var_config_web_privacy/');
//        loadSeederFromClientOrDef('user_guide', 'var_user_guide/');

        if (File::isFile(base_path('database/seeders/Data/DataCountrySeeder.php'))) {
            $this->call(\Database\Seeders\Data\DataCountrySeeder::class);
        }

    }
}
