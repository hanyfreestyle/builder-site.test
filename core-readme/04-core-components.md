# العناصر الأساسية

## نظرة عامة على المكونات الأساسية

نظام الكور المخصص يعتمد على مجموعة من المكونات الأساسية التي تشكل العمود الفقري للنظام. هذه المكونات مصممة لتوفير أساس متين للتطوير وتبسيط العمليات المتكررة وضمان اتساق التنفيذ عبر المشروع.

## 1. SmartResourceTrait

### نظرة عامة
`SmartResourceTrait` هي سمة أساسية توفر وظائف مشتركة لجميع موارد Filament في النظام. تهدف إلى توحيد السلوك وتجنب تكرار الكود.

### الملف
`app/Traits/Admin/Helper/SmartResourceTrait.php`

### الوظائف الرئيسية

#### 1.1 إدارة الصلاحيات

```php
public static function filterPermissions(array $skipKeys = [], array $keepKeys = [], array $addKeys = []): array {
    // 1. تحميل الصلاحيات من الكونفيج
    $defaultPermissions = config('filament-shield.permission_prefixes.resource', []);

    // 2. علاقات الحذف بناءً على مفاتيح skipKeys
    $relations = [
        'delete' => ['force_delete', 'force_delete_any'],
        'restore' => ['restore', 'restore_any'],
        'slug' => ['update_slug'],
        'view' => ['view'],
        'view_any' => ['view_any'],
    ];

    // 3. صلاحيات دايمًا بتتشال، إلا لو ذكرت في keepKeys
    $alwaysRemove = [
        'replicate' => ['replicate'],
        'sort' => ['reorder'],
        'publish' => ['publish'],
        'cat' => ['view_any_category'],
    ];

    // 4. اجمع صلاحيات الحذف من skipKeys
    $toRemoveByKey = collect($skipKeys)
        ->flatMap(fn($key) => $relations[$key] ?? [$key])
        ->toArray();

    // 5. احذف الافتراضيات إلا لو المفتاح موجود في keepKeys
    $toRemoveDefaults = collect($alwaysRemove)
        ->except($keepKeys)
        ->flatten()
        ->toArray();

    // 6. الدمج النهائي للصلاحيات اللي هتتحذف
    $toRemove = array_unique(array_merge($toRemoveByKey, $toRemoveDefaults));

    // 7. فلترة الصلاحيات
    $filtered = array_values(array_filter(
        $defaultPermissions,
        fn($permission) => !in_array($permission, $toRemove)
    ));

    // 8. دمج الإضافات حسب المكان
    if (!empty($addKeys['keys'])) {
        $insertion = $addKeys['keys'];
        $position = $addKeys['placeIn'] ?? 'after';

        $filtered = match ($position) {
            'before' => array_merge($insertion, $filtered),
            'after' => array_merge($filtered, $insertion),
            default => $filtered,
        };
    }

    return $filtered;
}
```

**الغرض**: تبسيط إدارة صلاحيات الموارد مع توفير مرونة في تخصيص الصلاحيات.

#### 1.2 ترتيب عناصر التنقل

```php
public static function getNavigationSortNumber(): int {
    $map = client_config('filament-navigation-map', true);
    $counter = 0;
    foreach ($map as $group => $resources) {
        foreach ($resources as $resourceClass) {
            if ($resourceClass === static::class) {
                return $counter;
            }
            $counter++;
        }
    }
    return 9901;
}
```

**الغرض**: تحديد ترتيب ظهور الموارد في قائمة التنقل بطريقة منظمة ومركزية.

#### 1.3 تخصيص العناوين ديناميكيًا

```php
public static function resolveDynamicLabel(string $label): ?string {
    $pathCheck = str(request()->path())->contains('shield/roles');

    if ($pathCheck) {
        $current = class_basename(static::class);

        return match (true) {
            $current === 'DefPhotoResource' => 'ادارة اعدادات الموقع',
            $current === 'RoleResource' => 'صلاحيات النظام',
            $current === 'BlogPostResource' => 'ادارة المقالات',
            default => $label,
        };
    }

    return $label;
}
```

**الغرض**: تعديل العناوين ديناميكيًا حسب السياق، خاصة في صفحات إدارة الصلاحيات.

#### 1.4 معالجة مسارات السجلات

```php
public static function getTableRecordUrl($record): ?string {
    return static::getUrl('edit', ['record' => $record->getKey()]);
}
```

**الغرض**: توحيد سلوك الانتقال عند النقر على سجل في الجدول.

## 2. مكونات الجدول المخصصة (Table Components)

### 2.1 CreatedDates

#### الملف
`app/FilamentCustom/Table/CreatedDates.php`

#### الوظيفة

```php
class CreatedDates {
    protected bool $toggleable = true;

    public static function make(): static {
        return new static();
    }

    public function toggleable(bool $value = true): static {
        $this->toggleable = $value;
        return $this;
    }

    public function getColumns(): array {
        return [
            TextColumn::make('created_at')
                ->label(__('default/lang.columns.created_at'))
                ->date()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: $this->toggleable),

            TextColumn::make('updated_at')
                ->label(__('default/lang.columns.updated_at'))
                ->date()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: $this->toggleable),

            TextColumn::make('deleted_at')
                ->label(__('default/lang.columns.deleted_at'))
                ->date()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: $this->toggleable),
        ];
    }
}
```

**الغرض**: توحيد طريقة عرض أعمدة التواريخ (إنشاء، تحديث، حذف) في جميع الجداول.

### 2.2 IconColumnDef

#### الملف
`app/FilamentCustom/Table/IconColumnDef.php`

#### الوظيفة
توفير تعريفات موحدة لأعمدة الأيقونات مع إعدادات مشتركة.

### 2.3 ImageColumnDef

#### الملف
`app/FilamentCustom/Table/ImageColumnDef.php`

#### الوظيفة
توفير تعريفات موحدة لأعمدة الصور مع إعدادات مشتركة.

### 2.4 TranslationTextColumn

#### الملف
`app/FilamentCustom/Table/TranslationTextColumn.php`

#### الوظيفة
توفير عمود نصي مع دعم للترجمة التلقائية.

## 3. مكونات النموذج المخصصة (Form Components)

### 3.1 مكونات الإدخال المخصصة

#### الملفات
موجودة في مجلد `app/FilamentCustom/Form/`

#### الوظيفة
توفير حقول إدخال مخصصة بسلوك وشكل موحد عبر المشروع.

## 4. مكونات رفع الملفات المخصصة

### 4.1 مكونات رفع الصور والملفات

#### الملفات
موجودة في مجلد `app/FilamentCustom/UploadFile/`

#### الوظيفة
توفير واجهة موحدة لرفع ومعالجة الملفات والصور مع ميزات مثل تغيير الحجم والضغط.

## 5. نمط الصفحات (Pages Pattern)

### 5.1 صفحة القائمة النموذجية

```php
class ListUsers extends ListRecords {
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array {
        return [
            'Active' => Tab::make()
                ->label(__('default/users.tab.Active'))
                ->icon('heroicon-o-users')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('is_active', '=', 1)->where('is_archived', '=', 0))
                ->badge(static::getModel()::query()->where('is_active', '=', 1)->where('is_archived', '=', 0)->count())
                ->badgeColor('success'),

            'Pending' => Tab::make()
                ->label(__('default/users.tab.Pending'))
                ->icon('heroicon-o-lock-closed')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('is_active', '=', 0)->where('is_archived', '=', 0))
                ->badge(static::getModel()::query()->where('is_active', '=', 0)->where('is_archived', '=', 0)->count())
                ->badgeColor('warning'),

            'All' => Tab::make()
                ->label(__('default/users.tab.all'))
                ->badge(static::getModel()::query()->count()),
        ];
    }
}
```

**الغرض**: توفير هيكل موحد لصفحات القائمة مع دعم للتبويبات والبحث والتصفية.

### 5.2 صفحات الإنشاء والتعديل

```php
class CreateUser extends CreateRecord {
    protected static string $resource = UserResource::class;
}

class EditUser extends EditRecord {
    protected static string $resource = UserResource::class;
    
    protected function mutateFormDataBeforeSave(array $data): array {
        // معالجة البيانات قبل الحفظ
        return $data;
    }
}
```

**الغرض**: توفير هيكل موحد لصفحات الإنشاء والتعديل مع إمكانية تخصيص معالجة البيانات.

## 6. نمط المورد (Resource Pattern)

### 6.1 هيكل المورد النموذجي

```php
class UserResource extends Resource implements HasShieldPermissions {
    use SmartResourceTrait;

    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';

    // 1. إدارة الصلاحيات
    public static function getPermissionPrefixes(): array {
        return static::filterPermissions(
            skipKeys: ['slug'],
            keepKeys: [],
        );
    }

    // 2. تخصيص الترجمة والتنقل
    public static function getNavigationGroup(): ?string {
        return __('default/users.navigation_group');
    }

    public static function getModelLabel(): string {
        return __('default/users.ModelLabel');
    }

    public static function getPluralModelLabel(): string {
        return __('default/users.PluralModelLabel');
    }

    public static function getNavigationLabel(): string {
        return __('default/users.NavigationLabel');
    }

    // 3. تعريف النموذج (Form)
    public static function form(Form $form): Form {
        // ...
    }

    // 4. تعريف الجدول (Table)
    public static function table(Table $table): Table {
        // ...
    }

    // 5. تعريف العلاقات
    public static function getRelations(): array {
        // ...
    }

    // 6. تعريف الصفحات
    public static function getPages(): array {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    // 7. تعريف قائمة المعلومات
    public static function infolist(Infolist $infolist): Infolist {
        // ...
    }
}
```

**الغرض**: توفير هيكل موحد وشامل لتعريف موارد Filament.

## 7. نظام الترجمة المخصص

### 7.1 هيكل ملفات الترجمة

```
lang/
└── {locale}/
    └── default/
        ├── lang.php       # ترجمات عامة
        ├── users.php      # ترجمات خاصة بالمستخدمين
        └── products.php   # ترجمات خاصة بالمنتجات
```

### 7.2 مثال على ملف ترجمة

```php
// lang/ar/default/users.php
return [
    'ModelLabel' => 'مستخدم',
    'PluralModelLabel' => 'المستخدمين',
    'NavigationLabel' => 'إدارة المستخدمين',
    'navigation_group' => 'إدارة النظام',
    
    'name' => 'الاسم',
    'email' => 'البريد الإلكتروني',
    'password' => 'كلمة المرور',
    'phone' => 'رقم الهاتف',
    'is_active' => 'مفعل',
    'roles' => 'الأدوار',
    
    'card' => [
        'User_Information' => 'بيانات المستخدم',
        'Roles' => 'الأدوار والصلاحيات',
    ],
    
    'tab' => [
        'Active' => 'نشط',
        'Pending' => 'معلق',
        'Archived' => 'مؤرشف',
        'all' => 'الكل',
    ],
];
```

**الغرض**: توفير نظام ترجمة منظم ومرن يدعم تعدد اللغات في المشروع.

## 8. معالجة الصلاحيات والأدوار

### 8.1 تكامل مع نظام Shield

```php
class UserResource extends Resource implements HasShieldPermissions {
    // ...
    
    public static function getPermissionPrefixes(): array {
        return static::filterPermissions(
            skipKeys: ['slug'],
            keepKeys: [],
        );
    }
}
```

**الغرض**: تكامل مع مكتبة `bezhansalleh/filament-shield` لإدارة الصلاحيات بطريقة مبسطة.

### 8.2 تنفيذ وتطبيق الصلاحيات

```php
// استخدام Spatie Permission في النماذج
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasAvatar, FilamentUser {
    use HasRoles, HasPanelShield;
    
    // ...
}
```

**الغرض**: تطبيق نظام صلاحيات متكامل يسمح بإدارة دقيقة للوصول إلى موارد النظام.

## 9. الدوال المساعدة العامة

### 9.1 دالة rtlCell

```php
function rtlCell(string $lang = 'ar'): array {
    // تعديل خصائص الخلية للغات RTL
    return app()->getLocale() === $lang
        ? ['class' => 'filament-tables-text-column text-right']
        : [];
}
```

**الغرض**: تسهيل تعامل الجداول مع اللغات التي تكتب من اليمين إلى اليسار (RTL).

### 9.2 دالة client_config

```php
function client_config(string $key, bool $returnDefault = false, mixed $default = null): mixed {
    // الحصول على إعدادات العميل من التخزين أو الملف
    // ...
}
```

**الغرض**: توفير طريقة مرنة للوصول إلى إعدادات العميل المخزنة في مكان مركزي.

## 10. مكونات العرض المخصصة

### 10.1 مكونات العرض

#### الملفات
موجودة في مجلد `app/FilamentCustom/View/`

#### الوظيفة
توفير مكونات عرض مخصصة لتحسين تجربة المستخدم وتوحيد شكل الواجهة.

## 11. العلاقة بين المكونات

### 11.1 العلاقة بين المورد والنموذج

```php
class ProductResource extends Resource implements HasShieldPermissions {
    use SmartResourceTrait;

    protected static ?string $model = Product::class;
    
    // ...
}
```

### 11.2 العلاقة بين المورد والصفحات

```php
public static function getPages(): array {
    return [
        'index' => Pages\ListProducts::route('/'),
        'create' => Pages\CreateProduct::route('/create'),
        'edit' => Pages\EditProduct::route('/{record}/edit'),
    ];
}
```

### 11.3 العلاقة بين المكونات المخصصة والموارد

```php
public static function table(Table $table): Table {
    return $table->columns([
        // استخدام مكونات مخصصة
        ...CreatedDates::make()->toggleable(true)->getColumns(),
    ]);
}
```

**الغرض**: تكامل المكونات المختلفة لإنشاء نظام متماسك وسهل الصيانة.

## 12. توسيع النظام

النظام مصمم ليكون قابلًا للتوسعة بسهولة من خلال:

1. **إضافة مكونات مخصصة جديدة**: في المجلدات المناسبة ضمن `FilamentCustom`
2. **توسيع السمات الحالية**: مثل `SmartResourceTrait`
3. **إضافة أنماط جديدة**: للتعامل مع حالات خاصة
4. **تخصيص الإعدادات**: من خلال نظام التكوين المركزي

## الخلاصة

تمثل هذه المكونات الأساسية العمود الفقري لنظام الكور المخصص. من خلال الاستفادة من هذه المكونات، يمكن تطوير مشاريع Laravel + Filament بطريقة متسقة وفعالة ومرنة. تم تصميم كل مكون ليحل مشكلة محددة ويتكامل بسلاسة مع بقية النظام، مما يعزز قابلية الصيانة وإعادة الاستخدام.
