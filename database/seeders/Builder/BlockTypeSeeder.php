<?php

namespace Database\Seeders\Builder;

use App\Models\Builder\BlockType;
use Illuminate\Database\Seeder;

class BlockTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Hero Block
        BlockType::create([
            'name' => 'Hero Section',
            'slug' => 'hero',
            'description' => 'Hero section with title, subtitle, buttons and optional image/video',
            'icon' => 'fas fa-heading',
            'category' => 'Basic',
            'schema' => [
                [
                    'name' => 'title',
                    'label' => 'Title',
                    'type' => 'text',
                    'required' => true,
                    'placeholder' => 'Enter title',
                    'translatable' => true,
                    'width' => 'full',
                ],
                [
                    'name' => 'subtitle',
                    'label' => 'Subtitle',
                    'type' => 'textarea',
                    'required' => false,
                    'placeholder' => 'Enter subtitle',
                    'translatable' => true,
                    'width' => 'full',
                ],
                [
                    'name' => 'link1',
                    'label' => 'Primary Button',
                    'type' => 'link',
                    'required' => false,
                    'translatable' => true,
                    'width' => '1/2',
                ],
                [
                    'name' => 'link2',
                    'label' => 'Secondary Button',
                    'type' => 'link',
                    'required' => false,
                    'translatable' => true,
                    'width' => '1/2',
                ],
                [
                    'name' => 'photo',
                    'label' => 'Hero Image',
                    'type' => 'image',
                    'required' => false,
                    'translatable' => false,
                    'width' => 'full',
                ],
                [
                    'name' => 'video_url',
                    'label' => 'Video URL',
                    'type' => 'text',
                    'required' => false,
                    'placeholder' => 'Enter YouTube or Vimeo URL',
                    'translatable' => false,
                    'width' => 'full',
                ],
                [
                    'name' => 'background_color',
                    'label' => 'Background Color',
                    'type' => 'color',
                    'required' => false,
                    'translatable' => false,
                    'width' => '1/2',
                ],
                [
                    'name' => 'text_color',
                    'label' => 'Text Color',
                    'type' => 'color',
                    'required' => false,
                    'translatable' => false,
                    'width' => '1/2',
                ],
            ],
            'default_data' => [
                'title' => 'Welcome to Our Website',
                'subtitle' => 'Check out our amazing features and services.',
                'link1' => [
                    'text' => 'Get Started',
                    'url' => '#',
                ],
                'link2' => [
                    'text' => 'Learn More',
                    'url' => '#',
                ],
            ],
            'is_active' => true,
            'sort_order' => 10,
        ]);

        // 2. Features Block
        BlockType::create([
            'name' => 'Features',
            'slug' => 'features',
            'description' => 'Showcase multiple features with icons and descriptions',
            'icon' => 'fas fa-list',
            'category' => 'Basic',
            'schema' => [
                [
                    'name' => 'title',
                    'label' => 'Section Title',
                    'type' => 'text',
                    'required' => false,
                    'placeholder' => 'Enter section title',
                    'translatable' => true,
                    'width' => 'full',
                ],
                [
                    'name' => 'subtitle',
                    'label' => 'Section Subtitle',
                    'type' => 'textarea',
                    'required' => false,
                    'placeholder' => 'Enter section subtitle',
                    'translatable' => true,
                    'width' => 'full',
                ],
                [
                    'name' => 'features',
                    'label' => 'Features',
                    'type' => 'repeater',
                    'required' => true,
                    'translatable' => true,
                    'width' => 'full',
                ],
                [
                    'name' => 'columns',
                    'label' => 'Columns',
                    'type' => 'select',
                    'options' => [
                        '2' => '2 Columns',
                        '3' => '3 Columns',
                        '4' => '4 Columns',
                    ],
                    'default' => '3',
                    'required' => true,
                    'translatable' => false,
                    'width' => '1/2',
                ],
                [
                    'name' => 'background_color',
                    'label' => 'Background Color',
                    'type' => 'color',
                    'required' => false,
                    'translatable' => false,
                    'width' => '1/2',
                ],
            ],
            'default_data' => [
                'title' => 'Our Features',
                'subtitle' => 'Check out what makes us special.',
                'features' => [
                    [
                        'icon' => 'fas fa-rocket',
                        'title' => 'Fast & Reliable',
                        'description' => 'Our service is quick and dependable.',
                    ],
                    [
                        'icon' => 'fas fa-shield-alt',
                        'title' => 'Secure',
                        'description' => 'Your data is protected with us.',
                    ],
                    [
                        'icon' => 'fas fa-cogs',
                        'title' => 'Customizable',
                        'description' => 'Tailor our services to your needs.',
                    ],
                ],
                'columns' => '3',
            ],
            'is_active' => true,
            'sort_order' => 20,
        ]);

        // 3. Content Block
        BlockType::create([
            'name' => 'Text Content',
            'slug' => 'content',
            'description' => 'Rich text content block with optional image',
            'icon' => 'fas fa-align-left',
            'category' => 'Basic',
            'schema' => [
                [
                    'name' => 'title',
                    'label' => 'Title',
                    'type' => 'text',
                    'required' => false,
                    'placeholder' => 'Enter title',
                    'translatable' => true,
                    'width' => 'full',
                ],
                [
                    'name' => 'content',
                    'label' => 'Content',
                    'type' => 'rich_text',
                    'required' => true,
                    'placeholder' => 'Enter content',
                    'translatable' => true,
                    'width' => 'full',
                ],
                [
                    'name' => 'image',
                    'label' => 'Image',
                    'type' => 'image',
                    'required' => false,
                    'translatable' => false,
                    'width' => 'full',
                ],
                [
                    'name' => 'image_position',
                    'label' => 'Image Position',
                    'type' => 'select',
                    'options' => [
                        'left' => 'Left',
                        'right' => 'Right',
                        'top' => 'Top',
                        'bottom' => 'Bottom',
                    ],
                    'default' => 'right',
                    'required' => false,
                    'translatable' => false,
                    'width' => '1/2',
                ],
                [
                    'name' => 'text_alignment',
                    'label' => 'Text Alignment',
                    'type' => 'select',
                    'options' => [
                        'left' => 'Left',
                        'center' => 'Center',
                        'right' => 'Right',
                    ],
                    'default' => 'left',
                    'required' => false,
                    'translatable' => false,
                    'width' => '1/2',
                ],
            ],
            'default_data' => [
                'title' => 'About Us',
                'content' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam eget felis eget turpis varius.</p>',
                'image_position' => 'right',
                'text_alignment' => 'left',
            ],
            'is_active' => true,
            'sort_order' => 30,
        ]);

        // 4. Image Gallery Block
        BlockType::create([
            'name' => 'Image Gallery',
            'slug' => 'gallery',
            'description' => 'Image gallery with optional lightbox',
            'icon' => 'fas fa-images',
            'category' => 'Media',
            'schema' => [
                [
                    'name' => 'title',
                    'label' => 'Gallery Title',
                    'type' => 'text',
                    'required' => false,
                    'placeholder' => 'Enter gallery title',
                    'translatable' => true,
                    'width' => 'full',
                ],
                [
                    'name' => 'description',
                    'label' => 'Gallery Description',
                    'type' => 'textarea',
                    'required' => false,
                    'placeholder' => 'Enter gallery description',
                    'translatable' => true,
                    'width' => 'full',
                ],
                [
                    'name' => 'images',
                    'label' => 'Gallery Images',
                    'type' => 'repeater',
                    'required' => true,
                    'translatable' => true,
                    'width' => 'full',
                ],
                [
                    'name' => 'columns',
                    'label' => 'Columns',
                    'type' => 'select',
                    'options' => [
                        '2' => '2 Columns',
                        '3' => '3 Columns',
                        '4' => '4 Columns',
                    ],
                    'default' => '3',
                    'required' => true,
                    'translatable' => false,
                    'width' => '1/2',
                ],
                [
                    'name' => 'enable_lightbox',
                    'label' => 'Enable Lightbox',
                    'type' => 'checkbox',
                    'required' => false,
                    'default' => true,
                    'translatable' => false,
                    'width' => '1/2',
                ],
            ],
            'default_data' => [
                'title' => 'Our Gallery',
                'description' => 'Check out our latest photos.',
                'images' => [
                    [
                        'image' => '',
                        'caption' => 'Image 1',
                        'alt' => 'Gallery Image 1',
                    ],
                    [
                        'image' => '',
                        'caption' => 'Image 2',
                        'alt' => 'Gallery Image 2',
                    ],
                    [
                        'image' => '',
                        'caption' => 'Image 3',
                        'alt' => 'Gallery Image 3',
                    ],
                ],
                'columns' => '3',
                'enable_lightbox' => true,
            ],
            'is_active' => true,
            'sort_order' => 40,
        ]);

        // 5. Call to Action Block
        BlockType::create([
            'name' => 'Call to Action',
            'slug' => 'cta',
            'description' => 'Call to action block with button',
            'icon' => 'fas fa-bullhorn',
            'category' => 'Basic',
            'schema' => [
                [
                    'name' => 'title',
                    'label' => 'Title',
                    'type' => 'text',
                    'required' => true,
                    'placeholder' => 'Enter title',
                    'translatable' => true,
                    'width' => 'full',
                ],
                [
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'textarea',
                    'required' => false,
                    'placeholder' => 'Enter description',
                    'translatable' => true,
                    'width' => 'full',
                ],
                [
                    'name' => 'button',
                    'label' => 'Button',
                    'type' => 'link',
                    'required' => true,
                    'translatable' => true,
                    'width' => 'full',
                ],
                [
                    'name' => 'background_color',
                    'label' => 'Background Color',
                    'type' => 'color',
                    'required' => false,
                    'translatable' => false,
                    'width' => '1/2',
                ],
                [
                    'name' => 'text_color',
                    'label' => 'Text Color',
                    'type' => 'color',
                    'required' => false,
                    'translatable' => false,
                    'width' => '1/2',
                ],
                [
                    'name' => 'style',
                    'label' => 'Style',
                    'type' => 'select',
                    'options' => [
                        'standard' => 'Standard',
                        'boxed' => 'Boxed',
                        'full-width' => 'Full Width',
                    ],
                    'default' => 'standard',
                    'required' => false,
                    'translatable' => false,
                    'width' => 'full',
                ],
            ],
            'default_data' => [
                'title' => 'Ready to Get Started?',
                'description' => 'Join thousands of satisfied customers today.',
                'button' => [
                    'text' => 'Contact Us',
                    'url' => '#contact',
                ],
                'style' => 'standard',
            ],
            'is_active' => true,
            'sort_order' => 50,
        ]);
    }
}