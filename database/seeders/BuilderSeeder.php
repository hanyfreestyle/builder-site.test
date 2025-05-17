<?php

namespace Database\Seeders;

use App\Models\Builder\Page;
use App\Models\Builder\Template;
use Illuminate\Database\Seeder;
use Database\Seeders\Builder\BlockTypeSeeder;
use Database\Seeders\Builder\TemplateSeeder;
use Database\Seeders\Builder\DemoContentSeeder;

class BuilderSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $this->call([
            BlockTypeSeeder::class,
            TemplateSeeder::class,
//             DemoContentSeeder::class,
        ]);

        // Get the Restaurant template
        $restaurantTemplate = Template::where('slug', 'restaurant')->first();

        if (!$restaurantTemplate) {
            return;
        }

        // Create homepage
        $homepage = Page::create([
            'template_id' => $restaurantTemplate->id,
            'title' => 'Welcome to Our Restaurant',
            'slug' => 'home',
            'description' => 'Our delicious food and cozy atmosphere will make your dining experience unforgettable.',
            'meta_tags' => [
                'title' => 'Best Restaurant in Town | Fine Dining Experience',
                'description' => 'Experience the finest cuisine in town with our award-winning chefs and cozy atmosphere.',
                'keywords' => 'restaurant, fine dining, cuisine, food, dinner',
                'robots' => 'index, follow',
            ],
            'is_homepage' => true,
            'is_active' => true,
            'sort_order' => 0,
        ]);

    }
}
