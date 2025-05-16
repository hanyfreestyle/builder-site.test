<?php

namespace Database\Seeders\Builder;

use App\Models\Builder\Template;
use App\Models\Builder\Page;
use App\Models\Builder\Block;
use App\Models\Builder\BlockType;
use App\Models\Builder\Menu;
use App\Models\Builder\MenuItem;
use Illuminate\Database\Seeder;

class DemoContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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

        // Create About Us page
        $aboutPage = Page::create([
            'template_id' => $restaurantTemplate->id,
            'title' => 'About Us',
            'slug' => 'about',
            'description' => 'Learn about our story, our team, and our passion for food.',
            'meta_tags' => [
                'title' => 'About Us | Our Restaurant Story',
                'description' => 'Learn about our restaurant\'s history, our passionate team of chefs, and our commitment to quality.',
                'keywords' => 'restaurant history, about us, chef team, restaurant story',
                'robots' => 'index, follow',
            ],
            'is_homepage' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Create Menu page
        $menuPage = Page::create([
            'template_id' => $restaurantTemplate->id,
            'title' => 'Our Menu',
            'slug' => 'menu',
            'description' => 'Explore our delicious offerings for breakfast, lunch, and dinner.',
            'meta_tags' => [
                'title' => 'Menu | Delicious Food Options',
                'description' => 'Explore our diverse menu featuring appetizers, main courses, desserts, and beverages.',
                'keywords' => 'restaurant menu, food menu, dinner options, lunch menu',
                'robots' => 'index, follow',
            ],
            'is_homepage' => false,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Create Contact page
        $contactPage = Page::create([
            'template_id' => $restaurantTemplate->id,
            'title' => 'Contact Us',
            'slug' => 'contact',
            'description' => 'Get in touch with us for reservations, questions, or feedback.',
            'meta_tags' => [
                'title' => 'Contact Us | Reservations & Information',
                'description' => 'Make reservations, find our location, or send us feedback about your dining experience.',
                'keywords' => 'restaurant contact, reservations, restaurant location, dining feedback',
                'robots' => 'index, follow',
            ],
            'is_homepage' => false,
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // Get block types
        $heroBlock = BlockType::where('slug', 'hero')->first();
        $featuresBlock = BlockType::where('slug', 'features')->first();
        $contentBlock = BlockType::where('slug', 'content')->first();
        $ctaBlock = BlockType::where('slug', 'cta')->first();
        $galleryBlock = BlockType::where('slug', 'gallery')->first();

        // Add blocks to homepage
        if ($heroBlock) {
            Block::create([
                'page_id' => $homepage->id,
                'block_type_id' => $heroBlock->id,
                'data' => [
                    'title' => 'Welcome to Fine Dining Experience',
                    'subtitle' => 'Experience the finest cuisine in town with our award-winning chefs and cozy atmosphere.',
                    'link1' => [
                        'text' => 'Book a Table',
                        'url' => '/contact',
                    ],
                    'link2' => [
                        'text' => 'See Our Menu',
                        'url' => '/menu',
                    ],
                    'photo' => 'images/restaurant-hero.jpg',
                ],
                'view_version' => 'centered',
                'sort_order' => 0,
                'is_active' => true,
                'is_visible' => true,
            ]);
        }

        if ($featuresBlock) {
            Block::create([
                'page_id' => $homepage->id,
                'block_type_id' => $featuresBlock->id,
                'data' => [
                    'title' => 'Why Choose Us',
                    'subtitle' => 'We take pride in providing an exceptional dining experience.',
                    'features' => [
                        [
                            'icon' => 'fas fa-utensils',
                            'title' => 'Quality Ingredients',
                            'description' => 'We use only the freshest and highest quality ingredients in all our dishes.',
                        ],
                        [
                            'icon' => 'fas fa-award',
                            'title' => 'Award-Winning Chefs',
                            'description' => 'Our team of skilled chefs has won numerous culinary awards.',
                        ],
                        [
                            'icon' => 'fas fa-glass-cheers',
                            'title' => 'Elegant Atmosphere',
                            'description' => 'Enjoy your meal in our beautifully designed dining space.',
                        ],
                    ],
                    'columns' => '3',
                ],
                'view_version' => 'default',
                'sort_order' => 1,
                'is_active' => true,
                'is_visible' => true,
            ]);
        }

        if ($galleryBlock) {
            Block::create([
                'page_id' => $homepage->id,
                'block_type_id' => $galleryBlock->id,
                'data' => [
                    'title' => 'Our Specialties',
                    'description' => 'Take a look at some of our most popular dishes.',
                    'images' => [
                        [
                            'image' => 'images/food1.jpg',
                            'caption' => 'Grilled Salmon',
                            'alt' => 'Grilled Salmon with Vegetables',
                        ],
                        [
                            'image' => 'images/food2.jpg',
                            'caption' => 'Beef Steak',
                            'alt' => 'Premium Beef Steak',
                        ],
                        [
                            'image' => 'images/food3.jpg',
                            'caption' => 'Pasta Carbonara',
                            'alt' => 'Creamy Pasta Carbonara',
                        ],
                        [
                            'image' => 'images/food4.jpg',
                            'caption' => 'Chocolate Dessert',
                            'alt' => 'Chocolate Lava Cake',
                        ],
                    ],
                    'columns' => '4',
                    'enable_lightbox' => true,
                ],
                'view_version' => 'default',
                'sort_order' => 2,
                'is_active' => true,
                'is_visible' => true,
            ]);
        }

        if ($ctaBlock) {
            Block::create([
                'page_id' => $homepage->id,
                'block_type_id' => $ctaBlock->id,
                'data' => [
                    'title' => 'Make a Reservation Today',
                    'description' => 'Secure your table now and enjoy a memorable dining experience with your loved ones.',
                    'button' => [
                        'text' => 'Book a Table',
                        'url' => '/contact',
                    ],
                    'style' => 'boxed',
                ],
                'view_version' => 'centered',
                'sort_order' => 3,
                'is_active' => true,
                'is_visible' => true,
            ]);
        }

        // Add blocks to About page
        if ($heroBlock) {
            Block::create([
                'page_id' => $aboutPage->id,
                'block_type_id' => $heroBlock->id,
                'data' => [
                    'title' => 'Our Story',
                    'subtitle' => 'Learn about our journey, vision, and the team behind our restaurant.',
                    'photo' => 'images/restaurant-interior.jpg',
                ],
                'view_version' => 'default',
                'sort_order' => 0,
                'is_active' => true,
                'is_visible' => true,
            ]);
        }

        if ($contentBlock) {
            Block::create([
                'page_id' => $aboutPage->id,
                'block_type_id' => $contentBlock->id,
                'data' => [
                    'title' => 'Our History',
                    'content' => '<p>Founded in 2010 by Chef John Smith, our restaurant has been serving the community with passion and dedication for over a decade.</p><p>What started as a small family business has grown into one of the most respected dining establishments in the city, known for our commitment to quality, creativity, and exceptional service.</p><p>Over the years, we have expanded our menu, renovated our space, and built a loyal customer base who appreciates our authentic flavors and warm hospitality.</p>',
                    'image' => 'images/chef-founder.jpg',
                    'image_position' => 'right',
                    'text_alignment' => 'left',
                ],
                'view_version' => 'default',
                'sort_order' => 1,
                'is_active' => true,
                'is_visible' => true,
            ]);
        }

        if ($featuresBlock) {
            Block::create([
                'page_id' => $aboutPage->id,
                'block_type_id' => $featuresBlock->id,
                'data' => [
                    'title' => 'Meet Our Team',
                    'subtitle' => 'The talented individuals who make our restaurant special.',
                    'features' => [
                        [
                            'icon' => 'fas fa-user-chef',
                            'title' => 'Chef John Smith',
                            'description' => 'Head Chef & Founder with over 20 years of culinary experience.',
                        ],
                        [
                            'icon' => 'fas fa-user-chef',
                            'title' => 'Chef Maria Garcia',
                            'description' => 'Pastry Chef specializing in exquisite desserts and baked goods.',
                        ],
                        [
                            'icon' => 'fas fa-user-tie',
                            'title' => 'Michael Johnson',
                            'description' => 'Restaurant Manager ensuring smooth operations and guest satisfaction.',
                        ],
                    ],
                    'columns' => '3',
                ],
                'view_version' => 'icon-top',
                'sort_order' => 2,
                'is_active' => true,
                'is_visible' => true,
            ]);
        }

        // Create menus
        $headerMenu = Menu::create([
            'template_id' => $restaurantTemplate->id,
            'name' => 'Header Menu',
            'slug' => 'header-menu',
            'location' => 'header',
            'is_active' => true,
        ]);

        $footerMenu = Menu::create([
            'template_id' => $restaurantTemplate->id,
            'name' => 'Footer Menu',
            'slug' => 'footer-menu',
            'location' => 'footer',
            'is_active' => true,
        ]);

        // Add menu items to Header Menu
        MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'Home',
            'type' => 'page',
            'page_id' => $homepage->id,
            'icon' => 'fas fa-home',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'About Us',
            'type' => 'page',
            'page_id' => $aboutPage->id,
            'icon' => 'fas fa-info-circle',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'Menu',
            'type' => 'page',
            'page_id' => $menuPage->id,
            'icon' => 'fas fa-utensils',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'Contact',
            'type' => 'page',
            'page_id' => $contactPage->id,
            'icon' => 'fas fa-envelope',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // Add menu items to Footer Menu
        $footerAboutItem = MenuItem::create([
            'menu_id' => $footerMenu->id,
            'title' => 'About',
            'type' => 'section',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'parent_id' => $footerAboutItem->id,
            'title' => 'Our Story',
            'type' => 'page',
            'page_id' => $aboutPage->id,
            'is_active' => true,
            'sort_order' => 0,
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'parent_id' => $footerAboutItem->id,
            'title' => 'Our Team',
            'type' => 'url',
            'url' => '/about#team',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $footerMenusItem = MenuItem::create([
            'menu_id' => $footerMenu->id,
            'title' => 'Menus',
            'type' => 'section',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'parent_id' => $footerMenusItem->id,
            'title' => 'Lunch',
            'type' => 'url',
            'url' => '/menu#lunch',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'parent_id' => $footerMenusItem->id,
            'title' => 'Dinner',
            'type' => 'url',
            'url' => '/menu#dinner',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'parent_id' => $footerMenusItem->id,
            'title' => 'Desserts',
            'type' => 'url',
            'url' => '/menu#desserts',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $footerContactItem = MenuItem::create([
            'menu_id' => $footerMenu->id,
            'title' => 'Connect',
            'type' => 'section',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'parent_id' => $footerContactItem->id,
            'title' => 'Contact Us',
            'type' => 'page',
            'page_id' => $contactPage->id,
            'is_active' => true,
            'sort_order' => 0,
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'parent_id' => $footerContactItem->id,
            'title' => 'Reservations',
            'type' => 'url',
            'url' => '/contact#reservations',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'parent_id' => $footerContactItem->id,
            'title' => 'Facebook',
            'type' => 'url',
            'url' => 'https://facebook.com',
            'icon' => 'fab fa-facebook',
            'target_blank' => true,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'parent_id' => $footerContactItem->id,
            'title' => 'Instagram',
            'type' => 'url',
            'url' => 'https://instagram.com',
            'icon' => 'fab fa-instagram',
            'target_blank' => true,
            'is_active' => true,
            'sort_order' => 3,
        ]);
    }
}