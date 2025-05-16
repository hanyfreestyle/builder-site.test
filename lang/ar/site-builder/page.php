<?php

return [
    'singular' => 'صفحة',
    'tabs' => [
        'basic_info' => 'المعلومات الأساسية',
        'seo' => 'تحسين محركات البحث',
    ],
    'labels' => [
        'template' => 'القالب',
        'is_homepage' => 'الصفحة الرئيسية',
        'use_default_template' => 'استخدام القالب الافتراضي',
    ],
    'help_text' => [
        'is_homepage' => 'تعيين هذه الصفحة كصفحة رئيسية للموقع',
        'meta_title' => 'عنوان الصفحة في نتائج البحث (يفضل أقل من 60 حرف)',
        'meta_description' => 'وصف الصفحة في نتائج البحث (يفضل أقل من 160 حرف)',
        'meta_keywords' => 'الكلمات المفتاحية مفصولة بفواصل',
    ],
    'seo' => [
        'meta_title' => 'عنوان ميتا',
        'meta_description' => 'وصف ميتا',
        'meta_keywords' => 'كلمات مفتاحية',
        'robots' => 'روبوتات محركات البحث',
        'og_title' => 'عنوان Open Graph',
        'og_description' => 'وصف Open Graph',
        'og_image' => 'صورة Open Graph',
        'robots_options' => [
            'index_follow' => 'فهرسة, متابعة',
            'noindex_follow' => 'منع الفهرسة, متابعة',
            'index_nofollow' => 'فهرسة, منع المتابعة',
            'noindex_nofollow' => 'منع الفهرسة, منع المتابعة',
        ],
    ],
    'translations' => [
        'title' => 'العنوان',
        'description' => 'الوصف',
        'meta_title' => 'عنوان ميتا',
        'meta_description' => 'وصف ميتا',
    ],
    'template_options' => [
        'use_default_template' => 'استخدام القالب الافتراضي (:template)',
    ],
    'using_default_template' => 'يستخدم القالب الافتراضي',
    'actions' => [
        'use_default_template' => 'استخدام القالب الافتراضي',
        'use_default_template_bulk' => 'تحويل إلى القالب الافتراضي',
    ],
    'notifications' => [
        'now_using_default_template' => 'تم تعيين الصفحة لاستخدام القالب الافتراضي',
        'pages_using_default_template' => 'تم تحويل :count صفحة لاستخدام القالب الافتراضي',
    ],
];