# التعريب ودعم اللغات

## نظرة عامة على نظام الترجمة

نظام الكور المخصص يوفر دعمًا شاملًا لتعدد اللغات باستخدام نهج منظم للترجمات. يعتمد النظام على آلية الترجمة في Laravel مع إضافة تنظيم مخصص لملفات الترجمة وسير عمل محدد لضمان تجربة متعددة اللغات سلسة.

## المكتبات المستخدمة للتعريب

### 1. مكتبة Laravel Localization

```php
// composer.json
"mcamara/laravel-localization": "^2.3"
```

توفر هذه المكتبة دعمًا للمسارات متعددة اللغات والتبديل السلس بين اللغات.

### 2. مكتبة Filament Language Switch

```php
// composer.json
"bezhansalleh/filament-language-switch": "^3.1"
```

تضيف هذه المكتبة زر تبديل اللغة إلى واجهة Filament لتسهيل التنقل بين اللغات المختلفة.

### 3. مكتبة Laravel Translatable

```php
// composer.json
"astrotomic/laravel-translatable": "^11.15"
```

تستخدم هذه المكتبة لدعم المحتوى متعدد اللغات في قاعدة البيانات.

## هيكل ملفات الترجمة

يستخدم النظام هيكلًا منظمًا لملفات الترجمة:

```
lang/
└── {locale}/
    └── default/
        ├── lang.php         # ترجمات عامة للنظام
        ├── users.php        # ترجمات خاصة بالمستخدمين
        ├── products.php     # ترجمات خاصة بالمنتجات
        └── ...              # ملفات أخرى حسب الموارد
```

### 1. تنظيم الترجمات حسب المورد

كل مورد (Resource) له ملف ترجمة خاص به يحتوي على جميع النصوص المتعلقة بهذا المورد.

### 2. تنظيم الترجمات العامة

الترجمات العامة التي تستخدم في أكثر من مكان توجد في ملف `lang.php`.

## أمثلة على ملفات الترجمة

### 1. ملف الترجمة العامة (lang.php)

```php
// lang/ar/default/lang.php
return [
    'settings' => [
        'NavigationGroup' => 'الإعدادات',
    ],
    
    'columns' => [
        'id' => 'الرقم',
        'name' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'created_at' => 'تاريخ الإنشاء',
        'updated_at' => 'تاريخ التحديث',
        'deleted_at' => 'تاريخ الحذف',
        'actions' => 'الإجراءات',
        'is_active' => 'مفعل',
        'email_verified_at' => 'تاريخ تأكيد البريد',
    ],
    
    'buttons' => [
        'save' => 'حفظ',
        'cancel' => 'إلغاء',
        'create' => 'إنشاء',
        'edit' => 'تعديل',
        'delete' => 'حذف',
    ],
    
    'messages' => [
        'saved' => 'تم الحفظ بنجاح',
        'deleted' => 'تم الحذف بنجاح',
        'error' => 'حدث خطأ ما',
    ],
];
```

### 2. ملف ترجمة المستخدمين (users.php)

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

### 3. ملف ترجمة المنتجات (products.php)

```php
// lang/ar/default/products.php
return [
    'ModelLabel' => 'منتج',
    'PluralModelLabel' => 'المنتجات',
    'NavigationLabel' => 'إدارة المنتجات',
    'navigation_group' => 'المتجر',
    
    'name' => 'اسم المنتج',
    'price' => 'السعر',
    'description' => 'الوصف',
    'is_active' => 'متاح للبيع',
    
    'card' => [
        'Product_Information' => 'بيانات المنتج',
        'Pricing' => 'التسعير',
        'Media' => 'الصور والملفات',
    ],
    
    'tab' => [
        'Active' => 'متاح',
        'Inactive' => 'غير متاح',
        'all' => 'الكل',
    ],
];
```

## استخدام الترجمات في النظام

### 1. ترجمة الموارد في Filament

```php
class UserResource extends Resource implements HasShieldPermissions {
    use SmartResourceTrait;
    
    // ...
    
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
}
```

### 2. ترجمة حقول النموذج (Form)

```php
public static function form(Form $form): Form {
    return $form->schema([
        Group::make()->schema([
            Section::make(__('default/users.card.User_Information'))->schema([
                TextInput::make('name')
                    ->label(__('default/users.name'))
                    ->required(),
                    
                TextInput::make('email')
                    ->label(__('default/users.email'))
                    ->email()
                    ->required(),
                
                TextInput::make('password')
                    ->label(__('default/users.password'))
                    ->password(),
            ])->columns(2),
        ])->columnSpan(2),
    ])->columns(3);
}
```

### 3. ترجمة أعمدة الجدول (Table)

```php
public static function table(Table $table): Table {
    return $table->columns([
        TextColumn::make('name')
            ->label(__('default/users.name'))
            ->searchable(),
            
        TextColumn::make('email')
            ->label(__('default/users.email'))
            ->searchable(),
            
        IconColumn::make('is_active')
            ->label(__('default/users.is_active'))
            ->boolean(),
            
        TextColumn::make('created_at')
            ->label(__('default/lang.columns.created_at'))
            ->date()
            ->sortable(),
    ]);
}
```

### 4. ترجمة التبويبات (Tabs)

```php
public function getTabs(): array {
    return [
        'Active' => Tab::make()
            ->label(__('default/users.tab.Active'))
            ->icon('heroicon-o-users')
            ->modifyQueryUsing(fn(Builder $query) => $query->where('is_active', '=', 1)),
            
        'Pending' => Tab::make()
            ->label(__('default/users.tab.Pending'))
            ->icon('heroicon-o-lock-closed')
            ->modifyQueryUsing(fn(Builder $query) => $query->where('is_active', '=', 0)),
    ];
}
```

## دعم اتجاهات القراءة RTL و LTR

يدعم النظام تلقائيًا اتجاهات القراءة المختلفة (من اليمين إلى اليسار ومن اليسار إلى اليمين) استنادًا إلى اللغة الحالية.

### 1. تكوين اتجاه القراءة في Filament

```php
// config/filament.php
'direction' => 'auto', // يتم تحديد الاتجاه تلقائيًا بناءً على اللغة
```

### 2. دالة rtlCell لتنسيق خلايا الجدول

```php
function rtlCell(string $lang = 'ar'): array {
    return app()->getLocale() === $lang
        ? ['class' => 'filament-tables-text-column text-right']
        : [];
}

// استخدام الدالة في تعريف الجدول
TextColumn::make('phone')
    ->label(__('default/users.phone'))
    ->extraCellAttributes(fn() => rtlCell('en')) // اتجاه من اليسار إلى اليمين للأرقام
```

## المحتوى متعدد اللغات في قاعدة البيانات

باستخدام مكتبة `astrotomic/laravel-translatable`، يمكن تخزين البيانات بلغات متعددة في قاعدة البيانات.

### 1. تكوين النموذج للترجمة

```php
// app/Models/Product.php
use Astrotomic\Translatable\Translatable;

class Product extends Model {
    use Translatable;
    
    public $translatedAttributes = [
        'name',
        'description',
        'meta_title',
        'meta_description',
    ];
    
    // ...
}

// app/Models/ProductTranslation.php
class ProductTranslation extends Model {
    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'description',
        'meta_title',
        'meta_description',
    ];
}
```

### 2. الترحيلات لجداول الترجمة

```php
// database/migrations/create_products_table.php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->decimal('price', 10, 2);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    $table->softDeletes();
});

// database/migrations/create_product_translations_table.php
Schema::create('product_translations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    $table->string('locale')->index();
    $table->string('name');
    $table->text('description')->nullable();
    $table->string('meta_title')->nullable();
    $table->text('meta_description')->nullable();
    
    $table->unique(['product_id', 'locale']);
});
```

### 3. استخدام البيانات المترجمة في Filament

```php
public static function form(Form $form): Form {
    $activeLocales = LaravelLocalization::getSupportedLocales();
    $tabs = [];
    
    // إنشاء تبويب لكل لغة
    foreach ($activeLocales as $locale => $properties) {
        $tabs[$locale] = Tab::make($properties['native'])
            ->schema([
                TextInput::make($locale . '.name')
                    ->label(__('default/products.name'))
                    ->required(),
                    
                RichEditor::make($locale . '.description')
                    ->label(__('default/products.description')),
                    
                TextInput::make($locale . '.meta_title')
                    ->label(__('default/products.meta_title')),
                    
                Textarea::make($locale . '.meta_description')
                    ->label(__('default/products.meta_description')),
            ]);
    }
    
    // إضافة التبويب العام للبيانات غير المترجمة
    $tabs['general'] = Tab::make(__('default/lang.general'))
        ->schema([
            TextInput::make('price')
                ->label(__('default/products.price'))
                ->numeric()
                ->required(),
                
            Toggle::make('is_active')
                ->label(__('default/products.is_active')),
        ]);
    
    return $form->schema([
        Tabs::make('Translations')
            ->tabs($tabs),
    ]);
}
```

## تكوين تعدد اللغات في Laravel

### 1. تكوين مكتبة mcamara/laravel-localization

```php
// config/laravellocalization.php
return [
    'supportedLocales' => [
        'ar' => [
            'name' => 'Arabic',
            'script' => 'Arab',
            'native' => 'العربية',
            'dir' => 'rtl',
        ],
        'en' => [
            'name' => 'English',
            'script' => 'Latn',
            'native' => 'English',
            'dir' => 'ltr',
        ],
    ],
    
    'useAcceptLanguageHeader' => true,
    'hideDefaultLocaleInURL' => false,
    'localesOrder' => [],
    'localesMapping' => [],
    'utf8suffix' => env('LARAVELLOCALIZATION_UTF8SUFFIX', '.UTF-8'),
    'urlsIgnored' => ['/api/*', '/admin/*', '/images/*', '/js/*', '/css/*'],
];
```

### 2. تكوين مكتبة Filament Language Switch

```php
// config/filament-language-switch.php
return [
    'locales' => [
        'ar' => 'العربية',
        'en' => 'English',
    ],
];
```

### 3. تسجيل الميدلوير في Kernel.php

```php
// app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        // ...
        \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
    ],
];

protected $routeMiddleware = [
    // ...
    'localize' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
    'localizationRedirect' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
    'localeSessionRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
    'localeCookieRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class,
    'localeViewPath' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
];
```

## تنظيم المسارات متعددة اللغات

```php
// routes/web.php
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
], function() {
    // المسارات متعددة اللغات هنا
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about', [AboutController::class, 'index'])->name('about');
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
});
```

## مكونات مخصصة لدعم تعدد اللغات

### 1. مكون TranslationTextColumn

مكون مخصص لعرض البيانات المترجمة في الجداول:

```php
// app/FilamentCustom/Table/TranslationTextColumn.php
class TranslationTextColumn extends TextColumn {
    protected string $locale;
    
    public function locale(string $locale): static {
        $this->locale = $locale;
        return $this;
    }
    
    public function getStateFromRecord(Model $record): mixed {
        $locale = $this->locale ?? app()->getLocale();
        $field = $this->getName();
        
        if (method_exists($record, 'getTranslation')) {
            return $record->getTranslation($field, $locale);
        }
        
        return data_get($record, $field);
    }
}

// استخدام المكون في الجدول
TranslationTextColumn::make('name')
    ->label(__('default/products.name'))
    ->locale(app()->getLocale())
    ->searchable(),
```

### 2. دالة للتبديل بين اللغات

```php
// زر التبديل بين اللغات في الواجهة الأمامية
@foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
    <a href="{{ LaravelLocalization::getLocalizedURL($localeCode) }}" class="language-link">
        {{ $properties['native'] }}
    </a>
@endforeach
```

## أفضل الممارسات لدعم تعدد اللغات

### 1. استخدام مفاتيح ترجمة ثابتة

```php
// استخدم
__('default/products.name')

// بدلاً من
__('Name')
```

### 2. تنظيم ملفات الترجمة حسب المورد

ضع كل الترجمات المتعلقة بالمورد في ملف خاص به للحفاظ على تنظيم الترجمات.

### 3. تنسيق النصوص المترجمة

استخدم وسائل تنسيق النصوص المترجمة:

```php
// lang/ar/default/lang.php
'welcome' => 'مرحبًا :name!',

// الاستخدام
__('default/lang.welcome', ['name' => $user->name])
```

### 4. الترجمة الشرطية

استخدم الترجمة الشرطية عند الحاجة:

```php
// lang/ar/default/lang.php
'apples' => '{0} لا يوجد تفاح|[1,19] :count تفاحات|[20,*] :count تفاحة',

// الاستخدام
trans_choice('default/lang.apples', $count, ['count' => $count])
```

### 5. مراعاة الثقافات المختلفة

راعي الاختلافات الثقافية في الترجمات، مثل:
- تنسيق التواريخ
- تنسيق الأرقام
- اتجاه القراءة
- وحدات القياس

## استراتيجيات اختبار الترجمات

### 1. التأكد من وجود جميع المفاتيح

```php
// tests/Unit/TranslationTest.php
public function test_all_translation_keys_exist()
{
    $ar = require(lang_path('ar/default/users.php'));
    $en = require(lang_path('en/default/users.php'));
    
    // التأكد من وجود نفس المفاتيح في جميع اللغات
    $this->assertEquals(
        array_keys($ar),
        array_keys($en),
        'Translation keys mismatch between Arabic and English'
    );
}
```

### 2. التأكد من صحة تحميل الترجمات

```php
public function test_translations_are_loaded_correctly()
{
    $this->assertEquals('المستخدمين', __('default/users.PluralModelLabel'));
    
    app()->setLocale('en');
    $this->assertEquals('Users', __('default/users.PluralModelLabel'));
}
```

## الخلاصة

يوفر نظام الكور المخصص دعمًا شاملًا لتعدد اللغات من خلال:

1. **تنظيم منهجي لملفات الترجمة**: تقسيم حسب المورد والوظيفة
2. **دعم البيانات متعددة اللغات في قاعدة البيانات**: باستخدام Laravel Translatable
3. **مكونات مخصصة للتعامل مع اللغات**: مثل TranslationTextColumn
4. **دعم اتجاهات القراءة المختلفة**: RTL و LTR
5. **مسارات متعددة اللغات**: باستخدام Laravel Localization

هذا النهج الشامل يسمح بتطوير تطبيقات متعددة اللغات بسهولة وفعالية، مع الحفاظ على تجربة مستخدم متسقة عبر جميع اللغات المدعومة.
