# نظام الكور المخصص لمشاريع Laravel + Filament

هذا الملف يشرح باترن الكور المخصص المستخدم في مشاريع Laravel + Filament. هذا النظام يعتمد على هيكلة مخصصة وتوسيعات على Filament تسمح بتطوير سريع ومنظم.

## هيكل المشروع الرئيسي

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
│   │   └── Admin/Helper/       # سمات مساعدة للوحة التحكم
│   └── View/                  # مكونات عرض مخصصة
└── resources/
    └── lang/                  # ملفات الترجمة
```

## نمط الكود المخصص

هذا المشروع يستخدم نمطًا مخصصًا يستفيد من الميزات الحديثة لـ Laravel و Filament مع إضافة تعديلات وتوسيعات تناسب سير العمل الخاص بنا.

### الميزات الرئيسية للنمط

1. **فصل التخصيصات**: مجلد `FilamentCustom` لإبقاء التوسيعات الخاصة منفصلة عن مكونات Filament الأساسية
2. **استخدام الـ Traits**: للمشاركة الفعالة للوظائف المشتركة مثل `SmartResourceTrait`
3. **تنظيم واضح للموارد**: هيكل ثابت لصفحات وموارد Filament
4. **دعم متعدد اللغات**: استخدام ملفات ترجمة مخصصة لكل مورد
5. **فصل المكونات المخصصة**: لسهولة الصيانة والتطوير

## العناصر الأساسية في النمط

### 1. `SmartResourceTrait`

هذه السمة توفر وظائف مساعدة لموارد Filament، وتتضمن:

- `filterPermissions()`: إدارة صلاحيات الموارد
- `getNavigationSortNumber()`: ترتيب العناصر في القائمة
- `getTableRecordUrl()`: تعريف مسار عرض/تعديل السجل
- `resolveDynamicLabel()`: تخصيص العناوين ديناميكيًا

```php
trait SmartResourceTrait {
    public static function filterPermissions(array $skipKeys = [], array $keepKeys = [], array $addKeys = []): array {
        // إدارة متطورة للصلاحيات
    }
    
    public static function getNavigationSortNumber(): int {
        // تحديد ترتيب عناصر القائمة من ملف التكوين
    }
    
    // دوال أخرى مساعدة...
}
```

### 2. مكونات الجدول المخصصة

المشروع يستخدم مكونات مخصصة للجداول لتبسيط تنفيذ تخطيطات وظائف متكررة:

```php
class CreatedDates {
    public static function make(): static {
        return new static();
    }

    public function toggleable(bool $value = true): static {
        $this->toggleable = $value;
        return $this;
    }

    public function getColumns(): array {
        // إرجاع أعمدة تواريخ موحدة (إنشاء، تحديث، حذف)
    }
}
```

### 3. هيكل الموارد

```php
class UserResource extends Resource implements HasShieldPermissions {
    use SmartResourceTrait;
    
    // 1. تعريفات أساسية وإعدادات التصفح
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    // 2. إدارة الصلاحيات
    public static function getPermissionPrefixes(): array {
        return static::filterPermissions(
            skipKeys: ['slug'], 
            keepKeys: [],
        );
    }
    
    // 3. تخصيص النصوص واستخدام الترجمات
    public static function getNavigationGroup(): ?string {
        return __('default/users.navigation_group');
    }
    
    // 4. تعريف نموذج البيانات (Form)
    public static function form(Form $form): Form {
        return $form->schema([
            // مجموعات ومقاطع منظمة
        ]);
    }
    
    // 5. تعريف الجدول (Table)
    public static function table(Table $table): Table {
        return $table->columns([
            // أعمدة مع دعم للفلترة والترتيب
        ])->filters([
            // فلاتر متقدمة
        ])->actions([
            // إجراءات مخصصة
        ]);
    }
    
    // 6. تعريف العلاقات
    public static function getRelations(): array {
        return [
            // علاقات الجداول
        ];
    }
    
    // 7. تعريف الصفحات
    public static function getPages(): array {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
    
    // 8. تعريف infolist للعرض التفصيلي
    public static function infolist(Infolist $infolist): Infolist {
        return $infolist->schema([
            // مكونات عرض التفاصيل
        ]);
    }
}
```

### 4. هيكل الصفحات

```php
class ListUsers extends ListRecords {
    protected static string $resource = UserResource::class;

    // 1. إجراءات رأس الصفحة
    protected function getHeaderActions(): array {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // 2. تعريف التبويبات للتصفية
    public function getTabs(): array {
        return [
            'Active' => Tab::make()
                ->label(__('default/users.tab.Active'))
                ->icon('heroicon-o-users')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('is_active', '=', 1))
                ->badge(static::getModel()::query()->where('is_active', '=', 1)->count())
                ->badgeColor('success'),
            
            // تبويبات أخرى...
        ];
    }
}
```

## أنماط ترميز مخصصة

### 1. استخدام الفواصل النصية

```php
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
```

هذه الفواصل تستخدم لفصل الأقسام المختلفة من الكود لتسهيل القراءة والتصفح.

### 2. تنظيم النماذج (Form)

```php
Group::make()->schema([
    Section::make(__('default/users.card.User_Information'))->schema([
        // حقول مرتبطة منطقيًا
    ])->columns(2),
    
    Section::make(__('default/users.card.Roles'))->schema([
        // حقول مرتبطة منطقيًا
    ])->columns(2),
])->columnSpan(2)
```

- تجميع الحقول في مقاطع منطقية
- استخدام مجموعات لتنظيم التخطيط العام
- استخدام ترجمات للعناوين

### 3. تنظيم الأعمدة (Table)

```php
TextColumn::make('name')
    ->label(__('default/users.name'))
    ->weight(FontWeight::Bold)
    ->searchable(),

// استخدام مكونات مخصصة للأعمدة الشائعة
...CreatedDates::make()->toggleable(true)->getColumns(),
```

## دعم متعدد اللغات

المشروع يستخدم نظام ترجمة منظم مع نمط متسق:

```php
__('default/users.name')  // لملفات الترجمة الخاصة بالمستخدمين
__('default/lang.columns.created_at')  // لملفات الترجمة العامة
```

## نمط إدارة الصلاحيات

```php
public static function getPermissionPrefixes(): array {
    return static::filterPermissions(
        skipKeys: ['slug'],
        keepKeys: [],
    );
}
```

يستخدم `filterPermissions()` من `SmartResourceTrait` لإدارة صلاحيات الموارد بشكل موحد.

## الحقول المخصصة والويدجت

الحقول والأعمدة المخصصة محفوظة في مجلد `FilamentCustom` مثل:

- مكونات النموذج المخصصة في `Form/`
- مكونات الجدول المخصصة في `Table/`
- مكونات رفع الملفات في `UploadFile/`

## عملية تطوير ميزة جديدة

### 1. تطوير النموذج (Model)

```php
// app/Models/Product.php
class Product extends Model {
    use SoftDeletes;
    
    protected $fillable = ['name', 'price', 'description', 'is_active'];
    
    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];
    
    // العلاقات والنطاقات والدوال المساعدة
}
```

### 2. إنشاء المورد (Resource)

```php
// app/Filament/Admin/Resources/ProductResource.php
class ProductResource extends Resource implements HasShieldPermissions {
    use SmartResourceTrait;
    
    protected static ?string $model = Product::class;
    
    // تنفيذ الدوال الأساسية (form, table, getPages, etc.)
}
```

### 3. تنفيذ الصفحات (Pages)

```php
// app/Filament/Admin/Resources/ProductResource/Pages/ListProducts.php
class ListProducts extends ListRecords {
    protected static string $resource = ProductResource::class;
    
    // تنفيذ الدوال المخصصة (getTabs, getHeaderActions, etc.)
}
```

### 4. إضافة الترجمات

```php
// resources/lang/ar/default/products.php
return [
    'ModelLabel' => 'منتج',
    'PluralModelLabel' => 'منتجات',
    'NavigationLabel' => 'المنتجات',
    'navigation_group' => 'المتجر',
    
    // الحقول والأقسام
    'name' => 'الاسم',
    'price' => 'السعر',
    
    // التبويبات
    'tab' => [
        'Active' => 'نشط',
        'Pending' => 'معلق',
        'All' => 'الكل',
    ],
    
    // البطاقات
    'card' => [
        'Product_Information' => 'بيانات المنتج',
    ],
];
```

## أفضل الممارسات

1. **التزم بالهيكل**: اتبع هيكل المجلدات والملفات بدقة
2. **استخدام الترجمات**: دائمًا استخدم ملفات الترجمة عند عرض النصوص
3. **منهجية MCP**: اتبع نمط Model-Controller-Pages كما موضح أعلاه
4. **استخدم السمات المشتركة**: مثل `SmartResourceTrait` لإعادة استخدام الوظائف الشائعة
5. **ابقِ التخصيصات منفصلة**: استخدم `FilamentCustom` لأي مكونات مخصصة
6. **استخدم فواصل الأقسام**: لتحسين قراءة الكود

## قائمة مرجعية لإنشاء مورد جديد

1. [ ] إنشاء النموذج (Model) في `app/Models/`
2. [ ] تعريف ملفات الترجمة في `resources/lang/{locale}/default/`
3. [ ] إنشاء المورد (Resource) في `app/Filament/Admin/Resources/`
4. [ ] تنفيذ صفحات المورد (ListRecords, CreateRecord, EditRecord)
5. [ ] إضافة العلاقات والويدجت إن وجدت
6. [ ] تخصيص الجدول والفلاتر والإجراءات
7. [ ] إضافة التبويبات للتصفية إن لزم الأمر

## ملاحظات إضافية

- استخدم `#@@@...` كفواصل للأقسام الرئيسية
- اتبع تسمية متسقة للمتغيرات والدوال
- استخدم دوال السمات المشتركة لتجنب تكرار الكود
- قم بتنظيم النماذج والجداول في مقاطع منطقية
- اهتم بترتيب القوائم باستخدام خاصية `getNavigationSort()`

## التخصيصات الموجودة

- `CreatedDates`: أعمدة تواريخ الإنشاء والتحديث والحذف
- `IconColumnDef`: تعريفات موحدة لأعمدة الأيقونات
- `ImageColumnDef`: تعريفات موحدة لأعمدة الصور
- `TranslationTextColumn`: أعمدة نصية مع دعم الترجمة

هذا النمط المخصص سيسمح لك بتطوير لوحات تحكم متسقة وسهلة الصيانة وقابلة للتوسعة باستخدام Laravel و Filament.
