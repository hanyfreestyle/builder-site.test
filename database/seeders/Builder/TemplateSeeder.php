<?php

namespace Database\Seeders\Builder;

use App\Models\Builder\Template;
use App\Models\Builder\BlockType;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // 1. Create Restaurant Template
        $restaurantTemplate = Template::create([
            'name' => 'Restaurant',
            'slug' => 'restaurant',
            'description' => 'Perfect for restaurants, cafes, and food-related businesses',
            'thumbnail' => 'templates/restaurant-thumbnail.jpg',
            'settings' => [
                'colors' => [
                    'primary' => '#d4af37',
                    'secondary' => '#222222',
                    'accent' => '#e74c3c',
                    'background' => '#ffffff',
                    'text' => '#333333',
                ],
                'fonts' => [
                    'primary' => 'Roboto, sans-serif',
                    'heading' => 'Playfair Display, serif',
                    'base_size' => '16px',
                ],
                'spacing' => [
                    'base' => '1rem',
                    'section' => '3rem',
                ],
            ],
            'supported_languages' => ['en', 'ar'],
            'is_active' => true,
            'is_default' => true,
        ]);


        // Get all block types
        $blockTypes = BlockType::all();

        // Associate block types with Restaurant template
        foreach ($blockTypes as $blockType) {
            $viewVersions = [];
            $defaultViewVersion = 'default';

            // Define specific view versions for each block type
            switch ($blockType->slug) {
                case 'hero':
                    $viewVersions = ['default', 'centered', 'with-video'];
                    break;
                case 'features':
                    $viewVersions = ['default', 'boxed', 'icon-top'];
                    break;
                case 'content':
                    $viewVersions = ['default', 'boxed', 'full-width'];
                    break;
                case 'gallery':
                    $viewVersions = ['default', 'masonry', 'slider'];
                    break;
                case 'cta':
                    $viewVersions = ['default', 'full-width', 'centered'];
                    break;
                default:
                    $viewVersions = ['default'];
                    break;
            }

            // Associate with Restaurant template
            $restaurantTemplate->blockTypes()->attach($blockType->id, [
                'view_versions' => json_encode($viewVersions),
                'default_view_version' => $defaultViewVersion,
                'is_enabled' => true,
                'sort_order' => $blockType->sort_order,
            ]);
        }
    }
}
