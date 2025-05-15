# دليل طريقة العمل الخاصة بالكور

## مقدمة

هذا الدليل يوضح طريقة العمل الأساسية للكور المخصص لمشاريع Laravel + Filament، ويؤكد على أهمية الالتزام بالبنية والأنماط المحددة. الالتزام بهذه الهيكلية يضمن اتساق التطبيق وسهولة صيانته وتوسيعه، ويسمح للمطورين بالعمل بكفاءة عالية.

## الهيكلة الأساسية والالتزام بها

### هيكل المكونات

نظام الكور يعتمد على الهيكل التالي:

```
app/
├── Filament/              # مكونات Filament الأساسية
│   └── Admin/             # مكونات لوحة الإدارة
│       ├── Pages/         # صفحات مستقلة في لوحة الإدارة
│       └── Resources/     # موارد Filament (كل مورد يمثل نموذج)
│           └── {Path}/    # مسار المورد المطابق لمسار النموذج
│               └── {Resource}/   # الموارد نفسها والصفحات المرتبطة
├── FilamentCustom/        # التوسيعات المخصصة لـ Filament
│   ├── Form/              # مكونات النموذج المخصصة
│   ├── Table/             # مكونات الجدول المخصصة
│   ├── UploadFile/        # مكونات رفع الملفات المخصصة
│   └── View/              # مكونات العرض المخصصة
├── Models/                # نماذج Eloquent
│   └── {Path}/            # مسارات تنظيمية للنماذج
├── Traits/                # السمات المشتركة
└── lang/                  # ملفات الترجمة
    └── {locale}/          # اللغات المدعومة
        └── default/       # مجلد الترجمات الأساسي
            └── {path}/    # مسارات تنظيمية للترجمات
```

> **ملاحظة مهمة**: الالتزام بهذه الهيكلية أمر ضروري لضمان توافق المكونات وعمل الأوامر المخصصة بشكل صحيح.

### إنشاء المكونات الجديدة

لإنشاء الموديولات الجديدة، يجب استخدام الأوامر المخصصة التالية:

```bash
# إنشاء نموذج كامل مع المورد وملفات الترجمة
php artisan app:model Path/ModelName --table=table_name --trans --Resource --Lang
```

هذا النهج يضمن تجانس المكونات الجديدة مع النظام الأساسي، ويمنع التناقضات في البنية والتنفيذ.

## العمل مع الترجمات

### 1. النماذج متعددة اللغات

النماذج متعددة اللغات تستخدم `Astrotomic\Translatable` وتحتاج إلى:

1. **نموذج أساسي** يستخدم trait الترجمة:

```php
use Astrotomic\Translatable\Translatable;

class Category extends Model {
    use Translatable;
    
    public $translatedAttributes = ['name', 'description'];
    
    // الحقول غير المترجمة
    protected $fillable = ['slug', 'is_active', 'photo'];
}
```

2. **نموذج ترجمة** يحتوي على الحقول المترجمة:

```php
class CategoryTranslation extends Model {
    protected $fillable = ['name', 'description'];
    public $timestamps = false;
}
```

3. **جدولان في قاعدة البيانات**:
   - الجدول الرئيسي: `categories`
   - جدول الترجمة: `categories_lang`

### 2. عرض حقول الترجمة في واجهة Filament

لعرض حقول الترجمة بشكل صحيح، يجب استخدام المكونات المخصصة في الكور:

```php
use App\Helpers\FilamentAstrotomic\Forms\Components\TranslatableTabs;
use App\Helpers\FilamentAstrotomic\TranslatableTab;
use App\FilamentCustom\Form\Translation\MainInput;

public static function form(Form $form): Form {
    return $form->schema([
        Group::make()->schema([
            // الحقول غير المترجمة
            SlugInput::make('slug'),
            
            // الحقول المترجمة في تبويبات
            TranslatableTabs::make('translations')
                ->availableLocales(config('app.web_add_lang'))
                ->localeTabSchema(fn(TranslatableTab $tab) => [
                    ...MainInput::make()
                        ->setDes(true)  // إضافة حقل الوصف
                        ->setSeoRequired(false)  // إضافة حقول SEO
                        ->getColumns($tab),
                ]),
        ])->columnSpan(2),
        
        Group::make()->schema([
            Section::make()->schema([
                // حقول إضافية غير مترجمة
                ...WebpUploadWithFilter::make()
                    ->setFilterId($filterId)
                    ->setUploadDirectory(static::$uploadDirectory)
                    ->getColumns(),
                    
                Toggle::make('is_active')
                    ->label(__('default/lang.columns.is_active'))
                    ->default(true),
            ]),
        ])->columnSpan(1),
    ])->columns(3);
}
```

> **ملاحظة هامة**: استخدام `MainInput` يوفر مجموعة قياسية من حقول الترجمة (العنوان، الوصف، حقول SEO) التي يمكن تخصيصها باستخدام الوسائط المناسبة.

### 3. جلب وعرض البيانات المترجمة

في واجهة الجدول، استخدم `TranslationTextColumn` لعرض البيانات المترجمة:

```php
use App\FilamentCustom\Table\TranslationTextColumn;

public static function table(Table $table): Table {
    return $table->columns([
        // عمود لعرض الحقل المترجم بحسب اللغة الحالية
        TranslationTextColumn::make('name')
            ->label(__('default/categories.name'))
            ->searchable()
            ->sortable(),
            
        // أعمدة أخرى غير مترجمة
        IconColumn::make('is_active')
            ->label(__('default/lang.columns.is_active'))
            ->boolean(),
    ]);
}
```

## ربط الموديولات ببعضها

### 1. العلاقات بين النماذج

لربط الموديولات ببعضها، يجب تعريف العلاقات بشكل صحيح في النماذج:

```php
// علاقة مع النموذج نفسه (تسلسل هرمي)
public function parent() {
    return $this->belongsTo(Category::class, 'parent_id');
}

public function children() {
    return $this->hasMany(Category::class, 'parent_id');
}

// علاقة مع نموذج آخر
public function products() {
    return $this->hasMany(Product::class);
}
```

### 2. عرض العلاقات في واجهة Filament

لعرض العلاقات في واجهات Filament، استخدم RelationManagers:

```php
public static function getRelations(): array {
    return [
        RelationManagers\ProductsRelationManager::class,
    ];
}
```

ثم قم بإنشاء RelationManager لكل علاقة:

```php
namespace App\Filament\Admin\Resources\CategoryResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;

class ProductsRelationManager extends RelationManager {
    protected static string $relationship = 'products';
    
    // تعريف شكل الجدول والنموذج للعلاقة
}
```

### 3. الاختيار من العلاقات

لعرض حقول الاختيار من العلاقات في النماذج:

```php
use Filament\Forms\Components\Select;

Select::make('parent_id')
    ->label(__('default/categories.parent'))
    ->relationship('parent', 'name')
    ->searchable()
    ->preload()
    ->nullable(),
    
Select::make('category_id')
    ->label(__('default/products.category'))
    ->relationship('category', 'name')
    ->searchable()
    ->preload()
    ->required(),
```

## استخدام المكونات المخصصة

### 1. مكونات رفع الملفات

استخدم `WebpUploadWithFilter` لرفع الصور وتطبيق فلاتر عليها:

```php
use App\FilamentCustom\UploadFile\WebpUploadWithFilter;

WebpUploadWithFilter::make()
    ->setFilterId($filterId)
    ->setUploadDirectory('categories')
    ->setRequiredUpload(false)
    ->setCanChangeFilter(true)
    ->getColumns(),
```

### 2. مكونات الجدول المخصصة

استخدم مكونات الجدول المخصصة لتوحيد مظهر الجداول:

```php
use App\FilamentCustom\Table\CreatedDates;
use App\FilamentCustom\Table\ImageColumnDef;

// عمود صورة موحد
ImageColumnDef::make('photo')->width(60)->height(40),

// أعمدة التواريخ (إنشاء، تعديل، حذف)
...CreatedDates::make()->toggleable(true)->getColumns(),
```

### 3. مكونات النموذج المخصصة

استخدم مكونات النموذج المخصصة لتوحيد واجهات الإدخال:

```php
use App\FilamentCustom\Form\Inputs\SlugInput;

// حقل Slug موحد
SlugInput::make('slug'),
```

## استخدام الصلاحيات

لتطبيق الصلاحيات بشكل صحيح:

```php
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class CategoryResource extends Resource implements HasShieldPermissions {
    use SmartResourceTrait;
    
    // تحديد الصلاحيات المطلوبة للمورد
    public static function getPermissionPrefixes(): array {
        return static::filterPermissions(
            skipKeys: ['view'],  // استبعاد صلاحيات محددة
            keepKeys: ['cat', 'sort'],  // الاحتفاظ بصلاحيات محددة
            addKeys: [
                'keys' => ['publish', 'unpublish'],  // إضافة صلاحيات مخصصة
                'placeIn' => 'after',
            ],
        );
    }
}
```

## استخدام ملفات الترجمة

يجب وضع ملفات الترجمة في المسار الصحيح `lang/{locale}/default/{path}` واستخدامها بشكل متسق:

```php
// استخدام الترجمات للواجهة
public static function getNavigationGroup(): ?string {
    return __('default/categories.navigation_group');
}

// استخدام الترجمات للحقول
TextInput::make('name')
    ->label(__('default/categories.name'))
    ->required(),
```

## ملخص النقاط الرئيسية

1. **التزم بهيكل المشروع** المحدد لضمان التوافق
2. **استخدم الأوامر المخصصة** لإنشاء المكونات الجديدة
3. **استخدم الترجمات** بشكل صحيح وموحد
4. **استخدم المكونات المخصصة** من `FilamentCustom` بدلاً من إعادة اختراع العجلة
5. **أنشئ العلاقات** بين النماذج بشكل صحيح
6. **طبق نظام الصلاحيات** باستخدام `SmartResourceTrait`

الالتزام بهذه المبادئ التوجيهية سيضمن انسجام التطبيق واتساقه، وسيسهل التطوير والصيانة، وسيوفر تجربة مستخدم متسقة عبر مختلف أجزاء النظام.
