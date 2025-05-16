<?php

return [
    'singular' => 'Template',
    'tabs' => [
        'basic_info' => 'Basic Information',
        'settings' => 'Settings',
        'languages' => 'Languages',
    ],
    'settings' => [
        'colors' => 'Colors',
        'fonts' => 'Fonts',
        'spacing' => 'Spacing',
    ],
    'colors' => [
        'primary' => 'Primary Color',
        'secondary' => 'Secondary Color',
        'accent' => 'Accent Color',
        'background' => 'Background Color',
        'text' => 'Text Color',
    ],
    'fonts' => [
        'primary' => 'Primary Font',
        'heading' => 'Heading Font',
        'base_size' => 'Base Font Size',
    ],
    'spacing' => [
        'base' => 'Base Spacing',
        'section' => 'Section Spacing',
    ],
    'labels' => [
        'supported_languages' => 'Supported Languages',
    ],
    'helpers' => [
        'is_default' => 'When enabled, any other default template will be deactivated.',
    ],
    'actions' => [
        'set_default' => 'Set as Default',
        'migrate_pages' => 'Convert Pages to Default Template',
    ],
    'notifications' => [
        'set_default_success' => 'Template has been set as default successfully',
        'pages_migrated' => ':count pages have been converted to use the default template',
        'migration_failed' => 'Failed to convert pages to default template',
    ],
    'modal' => [
        'migrate_pages_title' => 'Convert Pages to Default Template',
        'migrate_pages_description' => 'This will convert all pages that use this template to use the default template instead. Pages will keep the same URLs and content, only the template will change. This operation cannot be undone.',
        'migrate_pages_submit' => 'Convert Pages',
    ],
];
