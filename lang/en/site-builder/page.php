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
    ],
    'help_text' => [
        'is_homepage' => 'Only one page can be set as homepage',
        'meta_title' => 'Optimal length: 50-60 characters',
        'meta_description' => 'Optimal length: 150-160 characters',
        'meta_keywords' => 'Comma-separated keywords',
    ],
    'seo' => [
        'meta_title' => 'Meta Title',
        'meta_description' => 'Meta Description',
        'meta_keywords' => 'Meta Keywords',
        'robots' => 'Robots',
        'og_title' => 'OG Title',
        'og_description' => 'OG Description',
        'og_image' => 'OG Image',
        'robots_options' => [
            'index_follow' => 'Index, Follow',
            'noindex_follow' => 'No Index, Follow',
            'index_nofollow' => 'Index, No Follow',
            'noindex_nofollow' => 'No Index, No Follow',
        ],
    ],
    'translations' => [
        'title' => 'Translated Title',
        'description' => 'Translated Description',
        'meta_title' => 'Translated Meta Title',
        'meta_description' => 'Translated Meta Description',
    ],
    'blocks' => [
        'block_type' => 'Block Type',
        'view_version' => 'View Version',
        'default_language_content' => 'Default Language Content',
        'default_language_description' => 'Enter content for the default language (first language in template settings)',
        'translations_heading' => 'Translations',
        'translations_description' => 'Provide translations for all languages supported by the template. All fields are required.',
        'no_translations_needed' => 'No additional languages are configured for this template.',
        'icon_help' => 'Enter icon name, e.g: fas fa-home',
        'link_text' => 'Link Text',
        'link_url' => 'Link URL',
        'link_text_placeholder' => 'Enter link text',
        'link_url_placeholder' => 'Enter URL',
        'is_visible' => 'Visible',
        'language_content' => ':language Content',
        'repeater_item_field' => 'Item & Field',
        'translation' => 'Translation',
        'repeater_translation_help' => 'Use "0.title" for first item, "1.title" for second item, etc.',
    ],
];
