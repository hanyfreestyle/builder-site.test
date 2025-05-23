# هيكل المشروع الرئيسي

## نظرة عامة على هيكل المشروع

مشروع Laravel + Filament المخصص يتبع هيكلًا منظمًا يسهل العثور على الملفات والمكونات. الهيكل يوسع بنية Laravel القياسية مع إضافة مجلدات وتنظيم مخصص للتعامل مع مكونات Filament والتوسيعات المخصصة.

```
project/
├── app/
│   ├── Enums/                 # تعريفات الثوابت والقيم المحددة
│   ├── Filament/              # مكونات Filament الأساسية
│   │   ├── Admin/
│   │   │   ├── Pages/         # الصفحات المخصصة للوحة التحكم
│   │   │   └── Resources/     # موارد Filament
│   │   │       └── {Model}Resource/
│   │   │           ├── Pages/
│   │   │           └── {Model}Resource.php
│   │   └── Plugins/           # إضافات Filament المخصصة
│   ├── FilamentCustom/        # التوسيعات والتخصيصات لـ Filament
│   │   ├── Form/              # مكونات النموذج المخصصة
│   │   ├── Setting/           # إعدادات مخصصة
│   │   ├── Table/             # مكونات الجدول المخصصة
│   │   ├── UploadFile/        # مكونات رفع الملفات المخصصة
│   │   └── View/              # مكونات العرض المخصصة
│   ├── Helpers/               # دوال مساعدة للمشروع
│   ├── Http/
│   │   └── Controllers/       # وحدات التحكم
│   ├── Models/                # نماذج Eloquent
│   │   └── Data/              # نماذج بيانات إضافية 
│   ├── Policies/              # سياسات الصلاحيات
│   ├── Providers/             # مزودي الخدمات
│   ├── Traits/                # السمات المشتركة المستخدمة في المشروع
│   │   └── Admin/Helper/      # سمات مساعدة للوحة التحكم
│   └── View/                  # مكونات عرض مخصصة
├── config/                    # ملفات الإعداد
├── database/                  # الترحيلات والبذور
├── lang/                      # ملفات الترجمة
│   └── {locale}/default/      # ملفات الترجمة المنظمة حسب المورد
├── public/                    # الملفات العامة
├── resources/                 # الموارد الأمامية
└── routes/                    # تعريفات المسارات
```

## الأقسام الرئيسية

### 1. مجلد `Filament`

يحتوي على المكونات الأساسية لـ Filament حسب الهيكل القياسي:

```
app/Filament/
├── Admin/                     # لوحة تحكم المشرف
│   ├── Pages/                 # صفحات مخصصة (Dashboard, Settings, etc.)
│   └── Resources/             # موارد (UserResource, ProductResource, etc.)
│       └── {Model}Resource/   # ملفات خاصة بكل مورد
│           ├── Pages/         # صفحات المورد (List, Create, Edit, etc.)
│           └── RelationManagers/ # مديري العلاقات
└── Plugins/                   # إضافات Filament المخصصة
```

### 2. مجلد `FilamentCustom`

هذا المجلد هو امتداد مخصص لـ Filament، ويحتوي على مكونات وتوسيعات مخصصة:

```
app/FilamentCustom/
├── Form/                      # مكونات النموذج المخصصة
├── Setting/                   # مكونات الإعدادات المخصصة
├── Table/                     # مكونات الجدول المخصصة (CreatedDates, IconColumnDef, etc.)
├── UploadFile/                # مكونات رفع الملفات المخصصة
└── View/                      # مكونات العرض المخصصة
```

### 3. مجلد `Traits`

يحتوي على السمات المشتركة المستخدمة في المشروع:

```
app/Traits/
└── Admin/Helper/              # سمات مساعدة للوحة التحكم
    └── SmartResourceTrait.php # سمة أساسية للموارد
```

### 4. مجلد `Models`

يحتوي على نماذج Eloquent:

```
app/Models/
├── User.php                   # نموذج المستخدم
└── Data/                      # نماذج البيانات الإضافية
```

### 5. مجلد `lang`

يحتوي على ملفات الترجمة منظمة بطريقة خاصة:

```
lang/
└── {locale}/                  # رمز اللغة (ar, en, etc.)
    └── default/               # مجلد الترجمات الافتراضية
        ├── lang.php           # ترجمات عامة
        ├── users.php          # ترجمات خاصة بالمستخدمين
        └── ...                # ملفات أخرى حسب المورد
```

## تفاصيل المكونات الرئيسية

### 1. موارد Filament

كل مورد يتبع هيكلًا موحدًا:

```
app/Filament/Admin/Resources/{Model}Resource/
├── Pages/
│   ├── Create{Model}.php      # صفحة إنشاء سجل جديد
│   ├── Edit{Model}.php        # صفحة تعديل سجل
│   └── List{Model}s.php       # صفحة عرض قائمة السجلات
└── {Model}Resource.php        # تعريف المورد الرئيسي
```

### 2. مكونات FilamentCustom

المكونات المخصصة مثل `CreatedDates` تستخدم لتوحيد وتبسيط العمليات المتكررة:

```php
// app/FilamentCustom/Table/CreatedDates.php
class CreatedDates {
    public static function make(): static {
        return new static();
    }

    public function getColumns(): array {
        // تعريف أعمدة التواريخ الموحدة
    }
}
```

### 3. السمات المشتركة

`SmartResourceTrait` هي سمة أساسية توفر وظائف مشتركة للموارد:

```php
// app/Traits/Admin/Helper/SmartResourceTrait.php
trait SmartResourceTrait {
    // دوال لإدارة الصلاحيات والتنقل وغيرها
}
```

## الميزات الرئيسية للهيكل

1. **فصل المكونات الأساسية والمخصصة**: مجلد `Filament` للمكونات القياسية ومجلد `FilamentCustom` للتوسيعات المخصصة
2. **تنظيم حسب الوظيفة**: فصل المكونات حسب وظيفتها (Form, Table, etc.)
3. **إعادة استخدام الكود**: استخدام السمات لتسهيل مشاركة الوظائف المشتركة
4. **تنظيم الترجمات**: هيكل منظم للترجمات ضمن مجلد `lang`
5. **هيكل مورد موحد**: نمط موحد لجميع موارد Filament

## أفضل الممارسات للتعامل مع هيكل المشروع

1. **احترام المسارات المحددة**: وضع كل ملف في المكان المخصص له حسب الهيكل
2. **الحفاظ على التسلسل الهرمي**: الالتزام بالتسلسل الهرمي للمجلدات
3. **المتابعة المستمرة للتغييرات**: تحديث الهيكل عند إضافة أنواع جديدة من المكونات
4. **استخدام الأسماء الوصفية**: تسمية الملفات والمجلدات بأسماء وصفية واضحة
5. **تجنب تكرار الوظائف**: الاستفادة من المكونات المخصصة لتجنب تكرار الكود

## ملاحظات مهمة

- مجلد `FilamentCustom` هو إضافة غير قياسية لـ Laravel + Filament، ويمثل جوهر نظام الكور المخصص
- يجب الالتزام بتنظيم الترجمات في مجلد `lang/{locale}/default/` لضمان عمل نظام الترجمة بشكل صحيح
- مجلد `Models/Data` يستخدم لفصل نماذج البيانات الأساسية عن الوظائف الإضافية
