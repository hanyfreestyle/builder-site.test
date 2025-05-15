# التوسيعات المخصصة

## نظرة عامة على التوسيعات المخصصة

يتميز نظام الكور المخصص بوجود مجلد `FilamentCustom` الذي يحتوي على توسيعات وتخصيصات خاصة بـ Filament. هذه التوسيعات تعزز وظائف Filament الأساسية وتضيف مكونات جديدة تناسب احتياجات المشروع. الهدف الرئيسي هو الفصل بين المكونات القياسية والمكونات المخصصة لسهولة الصيانة والتطوير.

## هيكل مجلد FilamentCustom

```
app/FilamentCustom/
├── Form/              # مكونات النموذج المخصصة
├── Setting/           # مكونات الإعدادات المخصصة
├── Table/             # مكونات الجدول المخصصة
├── UploadFile/        # مكونات رفع الملفات المخصصة
└── View/              # مكونات العرض المخصصة
```

## مكونات الجدول المخصصة (Table Components)

### 1. CreatedDates

هذا المكون يوفر طريقة موحدة لعرض أعمدة التواريخ (إنشاء، تحديث، حذف) في جميع الجداول.

#### الملف
`app/FilamentCustom/Table/CreatedDates.php`

#### الكود

```php
namespace App\FilamentCustom\Table;

use Filament\Tables\Columns\TextColumn;

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

#### الاستخدام

```php
public static function table(Table $table): Table {
    return $table->columns([
        // أعمدة أخرى...
        
        // استخدام CreatedDates لإضافة أعمدة التواريخ
        ...CreatedDates::make()->toggleable(true)->getColumns(),
    ]);
}
```

#### الميزات

- توحيد طريقة عرض أعمدة التواريخ في جميع الجداول
- إمكانية تخصيص سلوك الإخفاء/الإظهار من خلال خاصية `toggleable`
- استخدام الترجمات للتسميات

### 2. IconColumnDef

مكون لتوحيد تعريف أعمدة الأيقونات مع إعدادات مشتركة.

#### الملف
`app/FilamentCustom/Table/IconColumnDef.php`

#### الكود (مثال)

```php
namespace App\FilamentCustom\Table;

use Filament\Tables\Columns\IconColumn;

class IconColumnDef {
    public static function statusColumn(string $field = 'is_active'): IconColumn {
        return IconColumn::make($field)
            ->label(__("default/lang.columns.{$field}"))
            ->boolean()
            ->trueIcon('heroicon-o-check-circle')
            ->trueColor('success')
            ->falseIcon('heroicon-o-x-circle')
            ->falseColor('danger');
    }
    
    public static function featuredColumn(string $field = 'is_featured'): IconColumn {
        return IconColumn::make($field)
            ->label(__("default/lang.columns.{$field}"))
            ->boolean()
            ->trueIcon('heroicon-o-star')
            ->trueColor('warning')
            ->falseIcon('heroicon-o-minus-circle')
            ->falseColor('secondary');
    }
}
```

#### الاستخدام

```php
public static function table(Table $table): Table {
    return $table->columns([
        // أعمدة أخرى...
        
        // استخدام IconColumnDef لإضافة عمود الحالة
        IconColumnDef::statusColumn(),
        
        // استخدام IconColumnDef لإضافة عمود مميز
        IconColumnDef::featuredColumn(),
    ]);
}
```

### 3. ImageColumnDef

مكون لتوحيد تعريف أعمدة الصور مع إعدادات مشتركة.

#### الملف
`app/FilamentCustom/Table/ImageColumnDef.php`

#### الكود (مثال)

```php
namespace App\FilamentCustom\Table;

use Filament\Tables\Columns\ImageColumn;

class ImageColumnDef {
    public static function avatar(): ImageColumn {
        return ImageColumn::make('avatar_url')
            ->disk('root_folder')
            ->label('')
            ->circular()
            ->grow(false)
            ->getStateUsing(fn($record) => $record->avatar_url
                ? $record->avatar_url
                : "https://ui-avatars.com/api/?name=" . urlencode($record->name));
    }
    
    public static function thumbnail(string $field = 'thumbnail'): ImageColumn {
        return ImageColumn::make($field)
            ->disk('root_folder')
            ->label(__("default/lang.columns.{$field}"))
            ->square()
            ->grow(false)
            ->height(50);
    }
}
```

#### الاستخدام

```php
public static function table(Table $table): Table {
    return $table->columns([
        // استخدام ImageColumnDef لإضافة عمود الصورة الشخصية
        ImageColumnDef::avatar(),
        
        // أعمدة أخرى...
    ]);
}
```

### 4. TranslationTextColumn

مكون لعرض البيانات المترجمة في الجداول.

#### الملف
`app/FilamentCustom/Table/TranslationTextColumn.php`

#### الكود (مثال)

```php
namespace App\FilamentCustom\Table;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

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
```

#### الاستخدام

```php
public static function table(Table $table): Table {
    return $table->columns([
        // استخدام TranslationTextColumn لعرض البيانات المترجمة
        TranslationTextColumn::make('name')
            ->label(__('default/products.name'))
            ->locale(app()->getLocale())
            ->searchable(),
        
        // أعمدة أخرى...
    ]);
}
```

## مكونات النموذج المخصصة (Form Components)

### 1. WebpImageUpload

مكون لرفع وتحويل الصور إلى تنسيق Webp مع خيارات تحسين.

#### الملف
`app/FilamentCustom/Form/WebpImageUpload.php`

#### الكود (مثال)

```php
namespace App\FilamentCustom\Form;

use Filament\Forms\Components\FileUpload;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class WebpImageUpload extends FileUpload {
    protected int $width = 0;
    protected int $height = 0;
    protected int $quality = 80;
    protected string $uploadDirectory = 'uploads';
    
    public function resize(int $width, int $height, int $quality = 80): static {
        $this->width = $width;
        $this->height = $height;
        $this->quality = $quality;
        return $this;
    }
    
    public function uploadDirectory(string $directory): static {
        $this->uploadDirectory = $directory;
        
        // تعيين خيارات FileUpload الأساسية
        $this->disk('root_folder')
            ->directory($directory)
            ->visibility('public')
            ->imagePreviewHeight(100)
            ->panelAspectRatio('1:1')
            ->removeUploadedFileButtonPosition('right')
            ->uploadButtonPosition('left')
            ->uploadProgressIndicatorPosition('center');
        
        return $this;
    }
    
    protected function setUp(): void {
        parent::setUp();
        
        // تخصيص معالجة الملفات المرفوعة
        $this->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
            $filename = $file->hashName();
            
            // إنشاء Intervention Image وتنفيذ التعديلات
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file->getRealPath());
            
            if ($this->width > 0 && $this->height > 0) {
                $image->resize($this->width, $this->height);
            }
            
            // تحويل الصورة إلى Webp
            $webpFilename = pathinfo($filename, PATHINFO_FILENAME) . '.webp';
            $path = $this->uploadDirectory . '/' . $webpFilename;
            
            // حفظ الصورة
            $image->encodeByExtension('webp', $this->quality)
                ->save(storage_path('app/public/' . $path));
            
            return $path;
        });
    }
}
```

#### الاستخدام

```php
public static function form(Form $form): Form {
    return $form->schema([
        // أقسام وحقول أخرى...
        
        WebpImageUpload::make('image')
            ->label(__('default/products.image'))
            ->uploadDirectory('products')
            ->resize(800, 600, 85)
            ->nullable(),
    ]);
}
```

### 2. ColorPicker

مكون لاختيار الألوان مع العرض المرئي ودعم تنسيقات مختلفة.

#### الملف
`app/FilamentCustom/Form/ColorPicker.php`

#### الكود (مثال)

```php
namespace App\FilamentCustom\Form;

use Filament\Forms\Components\TextInput;

class ColorPicker extends TextInput {
    protected function setUp(): void {
        parent::setUp();
        
        $this->view('filament-custom.forms.components.color-picker')
            ->suffix('')
            ->suffixIcon('heroicon-o-swatch')
            ->extraInputAttributes(['type' => 'color'])
            ->maxLength(7);
    }
}
```

#### الاستخدام

```php
public static function form(Form $form): Form {
    return $form->schema([
        // أقسام وحقول أخرى...
        
        ColorPicker::make('background_color')
            ->label(__('default/themes.background_color'))
            ->required(),
    ]);
}
```

## مكونات الإعدادات المخصصة (Setting Components)

### 1. SettingGroup

مكون لتنظيم وإدارة مجموعات الإعدادات.

#### الملف
`app/FilamentCustom/Setting/SettingGroup.php`

#### الكود (مثال)

```php
namespace App\FilamentCustom\Setting;

use Spatie\Valuestore\Valuestore;

class SettingGroup {
    protected string $group;
    protected ?Valuestore $store = null;
    
    public function __construct(string $group) {
        $this->group = $group;
        $this->store = Valuestore::make(storage_path('app/settings.json'));
    }
    
    public static function make(string $group): static {
        return new static($group);
    }
    
    public function get(string $key, $default = null) {
        $fullKey = "{$this->group}.{$key}";
        return $this->store->get($fullKey, $default);
    }
    
    public function set(string $key, $value): void {
        $fullKey = "{$this->group}.{$key}";
        $this->store->put($fullKey, $value);
    }
    
    public function all(): array {
        $settings = $this->store->all();
        $groupSettings = [];
        
        foreach ($settings as $key => $value) {
            if (str_starts_with($key, "{$this->group}.")) {
                $shortKey = str_replace("{$this->group}.", '', $key);
                $groupSettings[$shortKey] = $value;
            }
        }
        
        return $groupSettings;
    }
    
    public function forget(string $key): void {
        $fullKey = "{$this->group}.{$key}";
        $this->store->forget($fullKey);
    }
}
```

#### الاستخدام

```php
// الحصول على إعدادات الموقع
$siteSettings = SettingGroup::make('site');
$siteName = $siteSettings->get('name', 'Default Site Name');
$siteDescription = $siteSettings->get('description');

// تحديث الإعدادات
$siteSettings->set('name', 'My Awesome Site');
$siteSettings->set('logo', 'path/to/logo.webp');

// الحصول على جميع إعدادات المجموعة
$allSiteSettings = $siteSettings->all();
```

### 2. SettingPage

مكون لإنشاء صفحات إعدادات في Filament.

#### الملف
`app/FilamentCustom/Setting/SettingPage.php`

#### الكود (مثال)

```php
namespace App\FilamentCustom\Setting;

use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Spatie\Valuestore\Valuestore;

class SettingPage extends Page implements HasForms {
    use InteractsWithForms;
    
    protected static string $view = 'filament-custom.pages.settings-page';
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationGroup = 'Settings';
    
    protected string $settingGroup;
    protected ?Valuestore $store = null;
    
    public function mount(): void {
        $this->form->fill($this->getFormData());
    }
    
    protected function getFormData(): array {
        $this->store = Valuestore::make(storage_path('app/settings.json'));
        $settings = $this->store->all();
        $formData = [];
        
        foreach ($settings as $key => $value) {
            if (str_starts_with($key, "{$this->settingGroup}.")) {
                $shortKey = str_replace("{$this->settingGroup}.", '', $key);
                $formData[$shortKey] = $value;
            }
        }
        
        return $formData;
    }
    
    public function submitForm(): void {
        $data = $this->form->getState();
        
        foreach ($data as $key => $value) {
            $fullKey = "{$this->settingGroup}.{$key}";
            $this->store->put($fullKey, $value);
        }
        
        $this->notify('success', 'Settings saved successfully');
    }
    
    public function form(Form $form): Form {
        return $form->schema($this->getFormSchema())
            ->statePath('data');
    }
    
    protected function getFormSchema(): array {
        // يتم تجاوز هذه الدالة في الصفحات الفرعية
        return [];
    }
}
```

#### صفحة الإعدادات الفرعية (مثال)

```php
namespace App\Filament\Admin\Pages;

use App\FilamentCustom\Setting\SettingPage;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;

class SiteSettings extends SettingPage {
    protected static ?string $navigationLabel = 'Site Settings';
    protected static ?string $title = 'Site Settings';
    
    public function __construct() {
        $this->settingGroup = 'site';
    }
    
    protected function getFormSchema(): array {
        return [
            Section::make('General Settings')
                ->schema([
                    TextInput::make('name')
                        ->label('Site Name')
                        ->required(),
                        
                    TextInput::make('title')
                        ->label('Site Title')
                        ->required(),
                        
                    RichEditor::make('description')
                        ->label('Site Description')
                        ->columnSpanFull(),
                ])->columns(2),
                
            Section::make('Branding')
                ->schema([
                    FileUpload::make('logo')
                        ->label('Site Logo')
                        ->disk('public')
                        ->directory('site')
                        ->visibility('public')
                        ->image(),
                        
                    FileUpload::make('favicon')
                        ->label('Favicon')
                        ->disk('public')
                        ->directory('site')
                        ->visibility('public')
                        ->image()
                        ->imageResizeMode('contain')
                        ->imageResizeTargetWidth('64')
                        ->imageResizeTargetHeight('64'),
                ]),
        ];
    }
}
```

## مكونات رفع الملفات المخصصة (UploadFile Components)

### مكون FileUploadManager

مكون لإدارة عملية رفع الملفات المختلفة مع معالجة خاصة لكل نوع.

#### الملف
`app/FilamentCustom/UploadFile/FileUploadManager.php`

#### الكود (مثال)

```php
namespace App\FilamentCustom\UploadFile;

use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class FileUploadManager {
    protected string $disk = 'public';
    protected string $directory = 'uploads';
    
    public function __construct(string $directory = 'uploads', string $disk = 'public') {
        $this->directory = $directory;
        $this->disk = $disk;
    }
    
    public static function make(string $directory = 'uploads', string $disk = 'public'): static {
        return new static($directory, $disk);
    }
    
    public function uploadImage(UploadedFile $file, array $options = []): string {
        $options = array_merge([
            'width' => null,
            'height' => null,
            'quality' => 85,
            'format' => 'webp',
            'filename_prefix' => '',
        ], $options);
        
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getRealPath());
        
        // تغيير حجم الصورة إذا تم تحديد الأبعاد
        if ($options['width'] && $options['height']) {
            $image->resize($options['width'], $options['height']);
        }
        
        // إنشاء اسم الملف
        $filename = $options['filename_prefix'] . md5($file->getClientOriginalName() . time()) . '.' . $options['format'];
        $path = $this->directory . '/' . $filename;
        
        // حفظ الصورة المعالجة
        $image->encodeByExtension($options['format'], $options['quality'])
            ->save(storage_path('app/public/' . $path));
        
        return $path;
    }
    
    public function uploadDocument(UploadedFile $file): string {
        $filename = md5($file->getClientOriginalName() . time()) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($this->directory, $filename, ['disk' => $this->disk]);
        
        return $path;
    }
    
    public function deleteFile(string $path): bool {
        if (\Storage::disk($this->disk)->exists($path)) {
            return \Storage::disk($this->disk)->delete($path);
        }
        
        return false;
    }
}
```

#### الاستخدام

```php
// في Controller أو Service
$uploadManager = FileUploadManager::make('products');

// رفع صورة مع تغيير الحجم
$imagePath = $uploadManager->uploadImage($request->file('image'), [
    'width' => 800,
    'height' => 600,
    'quality' => 90,
    'filename_prefix' => 'product_'
]);

// رفع مستند
$documentPath = $uploadManager->uploadDocument($request->file('document'));

// حفظ المسارات في قاعدة البيانات
$product->update([
    'image' => $imagePath,
    'document' => $documentPath
]);
```

## مكونات العرض المخصصة (View Components)

### 1. StatCard

مكون لعرض البطاقات الإحصائية في لوحة التحكم.

#### الملف
`app/FilamentCustom/View/StatCard.php`

#### الكود (مثال)

```php
namespace App\FilamentCustom\View;

use Illuminate\View\Component;

class StatCard extends Component {
    public string $title;
    public $value;
    public string $icon;
    public string $color;
    public ?string $description;
    public ?string $url;
    
    public function __construct(
        string $title,
        $value,
        string $icon,
        string $color = 'primary',
        ?string $description = null,
        ?string $url = null
    ) {
        $this->title = $title;
        $this->value = $value;
        $this->icon = $icon;
        $this->color = $color;
        $this->description = $description;
        $this->url = $url;
    }
    
    public function render() {
        return view('components.stat-card');
    }
}
```

#### قالب العرض (Blade)

```html
<!-- resources/views/components/stat-card.blade.php -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200 {{ $url ? 'cursor-pointer' : '' }}">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $title }}</h3>
            <p class="text-3xl font-bold mt-2 {{ 'text-' . $color . '-600' }}">{{ $value }}</p>
            
            @if($description)
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ $description }}</p>
            @endif
        </div>
        
        <div class="h-12 w-12 flex items-center justify-center rounded-full {{ 'bg-' . $color . '-100' }}">
            <i class="{{ $icon }} {{ 'text-' . $color . '-500' }} text-xl"></i>
        </div>
    </div>
</div>
```

## تسجيل وتكوين المكونات المخصصة

### 1. تسجيل المكونات في Service Provider

```php
// app/Providers/FilamentCustomServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\FilamentCustom\View\StatCard;

class FilamentCustomServiceProvider extends ServiceProvider {
    public function register() {
        //
    }
    
    public function boot() {
        // تسجيل مكونات Blade
        Blade::component('stat-card', StatCard::class);
        
        // تسجيل مسارات العرض
        $this->loadViewsFrom(__DIR__ . '/../../resources/views/filament-custom', 'filament-custom');
        
        // نشر ملفات الأصول
        $this->publishes([
            __DIR__ . '/../../resources/views/filament-custom' => resource_path('views/vendor/filament-custom'),
        ], 'filament-custom-views');
    }
}
```

### 2. تسجيل مقدم الخدمة في config/app.php

```php
// config/app.php
'providers' => [
    // ...
    App\Providers\FilamentCustomServiceProvider::class,
],
```

## مزايا استخدام التوسيعات المخصصة

1. **الفصل بين الكود**: فصل واضح بين مكونات Filament الأساسية والتوسيعات المخصصة
2. **إعادة استخدام الكود**: استخراج الوظائف المتكررة إلى مكونات قابلة لإعادة الاستخدام
3. **توحيد واجهة المستخدم**: ضمان اتساق المظهر والسلوك عبر لوحة التحكم
4. **سهولة الصيانة**: تسهيل تحديث وتطوير المكونات المخصصة دون التأثير على المكونات الأساسية
5. **التوسعة بسهولة**: إمكانية إضافة مكونات جديدة حسب الحاجة دون تعديل المكونات الحالية

## أفضل الممارسات لاستخدام التوسيعات المخصصة

1. **التزم بمسؤولية واحدة**: كل مكون يجب أن يكون له مسؤولية واضحة ومحددة
2. **استخدم المكونات القياسية كأساس**: بدلاً من إعادة اختراع العجلة، قم بتوسيع المكونات القياسية
3. **تأكد من إعادة الاستخدام**: صمم المكونات بحيث يمكن إعادة استخدامها في أماكن متعددة
4. **اكتب اختبارات للمكونات**: تأكد من تغطية المكونات المخصصة بالاختبارات
5. **وثق المكونات**: اكتب توثيقًا واضحًا لكيفية استخدام المكونات المخصصة

## كيفية إضافة مكون مخصص جديد

1. **حدد احتياجاتك**: تحديد المشكلة التي يحلها المكون المخصص
2. **اختر المجلد المناسب**: تحديد المجلد المناسب في `FilamentCustom` حسب نوع المكون
3. **أنشئ الملف الجديد**: إنشاء ملف جديد للمكون
4. **كتابة الكود**: تنفيذ المكون مع الالتزام بأفضل الممارسات
5. **الاختبار**: اختبار المكون للتأكد من عمله بشكل صحيح
6. **التوثيق**: توثيق كيفية استخدام المكون
7. **المشاركة**: مشاركة المكون مع فريق التطوير

## الخلاصة

مجلد `FilamentCustom` وما يحتويه من مكونات مخصصة يعد جزءًا أساسيًا من نظام الكور المخصص. هذه المكونات توفر وظائف إضافية تكمل وتعزز قدرات Filament الأساسية، وتساعد على توحيد تجربة المستخدم وتبسيط عملية التطوير. من خلال الالتزام بالهيكل المنظم والمبادئ التوجيهية الموضحة في هذا الدليل، يمكن تطوير مكونات مخصصة عالية الجودة تلبي احتياجات المشروع الخاصة.
