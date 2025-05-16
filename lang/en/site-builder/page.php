<?php

return [
    'singular' => 'Page',
    'tabs' => [
        'basic_info' => 'Basic Information',
        'seo' => 'SEO',
    ],
    'labels' => [
        'template' => 'Template',
        'is_homepage' => 'Homepage',
        'use_default_template' => 'Use Default Template',
    ],
    'help_text' => [
        'is_homepage' => 'Set this page as the homepage of the website',
        'meta_title' => 'Title displayed in search results (preferably less than 60 characters)',
        'meta_description' => 'Description displayed in search results (preferably less than 160 characters)',
        'meta_keywords' => 'Keywords separated by commas',
    ],
    'seo' => [
        'meta_title' => 'Meta Title',
        'meta_description' => 'Meta Description',
        'meta_keywords' => 'Meta Keywords',
        'robots' => 'Robots',
        'og_title' => 'Open Graph Title',
        'og_description' => 'Open Graph Description',
        'og_image' => 'Open Graph Image',
        'robots_options' => [
            'index_follow' => 'Index, Follow',
            'noindex_follow' => 'No Index, Follow',
            'index_nofollow' => 'Index, No Follow',
            'noindex_nofollow' => 'No Index, No Follow',
        ],
    ],
    'translations' => [
        'title' => 'Title',
        'description' => 'Description',
        'meta_title' => 'Meta Title',
        'meta_description' => 'Meta Description',
    ],
    'template_options' => [
        'use_default_template' => 'Use Default Template (:template)',
    ],
    'using_default_template' => 'Using default template',
    'actions' => [
        'use_default_template' => 'Use Default Template',
        'use_default_template_bulk' => 'Convert to Default Template',
    ],
    'notifications' => [
        'now_using_default_template' => 'Page is now using the default template',
        'pages_using_default_template' => ':count pages have been converted to use the default template',
    ],
];