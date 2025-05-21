<?php

return [
    'singular' => 'قالب',
    'tabs' => [
        'basic_info' => 'المعلومات الأساسية',
        'settings' => 'الإعدادات',
        'languages' => 'اللغات',
    ],
    'settings' => [
        'colors' => 'الألوان',
        'fonts' => 'الخطوط',
        'spacing' => 'المسافات',
    ],
    'colors' => [
        'primary' => 'اللون الأساسي',
        'secondary' => 'اللون الثانوي',
        'accent' => 'لون التمييز',
        'background' => 'لون الخلفية',
        'text' => 'لون النص',
    ],
    'fonts' => [
        'primary' => 'الخط الأساسي',
        'heading' => 'خط العناوين',
        'base_size' => 'حجم الخط الأساسي',
    ],
    'spacing' => [
        'base' => 'المسافة الأساسية',
        'section' => 'مسافة القسم',
    ],
    'labels' => [
        'supported_languages' => 'اللغات المدعومة',
    ],
    'helpers' => [
        'is_default' => 'عند تفعيل هذا الخيار، سيتم إلغاء تعيين أي قالب آخر كافتراضي.',
    ],
    'actions' => [
        'set_default' => 'تعيين كافتراضي',
        'migrate_pages' => 'تحويل الصفحات',
    ],
    'notifications' => [
        'set_default_success' => 'تم تعيين القالب كافتراضي بنجاح',
        'pages_migrated' => 'تم تحويل :count صفحة لاستخدام القالب الافتراضي',
        'migration_failed' => 'فشل تحويل الصفحات إلى القالب الافتراضي',
    ],
    'modal' => [
        'migrate_pages_title' => ' إلى القالب الافتراضي',
        'migrate_pages_description' => 'سيتم تحويل جميع الصفحات التي تستخدم هذا القالب لاستخدام القالب الافتراضي بدلاً منه. ستحتفظ الصفحات بنفس العناوين URL والمحتوى، وسيتغير فقط القالب المستخدم. لا يمكن التراجع عن هذه العملية.',
        'migrate_pages_submit' => 'تحويل الصفحات',
    ],
];
