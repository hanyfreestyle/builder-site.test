# هيكل ملفات نظام Site Builder

يوضح هذا الملف هيكل الملفات والمجلدات الخاصة بنظام Site Builder، مع شرح مختصر لدور كل ملف ومجلد.

## المجلدات الرئيسية

### نماذج البيانات (Models)

```
app/Models/Builder/
├── BlockType.php         # نموذج نوع البلوك
├── Block.php             # نموذج البلوك
├── Template.php          # نموذج القالب
├── Page.php              # نموذج الصفحة
├── Menu.php              # نموذج القائمة
└── MenuItem.php          # نموذج عنصر القائمة
```

### التعدادات (Enums)

```
app/Enums/SiteBuilder/
├── BlockCategory.php     # تعداد فئات البلوكات
├── BlockTypeField.php    # تعداد أنواع حقول البلوكات
├── FieldWidth.php        # تعداد عرض الحقول
├── MenuItemType.php      # تعداد أنواع عناصر القائمة
└── MenuLocation.php      # تعداد مواقع القوائم
```

### الخدمات (Services)

```
app/Services/Builder/
├── BlockRenderer.php     # خدمة عرض البلوكات
└── LanguageService.php   # خدمة إدارة اللغات المدعومة
```

### وحدات التحكم (Controllers)

```
app/Http/Controllers/Builder/
└── PageController.php    # متحكم عرض الصفحات في الواجهة الأمامية
```

### ميدلوير (Middleware)

```
app/Http/Middleware/
└── LocaleMiddleware.php  # ميدلوير إدارة اللغة الحالية
```

### موارد Filament (Filament Resources)

```
app/Filament/Admin/Resources/
├── BuilderBlockTypeResource/              # مورد أنواع البلوكات
│   ├── Pages/
│   │   ├── CreateBuilderBlockType.php     # صفحة إنشاء نوع بلوك
│   │   ├── EditBuilderBlockType.php       # صفحة تعديل نوع بلوك
│   │   └── ListBuilderBlockTypes.php      # صفحة قائمة أنواع البلوكات
│   └── BuilderBlockTypeResource.php       # تعريف مورد نوع البلوك
│
├── BuilderMenuResource/                   # مورد القوائم
│   ├── Pages/
│   │   ├── CreateBuilderMenu.php          # صفحة إنشاء قائمة
│   │   ├── EditBuilderMenu.php            # صفحة تعديل قائمة
│   │   └── ListBuilderMenus.php           # صفحة قائمة القوائم
│   ├── RelationManagers/
│   │   └── MenuItemsRelationManager.php   # مدير علاقة عناصر القائمة
│   └── BuilderMenuResource.php            # تعريف مورد القائمة
│
├── BuilderPageResource/                   # مورد الصفحات
│   ├── Pages/
│   │   ├── CreateBuilderPage.php          # صفحة إنشاء صفحة
│   │   ├── EditBuilderPage.php            # صفحة تعديل صفحة
│   │   └── ListBuilderPages.php           # صفحة قائمة الصفحات
│   ├── RelationManagers/
│   │   └── BlocksRelationManager.php      # مدير علاقة البلوكات
│   └── BuilderPageResource.php            # تعريف مورد الصفحة
│
└── BuilderTemplateResource/               # مورد القوالب
    ├── Pages/
    │   ├── CreateBuilderTemplate.php      # صفحة إنشاء قالب
    │   ├── EditBuilderTemplate.php        # صفحة تعديل قالب
    │   └── ListBuilderTemplates.php       # صفحة قائمة القوالب
    ├── RelationManagers/
    │   └── BlockTypesRelationManager.php  # مدير علاقة أنواع البلوكات
    └── BuilderTemplateResource.php        # تعريف مورد القالب
```

### ملفات التعريب (Translation Files)

```
lang/
├── ar/
│   └── site-builder/
│       ├── block-type.php       # ترجمة أنواع البلوكات
│       ├── general.php          # ترجمة عامة
│       ├── menu.php             # ترجمة القوائم
│       ├── menu-item.php        # ترجمة عناصر القائمة
│       ├── page.php             # ترجمة الصفحات
│       ├── template.php         # ترجمة القوالب
│       └── translation.php      # ترجمة مكونات الترجمة
│
└── en/
    └── site-builder/
        ├── block-type.php       # ترجمة أنواع البلوكات
        ├── general.php          # ترجمة عامة
        ├── menu.php             # ترجمة القوائم
        ├── menu-item.php        # ترجمة عناصر القائمة
        ├── page.php             # ترجمة الصفحات
        ├── template.php         # ترجمة القوالب
        └── translation.php      # ترجمة مكونات الترجمة
```

### قوالب العرض (Views)

```
resources/views/
├── blocks/                                # قوالب البلوكات العامة (Fallback)
│   ├── hero/
│   │   └── default.blade.php              # قالب عرض بلوك Hero الافتراضي
│   └── features/
│       └── default.blade.php              # قالب عرض بلوك الميزات الافتراضي
│
├── components/                            # مكونات Blade المشتركة 
│   └── language-switcher.blade.php        # مكون تبديل اللغات
│
└── templates/                             # قوالب المواقع
    ├── default/                           # قالب الموقع الافتراضي
    │   ├── blocks/                        # بلوكات خاصة بقالب الافتراضي
    │   ├── layout.blade.php               # قالب التخطيط العام للقالب الافتراضي
    │   └── page.blade.php                 # قالب الصفحة للقالب الافتراضي
    │
    ├── restaurant/                        # قالب موقع المطعم
    │   ├── blocks/                        # بلوكات خاصة بقالب المطعم
    │   │   ├── hero/
    │   │   │   ├── centered.blade.php     # إصدار موسط لبلوك Hero
    │   │   │   └── with-video.blade.php   # إصدار مع فيديو لبلوك Hero
    │   │   └── features/
    │   ├── layout.blade.php               # قالب التخطيط العام للمطعم
    │   └── page.blade.php                 # قالب الصفحة للمطعم
    │
    └── agency/                            # قالب موقع الوكالة
        ├── blocks/                        # بلوكات خاصة بقالب الوكالة
        │   ├── hero/
        │   │   └── full-width.blade.php   # إصدار كامل العرض لبلوك Hero
        │   └── features/
        ├── layout.blade.php               # قالب التخطيط العام للوكالة
        └── page.blade.php                 # قالب الصفحة للوكالة
```

### تكوين التطبيق (Configuration)

```
config/
└── laravellocalization.php       # إعدادات مكتبة LaravelLocalization
```

### تجهيز قاعدة البيانات (Migrations & Seeds)

```
database/
├── migrations/
│   └── 2025_05_16_000001_create_builder_tables.php   # ملف ترحيل جداول Site Builder
│
└── seeders/
    ├── Builder/
    │   ├── BlockTypeSeeder.php            # بذرة أنواع البلوكات الأساسية
    │   ├── TemplateSeeder.php             # بذرة القوالب الأساسية
    │   └── DemoContentSeeder.php          # بذرة محتوى تجريبي
    │
    ├── BuilderSeeder.php                  # بذرة رئيسية لـ Site Builder
    └── DatabaseSeeder.php                 # بذرة قاعدة البيانات الرئيسية
```

### المسارات (Routes)

```
routes/
├── builder.php                            # مسارات عرض Site Builder الأمامية
└── web.php                                # مسارات الويب الرئيسية (تتضمن مسارات builder.php)
```

### تكوين التطبيق (App Configuration)

```
bootstrap/
└── app.php                                # تكوين التطبيق وتسجيل الميدلوير
```

## وصف وظائف الملفات الرئيسية

### ملفات النماذج

1. **`BlockType.php`** - نموذج نوع البلوك يحدد مختلف أنواع البلوكات المتاحة في النظام مع schema لكل نوع.

2. **`Block.php`** - نموذج البلوك يمثل مثيل فعلي من نوع بلوك معين داخل صفحة محددة مع البيانات الخاصة به.

3. **`Template.php`** - نموذج القالب يحدد قالب الموقع مع الإعدادات والألوان والخطوط وأنواع البلوكات المتاحة واللغات المدعومة.

4. **`Page.php`** - نموذج الصفحة يمثل صفحة فعلية في الموقع تحتوي على مجموعة من البلوكات.

5. **`Menu.php`** و **`MenuItem.php`** - نماذج تمثل قوائم الموقع وعناصرها المختلفة.

### ملفات التعدادات (Enums)

1. **`BlockCategory.php`** - تعريف فئات البلوكات (أساسي، وسائط، متقدم).

2. **`BlockTypeField.php`** - تعريف أنواع حقول البلوكات (نص، نص طويل، محرر نصوص، إلخ).

3. **`FieldWidth.php`** - تعريف خيارات عرض الحقول (كامل، نصف، ثلث، إلخ).

4. **`MenuItemType.php`** - تعريف أنواع عناصر القائمة (رابط، صفحة، مسار، قسم).

5. **`MenuLocation.php`** - تعريف مواقع القوائم (ترويسة، تذييل، جانبي، إلخ).

### ملفات الخدمات

1. **`BlockRenderer.php`** - خدمة مسؤولة عن عرض البلوكات وفق أنسب قالب عرض متاح بناءً على نوع البلوك والقالب المستخدم.

2. **`LanguageService.php`** - خدمة مسؤولة عن إدارة اللغات المدعومة وفلترتها بناءً على إعدادات القالب.

### ملفات الميدلوير

1. **`LocaleMiddleware.php`** - ميدلوير يتحكم في اللغة الحالية للتطبيق بناءً على الجلسة.

### ملفات مكونات Blade

1. **`language-switcher.blade.php`** - مكون مسؤول عن عرض واجهة تبديل اللغات بشكل ذكي (إخفاء اللغة الحالية وظهور كقائمة منسدلة عند وجود أكثر من لغتين).

### ملفات التحكم

1. **`PageController.php`** - متحكم لعرض الصفحات في الواجهة الأمامية للموقع، بما في ذلك الصفحة الرئيسية والصفحات العادية، مع دعم التبديل بين القوالب والقالب الافتراضي.

### ملفات موارد Filament

1. **موارد البلوكات** - تتحكم في إدارة أنواع البلوكات وبنيتها وربطها بالقوالب.

2. **موارد الصفحات** - تتحكم في إنشاء وتعديل الصفحات وإضافة البلوكات إليها.

3. **موارد القوالب** - تتحكم في إدارة قوالب المواقع وربطها بأنواع البلوكات المختلفة واللغات المدعومة.

4. **موارد القوائم** - تتحكم في إدارة قوائم الموقع وعناصرها.

### ملفات قوالب العرض

1. **قوالب البلوكات** - قوالب Blade تحدد كيفية عرض كل نوع من أنواع البلوكات. تنقسم إلى:
   - قوالب عامة (blocks/) تعمل كـ fallback
   - قوالب خاصة بكل قالب موقع (templates/{template}/blocks/)

2. **قوالب الصفحات والتخطيط** - قوالب Blade تحدد البنية العامة للصفحات في كل قالب موقع.

3. **مكونات مشتركة** - مكونات Blade مشتركة بين جميع القوالب مثل مكون تبديل اللغات.

### ملفات التعريب

1. **ملفات تعريب عامة** - ملفات تحتوي على ترجمات للعناصر المشتركة في النظام.

2. **ملفات تعريب خاصة بكل مكون** - ملفات تحتوي على ترجمات للعناصر الخاصة بكل مكون.

### ملفات المسارات

1. **`builder.php`** - يحدد مسارات الواجهة الأمامية لعرض الصفحات المنشأة باستخدام Site Builder، ويتضمن إعدادات تحديد اللغة ومسارات متعددة اللغات باستخدام LaravelLocalization.

2. **`web.php`** - يدمج مسارات Site Builder مع مسارات الويب الرئيسية للتطبيق.

### ملفات تجهيز قاعدة البيانات

1. **`create_builder_tables.php`** - ملف ترحيل ينشئ جميع جداول قاعدة البيانات المطلوبة لنظام Site Builder، بما في ذلك حقل supported_languages في جدول builder_templates.

2. **ملفات البذور** - تقوم بتهيئة قاعدة البيانات بالبيانات الأساسية مثل أنواع البلوكات والقوالب والمحتوى التجريبي.

## ملاحظات تنفيذية

1. **تسلسل التثبيت**:
   ```bash
   php artisan migrate
   php artisan db:seed --class=BuilderSeeder
   ```

2. **ترتيب تطوير المكونات الجديدة**:
   - إضافة نموذج بيانات جديد (Model)
   - إنشاء التعدادات المرتبطة (Enums) إن وجدت
   - إضافة ملفات التعريب المطلوبة
   - إضافة الخدمات المرتبطة (Service)
   - إضافة قوالب العرض (Views)
   - إضافة موارد Filament (Resource)

3. **إضافة نوع بلوك جديد**:
   - إضافة نوع بلوك جديد في `BlockTypeSeeder`
   - إنشاء قوالب العرض المناسبة في مجلد `blocks/{block_type}/`
   - إنشاء الإصدارات المخصصة في `templates/{template}/blocks/{block_type}/`

4. **إضافة قالب جديد**:
   - إضافة قالب جديد في `TemplateSeeder`
   - إنشاء مجلد للقالب في `templates/{template_slug}/`
   - إنشاء قوالب التخطيط والصفحة والبلوكات المخصصة
   - تحديد اللغات المدعومة للقالب من خلال حقل `supported_languages`

5. **دعم اللغات المتعددة**:
   - تكوين اللغات المدعومة في ملف `config/laravellocalization.php`
   - تحديد اللغات المدعومة لكل قالب في جدول `builder_templates`
   - استخدام `LaravelLocalization` لإنشاء روابط اللغات
   - استخدام `LanguageService` للحصول على اللغات المدعومة حسب القالب
   - تضمين مكون `language-switcher` في قوالب التخطيط