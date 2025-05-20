<?php

namespace Database\Seeders\Builder;

use App\Models\Builder\BlockType;
use Illuminate\Database\Seeder;

class BlockTypeSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // 1. Hero Block
        BlockType::create([
            'name' => ['ar'=>'Hero Section Ar','en'=>'Hero Section'],
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
            'is_active' => true,
            'sort_order' => 10,
        ]);

        // 2. Features Block
        BlockType::create([
            'name' => ['ar'=>'Features ar','en'=>'Features'],
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
            'is_active' => true,
            'sort_order' => 20,
        ]);

        // 3. Content Block
        BlockType::create([
            'name' => ['ar'=>'Text Content ar','en'=>'Text Content'],
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
            'is_active' => true,
            'sort_order' => 30,
        ]);

        // 4. Image Gallery Block
        BlockType::create([
            'name' => ['ar'=>'Image Gallery ar','en'=>'Image Gallery'],
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
            'is_active' => true,
            'sort_order' => 40,
        ]);

        // 5. Call to Action Block
        BlockType::create([
            'name' => ['ar'=>'Call to Action ar','en'=>'Call to Action'],
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
            'is_active' => true,
            'sort_order' => 50,
        ]);

        // إنشاء بلوك Slider Carousel
        BlockType::create([
            'name' => ['ar'=>'Slider Carousel ar','en'=>'Slider Carousel'],
            'slug' => 'slider_carousel',
            'description' => 'Slider carousel with image background, title, description and buttons',
            'icon' => 'heroicon-o-photo',
            'category' => 'Basic',
            'schema' => [
                [
                    'name' => 'slides',
                    'label' => 'Slides',
                    'type' => 'repeater',
                    'required' => true,
                    'translatable' => true,
                    'width' => 'full',
                    'fields' => [
                        [
                            'name' => 'image',
                            'label' => 'Background Image',
                            'type' => 'image',
                            'required' => true,
                            'translatable' => false,
                            'width' => 'full',
                        ],
                        [
                            'name' => 'image_alt',
                            'label' => 'Image Alt Text',
                            'type' => 'text',
                            'required' => false,
                            'translatable' => true,
                            'width' => 'full',
                        ],
                        [
                            'name' => 'subtitle',
                            'label' => 'Subtitle',
                            'type' => 'text',
                            'required' => false,
                            'translatable' => true,
                            'width' => 'full',
                        ],
                        [
                            'name' => 'title',
                            'label' => 'Title',
                            'type' => 'text',
                            'required' => true,
                            'translatable' => true,
                            'width' => 'full',
                        ],
                        [
                            'name' => 'description',
                            'label' => 'Description',
                            'type' => 'textarea',
                            'required' => false,
                            'translatable' => true,
                            'width' => 'full',
                        ],
                        [
                            'name' => 'primary_button',
                            'label' => 'Primary Button',
                            'type' => 'link',
                            'required' => false,
                            'translatable' => true,
                            'width' => 'half',
                        ],
                        [
                            'name' => 'secondary_button',
                            'label' => 'Secondary Button',
                            'type' => 'link',
                            'required' => false,
                            'translatable' => true,
                            'width' => 'half',
                        ]
                    ]
                ],
                [
                    'name' => 'overlay_opacity',
                    'label' => 'Overlay Opacity',
                    'type' => 'select',
                    'required' => false,
                    'translatable' => false,
                    'width' => 'half',
                    'options' => [
                        '0.2' => '20%',
                        '0.4' => '40%',
                        '0.6' => '60%',
                        '0.8' => '80%',
                    ],
                    'default' => '0.4'
                ],
                [
                    'name' => 'auto_play',
                    'label' => 'Auto Play',
                    'type' => 'toggle',
                    'required' => false,
                    'translatable' => false,
                    'width' => 'half',
                    'default' => true
                ],
                [
                    'name' => 'loop',
                    'label' => 'Loop Slides',
                    'type' => 'toggle',
                    'required' => false,
                    'translatable' => false,
                    'width' => 'half',
                    'default' => true
                ],
                [
                    'name' => 'interval',
                    'label' => 'Interval (ms)',
                    'type' => 'number',
                    'required' => false,
                    'translatable' => false,
                    'width' => 'half',
                    'default' => 5000
                ]
            ],
            'is_active' => true,
            'sort_order' => 10,
        ]);

        // إنشاء بلوك Slider Carousel
        BlockType::create([
            'name' => ['ar'=>'Test Block ar','en'=>'Test Block'],
            'slug' => 'test_block',
            'category' => 'Basic',
            'schema' => [
                [
                    'name' => 'title',
                    'label' => 'Title',
                    'type' => 'text',
                    'required' => true,
                    'translatable' => true,
                    'width' => 'full',
                ],

            ],
            'is_active' => true,
            'sort_order' => 10,
        ]);

    }
}
