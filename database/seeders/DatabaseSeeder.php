<?php

namespace Database\Seeders;

use App\Models\SiteBuilder\BuilderBlock;
use App\Models\SiteBuilder\BuilderPage;
use App\Models\SiteBuilder\BuilderTemplateBlock;
use App\Models\SiteBuilder\TemplateBlockDefinition;
use Database\Seeders\DefaultSeeder\ConfigDataSeeder;
use Database\Seeders\DefaultSeeder\UserSeeder;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call(UserSeeder::class);
        $this->call(ConfigDataSeeder::class);
        loadSeederFromFile('builder_template', true);
        loadSeederFromFile('builder_template_layout', true);
        loadSeederFromFileWithLang('user_guide', true);
        loadSeederFromFileWithLang('user_guide_photo', true);

        TemplateBlockDefinition::create([
            'type' => 'hero',
            'name' => [
                'en' => 'Hero Section',
                'ar' => 'الجزء الرئيسي'
            ],
            'schema' => [
                'fields' => [
                    [
                        'name' => 'title',
                        'type' => 'text',
                        'label' => ['en' => 'Title', 'ar' => 'العنوان']
                    ],
                    [
                        'name' => 'subtitle',
                        'type' => 'text',
                        'label' => ['en' => 'Subtitle', 'ar' => 'العنوان الفرعي']
                    ],
                    [
                        'name' => 'image',
                        'type' => 'image',
                        'label' => ['en' => 'Image', 'ar' => 'الصورة']
                    ],
                    [
                        'name' => 'button_text',
                        'type' => 'text',
                        'label' => ['en' => 'Button Text', 'ar' => 'نص الزر']
                    ],
                    [
                        'name' => 'button_link',
                        'type' => 'url',
                        'label' => ['en' => 'Button Link', 'ar' => 'رابط الزر']
                    ]
                ]
            ]
        ]);

        BuilderTemplateBlock::create([
            'template_id' => 1, // تأكد من وجود تمبلت برقم 1
            'block_definition_id' => 1, // تأكد إن تعريف hero موجود في الجدول الأساسي
            'photo' => 'template-blocks/hero-preview.jpg',
            'photo_thumbnail' => 'template-blocks/thumbnails/hero-thumb.jpg',
            'is_active' => true,
            'position' => 1,
        ]);

        $page = BuilderPage::create([
            'template_id' => 1, // تأكد من وجود هذا التمبلت مسبقًا
            'slug' => 'home',
            'title' => [
                'ar' => 'الرئيسية',
                'en' => 'Home'
            ],
            'locale' => 'ar',
            'is_active' => true,
        ]);

        BuilderBlock::create([
            'page_id' => $page->id,
            'type' => 'hero',
            'data' => [
                'title' => [
                    'ar' => 'مرحبًا بكم في موقعنا',
                    'en' => 'Welcome to Our Website',
                ],
                'subtitle' => [
                    'ar' => 'نحن نقدم أفضل الحلول الرقمية',
                    'en' => 'We provide top-notch digital solutions',
                ],
                'image' => 'uploads/hero.jpg',
                'button_text' => [
                    'ar' => 'ابدأ الآن',
                    'en' => 'Get Started',
                ],
                'button_link' => '/start',
            ],
            'position' => 1,
        ]);

    }
}
