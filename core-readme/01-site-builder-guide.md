# دليل تنفيذ Site Builder بـ Laravel و Filament

## مقدمة

هذا الدليل يشرح خطوات تنفيذ نظام Site Builder باستخدام Laravel و Filament، مع التركيز على نظام البلوكات والقوالب ذو التصميم المرن. النظام يسمح بإنشاء وإدارة صفحات ديناميكية باستخدام بلوكات معرفة مسبقاً، مع إمكانية تخصيص عرض هذه البلوكات حسب كل قالب.

## هيكل قاعدة البيانات

### 1. جدول القوالب (builder_templates)
يخزن قوالب المواقع المتاحة مع إعداداتها.

### 2. جدول أنواع البلوكات (builder_block_types)
جدول مركزي يحتوي على تعريف جميع أنواع البلوكات وهياكلها (schema) بشكل موحد.

### 3. جدول ربط القوالب بأنواع البلوكات (builder_template_block_types)
يربط بين القوالب وأنواع البلوكات، مع تحديد إصدارات العرض المتاحة لكل نوع بلوك في كل قالب.

### 4. جدول الصفحات (builder_pages)
يخزن صفحات الموقع مع دعم SEO وتعدد اللغات.

### 5. جدول البلوكات (builder_blocks)
يخزن البلوكات داخل الصفحات، مع الإشارة إلى نوع البلوك وإصدار العرض المستخدم.

### 6. جدول القوائم (builder_menus)
يخزن قوائم الموقع المختلفة (رئيسية، تذييل، جانبية).

### 7. جدول عناصر القائمة (builder_menu_items)
يخزن عناصر القوائم، مع دعم القوائم متعددة المستويات.

## خطوات التنفيذ

### المرحلة 1: إعداد المشروع

1. **إنشاء مشروع Laravel جديد**
   - تثبيت Laravel 11
   - تثبيت Filament 3
   - تكوين البيئة الأساسية

2. **إنشاء ملفات الهجرة (Migrations)**
   - إنشاء ملف هجرة واحد لجميع الجداول مع بادئة `builder_`
   - تنفيذ الهجرة

### المرحلة 2: تنفيذ الموديلات

1. **إنشاء موديل القالب (Template)**
   - العلاقة مع أنواع البلوكات (Many-to-Many)
   - العلاقة مع الصفحات (One-to-Many)
   - العلاقة مع القوائم (One-to-Many)
   - دوال مساعدة للحصول على الإصدارات المتاحة لكل نوع بلوك

2. **إنشاء موديل نوع البلوك (BlockType)**
   - العلاقة مع القوالب (Many-to-Many)
   - العلاقة مع البلوكات (One-to-Many)
   - دوال مساعدة للتعامل مع Schema و Default Data

3. **إنشاء موديل الصفحة (Page)**
   - العلاقة مع القالب (Many-to-One)
   - العلاقة مع البلوكات (One-to-Many)
   - دوال مساعدة للتعامل مع الترجمات

4. **إنشاء موديل البلوك (Block)**
   - العلاقة مع الصفحة (Many-to-One)
   - العلاقة مع نوع البلوك (Many-to-One)
   - دوال مساعدة للحصول على البيانات المترجمة

5. **إنشاء موديل القائمة (Menu)**
   - العلاقة مع القالب (Many-to-One)
   - العلاقة مع عناصر القائمة (One-to-Many)
   - دوال مساعدة للتعامل مع الترجمات

6. **إنشاء موديل عنصر القائمة (MenuItem)**
   - العلاقة مع القائمة (Many-to-One)
   - العلاقة مع العنصر الأب (Self Referencing)
   - العلاقة مع العناصر الفرعية (Self Referencing)
   - العلاقة مع الصفحة (المرتبطة إذا كان نوع العنصر "page")
   - دوال مساعدة للتعامل مع أنواع الروابط المختلفة

### المرحلة 3: تنفيذ خدمة عرض البلوكات

1. **إنشاء خدمة BlockRenderer**
   - منطق البحث عن أنسب قالب عرض للبلوك
   - دعم إصدارات العرض المختلفة
   - دعم تعدد اللغات

2. **إنشاء متحكم الصفحات الأمامي**
   - عرض الصفحة الرئيسية
   - عرض الصفحات العادية
   - استخدام BlockRenderer لعرض البلوكات
   - إعداد القوائم للعرض

3. **إضافة روتات العرض الأمامي**
   - روت الصفحة الرئيسية
   - روت الصفحات العادية

### المرحلة 4: تنفيذ لوحة الإدارة باستخدام Filament

1. **إنشاء Filament Resources للموديلات**
   - TemplateResource
   - BlockTypeResource
   - PageResource
   - MenuResource

2. **تنفيذ نماذج إدخال البيانات**
   - نموذج إنشاء وتعديل القوالب
   - نموذج إنشاء وتعديل أنواع البلوكات (مع دعم schema مرن)
   - نموذج ربط القوالب بأنواع البلوكات (مع تحديد إصدارات العرض)
   - نموذج إنشاء وتعديل الصفحات (مع إضافة البلوكات)
   - نموذج إنشاء وتعديل القوائم والعناصر

3. **تنفيذ RelationManagers**
   - إدارة أنواع البلوكات للقالب
   - إدارة البلوكات للصفحة
   - إدارة عناصر القائمة للقائمة

### المرحلة 5: تنفيذ هيكل القوالب والبلوكات

1. **إنشاء هيكل المجلدات**
   ```
   resources/
   ├── views/
   │   ├── blocks/                # البلوكات العامة (Fallback)
   │   │   ├── hero/
   │   │   │   └── default.blade.php
   │   │   ├── features/
   │   │   │   └── default.blade.php
   │   ├── templates/
   │   │   ├── restaurant/        # قالب المطعم
   │   │   │   ├── blocks/
   │   │   │   │   ├── hero/
   │   │   │   │   │   ├── default.blade.php
   │   │   │   │   │   ├── centered.blade.php
   │   │   │   │   │   └── with-video.blade.php
   │   │   │   │   ├── features/
   │   │   │   │   │   └── ...
   │   │   │   ├── page.blade.php
   │   │   │   ├── layout.blade.php
   │   │   ├── agency/           # قالب الوكالة
   │   │   │   ├── blocks/
   │   │   │   │   ├── hero/
   │   │   │   │   │   ├── default.blade.php
   │   │   │   │   │   ├── full-width.blade.php
   │   │   │   │   │   └── ...
   │   │   │   ├── page.blade.php
   │   │   │   ├── layout.blade.php
   ```

2. **إنشاء ملفات القوالب الأساسية**
   - قالب التخطيط العام (layout.blade.php)
   - قالب الصفحة (page.blade.php)

3. **إنشاء ملفات البلوكات**
   - البلوكات العامة في مجلد blocks
   - البلوكات الخاصة بكل قالب في مجلد templates/{template}/blocks
   - الإصدارات المختلفة لكل بلوك

### المرحلة 6: تنفيذ أصول القوالب

1. **إنشاء أصول القوالب**
   - ملفات CSS لكل قالب
   - ملفات JavaScript لكل قالب
   - الصور والأيقونات

2. **تنفيذ CSS الديناميكي**
   - توليد متغيرات CSS من إعدادات القالب
   - تطبيق الألوان والخطوط المخصصة

### المرحلة 7: إنشاء البيانات الأولية

1. **إنشاء Seeders**
   - BlockTypeSeeder - لإنشاء أنواع البلوكات الأساسية
   - TemplateSeeder - لإنشاء القوالب الأساسية وربطها بأنواع البلوكات

2. **تنفيذ البذور**
   - تنفيذ Seeders
   - إنشاء محتوى تجريبي

## تفاصيل تنفيذ نظام البلوكات

### مفهوم البنية الموحدة والعرض المرن

النظام يعتمد على الفصل بين:
1. **بنية البيانات (Data Structure)** - تُعرف مركزياً في جدول `builder_block_types`
2. **طريقة العرض (Presentation)** - تختلف حسب القالب وإصدار العرض المحدد

هذا النهج يسمح بـ:
- توحيد بنية البيانات لكل نوع بلوك
- استخدام نفس البنية في قوالب مختلفة
- تخصيص طريقة العرض حسب القالب وإصدار العرض

### مثال على تنفيذ بلوك Hero

1. **تعريف بنية البلوك في قاعدة البيانات**:
   ```
   نوع البلوك: hero
   Schema:
   - title (text, required)
   - subtitle (text)
   - link1 (link)
   - link2 (link)
   - photo (image)
   ```

2. **ربط البلوك بقالب المطعم مع إصدارات العرض**:
   ```
   الإصدارات:
   - default (افتراضي)
   - centered (موسط)
   - with-video (مع فيديو)
   ```

3. **ربط البلوك بقالب الوكالة مع إصدارات العرض**:
   ```
   الإصدارات:
   - default (افتراضي)
   - full-width (عرض كامل)
   - animated (متحرك)
   ```

4. **إنشاء ملفات العرض**:
   - `blocks/hero/default.blade.php` (العرض العام الافتراضي)
   - `templates/restaurant/blocks/hero/default.blade.php` (العرض الافتراضي لقالب المطعم)
   - `templates/restaurant/blocks/hero/centered.blade.php` (العرض الموسط لقالب المطعم)
   - `templates/agency/blocks/hero/full-width.blade.php` (العرض كامل العرض لقالب الوكالة)

5. **آلية عرض البلوك**:
   - البحث عن ملف العرض المناسب حسب القالب وإصدار العرض
   - الرجوع إلى العرض الافتراضي إذا لم يتوفر العرض المحدد
   - تمرير بيانات البلوك إلى ملف العرض لعرضها

## إرشادات إضافية

### إضافة نوع بلوك جديد

1. أضف سجل جديد في جدول `builder_block_types` مع تحديد schema البلوك
2. اربط نوع البلوك بالقوالب المطلوبة مع تحديد إصدارات العرض
3. أنشئ ملفات العرض المطلوبة في هيكل المجلدات

### إضافة قالب جديد

1. أضف سجل جديد في جدول `builder_templates`
2. اربط القالب بأنواع البلوكات المطلوبة
3. أنشئ مجلد جديد للقالب مع ملفات العرض المطلوبة

### تخصيص عرض البلوكات

1. أنشئ ملف عرض جديد للبلوك في مجلد القالب المطلوب
2. أضف الإصدار الجديد في جدول `builder_template_block_types`

## الخلاصة

يوفر هذا النظام آلية مرنة وقوية لإنشاء وإدارة مواقع ديناميكية مع بلوكات قابلة للتخصيص. يمكن توسيع النظام بسهولة من خلال إضافة أنواع بلوكات جديدة أو قوالب جديدة أو إصدارات عرض جديدة.