# نمط الترميز الأساسي

## فلسفة نمط الترميز

يعتمد نظام الكور المخصص على نمط ترميز واضح ومتسق يهدف إلى تحسين قابلية القراءة والصيانة وإعادة الاستخدام. يمكن تلخيص فلسفة الترميز في المشروع بالنقاط التالية:

1. **الوضوح قبل الإيجاز**: التركيز على وضوح الكود أكثر من الإيجاز
2. **التنظيم المنطقي**: تنظيم الكود في أقسام منطقية واضحة
3. **إعادة الاستخدام**: استخراج الوظائف المشتركة إلى مكونات قابلة لإعادة الاستخدام
4. **الاتساق**: اتباع نمط ثابت في جميع أجزاء المشروع
5. **التوثيق الذاتي**: كتابة كود يوثق نفسه قدر الإمكان

## الاصطلاحات العامة

### 1. استخدام الفواصل النصية

يستخدم المشروع فواصل نصية فريدة لتسهيل تقسيم الكود والتنقل فيه:

```php
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
// بداية قسم جديد
```

هذه الفواصل تستخدم قبل:
- تعريف الدوال العامة
- بداية الأقسام الرئيسية في الملف
- الفصل بين أجزاء الكود المختلفة منطقياً

### 2. اصطلاحات التسمية

#### أسماء النماذج (Models)
- صيغة المفرد
- الحرف الأول كبير (PascalCase)
- مثال: `User`, `Product`, `Category`

#### أسماء الموارد (Resources)
- اسم النموذج + `Resource`
- مثال: `UserResource`, `ProductResource`

#### أسماء الصفحات (Pages)
- اسم وصفي للإجراء + اسم النموذج
- مثال: `ListUsers`, `CreateProduct`, `EditCategory`

#### أسماء الوظائف (Methods)
- تبدأ بفعل (camelCase)
- مثال: `getColumns()`, `filterPermissions()`, `getTabs()`

#### أسماء المكونات المخصصة
- أسماء وصفية توضح الغرض
- مثال: `CreatedDates`, `IconColumnDef`, `TranslationTextColumn`

### 3. تنظيم الكود في النماذج (Models)

```php
class Product extends Model {
    // 1. الخصائص العامة
    protected $table = 'products';
    protected $fillable = ['name', 'price', 'description'];
    protected $casts = ['price' => 'decimal:2'];
    
    // 2. العلاقات
    public function category() {
        return $this->belongsTo(Category::class);
    }
    
    // 3. النطاقات (Scopes)
    public function scopeActive($query) {
        return $query->where('is_active', true);
    }
    
    // 4. الدوال المساعدة
    public function getFormattedPriceAttribute() {
        return '$' . number_format($this->price, 2);
    }
}
```

### 4. تنظيم الكود في الموارد (Resources)

```php
class UserResource extends Resource implements HasShieldPermissions {
    use SmartResourceTrait;
    
    // 1. تعريفات أساسية
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    // 2. إدارة الصلاحيات
    public static function getPermissionPrefixes(): array {
        // ...
    }
    
    // 3. تخصيص التصفح والترجمة
    public static function getNavigationGroup(): ?string {
        return __('default/users.navigation_group');
    }
    
    // 4. تعريف النموذج (Form)
    public static function form(Form $form): Form {
        // ...
    }
    
    // 5. تعريف الجدول (Table)
    public static function table(Table $table): Table {
        // ...
    }
    
    // 6. تعريف العلاقات
    public static function getRelations(): array {
        // ...
    }
    
    // 7. تعريف الصفحات
    public static function getPages(): array {
        // ...
    }
    
    // 8. تعريف قائمة المعلومات (Infolist)
    public static function infolist(Infolist $infolist): Infolist {
        // ...
    }
}
```

## أنماط خاصة في النظام

### 1. تنظيم النماذج (Forms)

```php
Group::make()->schema([
    Section::make(__('default/users.card.User_Information'))->schema([
        TextInput::make('name')
            ->label(__('default/users.name'))
            ->required(),
            
        TextInput::make('email')
            ->label(__('default/users.email'))
            ->email()
            ->required(),
    ])->columns(2),
])->columnSpan(2)
```

**الممارسات الأساسية**:
- تقسيم النموذج إلى مجموعات (`Group`)
- تنظيم الحقول ذات الصلة في أقسام (`Section`)
- استخدام الترجمات للعناوين
- ضبط عدد الأعمدة لكل قسم
- تعيين عرض المجموعة في التخطيط العام

### 2. تنظيم الجداول (Tables)

```php
return $table->columns([
    ImageColumn::make('avatar_url')
        ->label('')
        ->circular(),
        
    TextColumn::make('name')
        ->label(__('default/users.name'))
        ->searchable(),
        
    // استخدام مكونات مخصصة
    ...CreatedDates::make()->toggleable(true)->getColumns(),
    
])->filters([
    TrashedFilter::make(),
    DateRangeFilter::make('created_at'),
])->actions([
    Tables\Actions\EditAction::make()->iconButton(),
    Tables\Actions\DeleteAction::make()->iconButton(),
]);
```

**الممارسات الأساسية**:
- تنظيم الأعمدة حسب الأهمية
- استخدام مكونات مخصصة لتبسيط الكود وتجنب التكرار
- تعريف الفلاتر لتسهيل التصفية
- تحديد الإجراءات المتاحة
- استخدام الترجمات للتسميات

### 3. تنظيم الصفحات (Pages)

```php
class ListUsers extends ListRecords {
    protected static string $resource = UserResource::class;

    // 1. إجراءات رأس الصفحة
    protected function getHeaderActions(): array {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // 2. تعريف التبويبات
    public function getTabs(): array {
        return [
            'Active' => Tab::make()
                ->label(__('default/users.tab.Active'))
                ->icon('heroicon-o-users')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('is_active', true)),
                
            'Inactive' => Tab::make()
                ->label(__('default/users.tab.Inactive'))
                ->icon('heroicon-o-user-slash')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('is_active', false)),
        ];
    }
}
```

**الممارسات الأساسية**:
- تحديد المورد المرتبط بوضوح
- تنظيم إجراءات رأس الصفحة
- استخدام التبويبات لتصفية البيانات
- استخدام الأيقونات لتحسين تجربة المستخدم
- استخدام وظائف دالة (Closure) مختصرة للتصفية

## الإعتبارات الخاصة

### 1. استخدام SmartResourceTrait

```php
trait SmartResourceTrait {
    public static function filterPermissions(array $skipKeys = [], array $keepKeys = [], array $addKeys = []): array {
        // منطق تصفية الصلاحيات...
    }
    
    public static function getNavigationSortNumber(): int {
        // منطق تحديد ترتيب التنقل...
    }
}
```

**الغرض**:
- توحيد السلوك بين موارد Filament المختلفة
- تجنب تكرار الكود المشترك
- تسهيل صيانة وتحديث السلوك المشترك

### 2. مكونات الجدول المخصصة

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
        // إرجاع أعمدة التواريخ...
    }
}
```

**الغرض**:
- تبسيط إضافة أعمدة متكررة
- ضمان اتساق الجداول عبر المشروع
- تسهيل التعديلات الشاملة

### 3. سلسلة الترجمة

```php
__('default/users.name')  // للترجمات الخاصة بالمستخدمين
__('default/lang.columns.created_at')  // للترجمات العامة
```

**الممارسات الأساسية**:
- تنظيم ملفات الترجمة حسب المورد
- استخدام مفاتيح ثابتة ومتسقة
- فصل الترجمات العامة عن الخاصة بكل مورد

## أمثلة عملية لنمط الترميز

### مثال على تعريف النموذج (Form)

```php
public static function form(Form $form): Form {
    return $form->schema([
        Group::make()->schema([
            Section::make(__('default/users.card.User_Information'))->schema([
                TextInput::make('name')
                    ->label(__('default/users.name'))
                    ->maxLength(255)
                    ->required(),

                TextInput::make('email')
                    ->label(__('default/users.email'))
                    ->email()
                    ->unique(User::class, 'email', ignoreRecord: true)
                    ->maxLength(255)
                    ->required(),

                TextInput::make('password')
                    ->label(__('default/users.password'))
                    ->password()
                    ->dehydrateStateUsing(fn($state) => !empty($state) ? Hash::make($state) : null)
                    ->dehydrated(fn($state) => !empty($state))
                    ->maxLength(255),
            ])->columns(2),
        ])->columnSpan(2),

        Group::make()->schema([
            Section::make(__('default/users.card.User_Information'))->schema([
                // حقول إضافية...
            ])->columns(1),
        ])->columnSpan(1),
    ])->columns(3);
}
```

### مثال على تعريف الجدول (Table)

```php
public static function table(Table $table): Table {
    return $table->columns([
        ImageColumn::make('avatar_url')
            ->disk('root_folder')
            ->label('')
            ->searchable()
            ->circular()
            ->grow(false),

        TextColumn::make('name')
            ->label(__('default/users.name'))
            ->weight(FontWeight::Bold)
            ->searchable(),

        TextColumn::make('email')
            ->label(__('default/users.email'))
            ->icon('heroicon-m-envelope')
            ->weight(FontWeight::Bold)
            ->searchable(),

        IconColumn::make('is_active')
            ->label(__('default/users.is_active'))
            ->boolean(),

        ...CreatedDates::make()->toggleable(true)->getColumns(),
    ])->filters([
        TrashedFilter::make(),
        DateRangeFilter::make('created_at')->label(__('default/lang.columns.created_at')),
    ])->actions([
        Tables\Actions\EditAction::make()->iconButton(),
        Tables\Actions\DeleteAction::make()->iconButton(),
        Tables\Actions\ViewAction::make()->iconButton(),
    ]);
}
```

## نصائح عملية

1. **استخدام الفواصل النصية**: استخدم فواصل `#@@@...` لتقسيم الكود إلى أقسام واضحة
2. **تنظيم الشيفرة منطقيًا**: ضع الأشياء المتشابهة معًا (العلاقات، النطاقات، إلخ)
3. **اتباع تسلسل ثابت**: التزم بالترتيب الموضح في الأمثلة
4. **استخدام التعليقات**: أضف تعليقات توضيحية عند الضرورة
5. **استخدام المتغيرات المحلية**: لتحسين قراءة الكود المعقد
6. **استخدام الفنكشن تشاينينغ**: للحفاظ على نمط نظيف ومقروء
7. **التزام بنمط واحد**: اتبع نفس النمط في جميع أجزاء المشروع
