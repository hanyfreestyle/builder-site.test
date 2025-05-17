-- الكود الصحيح لإدراج بلوك في قالب
INSERT INTO `builder_template_block_types` (
    `block_type_id`,
    `created_at`,
    `default_view_version`,
    `is_enabled`,
    `sort_order`,
    `template_id`,
    `updated_at`,
    `view_versions`
)
VALUES (
    1,                                   -- معرف نوع البلوك
    '2025-05-17 13:42:44',              -- تاريخ الإنشاء (يجب أن يكون بتنسيق صحيح وداخل علامات اقتباس)
    'default',                           -- إصدار العرض الافتراضي (داخل علامات اقتباس)
    1,                                   -- مفعّل (1 = نعم)
    0,                                   -- ترتيب الفرز
    1,                                   -- معرف القالب
    '2025-05-17 13:42:44',              -- تاريخ التحديث (يجب أن يكون بتنسيق صحيح وداخل علامات اقتباس)
    '["default", "centered", "with-video"]'  -- إصدارات العرض المتاحة (مصفوفة JSON)
);

-- أو استخدام هذا الكود البديل
INSERT INTO builder_template_block_types (block_type_id, template_id, view_versions, default_view_version, is_enabled, sort_order, created_at, updated_at)
VALUES (1, 1, '["default"]', 'default', 1, 0, NOW(), NOW());

-- إضافة أكثر من نوع بلوك في نفس الوقت
INSERT INTO builder_template_block_types (block_type_id, template_id, view_versions, default_view_version, is_enabled, sort_order, created_at, updated_at)
VALUES 
    (1, 1, '["default", "centered"]', 'default', 1, 0, NOW(), NOW()),
    (2, 1, '["default", "boxed"]', 'default', 1, 1, NOW(), NOW()),
    (3, 1, '["default", "full-width"]', 'default', 1, 2, NOW(), NOW());
