# إدارة الصلاحيات

## نظرة عامة على نظام الصلاحيات

نظام الكور المخصص يستخدم مكتبة `bezhansalleh/filament-shield` بالتكامل مع `spatie/laravel-permission` لإدارة الصلاحيات والأدوار بطريقة مرنة وقوية. يتم توسيع هذه المكتبات من خلال `SmartResourceTrait` لتوفير تحكم أكثر دقة في إدارة الصلاحيات.

## المكونات الرئيسية لنظام الصلاحيات

### 1. التكامل مع مكتبة Shield

```php
// composer.json
"bezhansalleh/filament-shield": "^3.3"
```

Shield هي مكتبة تعمل كواجهة لإدارة صلاحيات Spatie Laravel Permission في Filament. توفر واجهة مستخدم لإدارة الأدوار والصلاحيات.

### 2. استخدام HasShieldPermissions

```php
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class UserResource extends Resource implements HasShieldPermissions {
    // ...
}
```

تنفيذ واجهة `HasShieldPermissions` يجعل المورد متاحًا لإدارة الصلاحيات في واجهة Shield الإدارية.

### 3. استخدام HasRoles في النموذج

```php
use Spatie\Permission\Traits\HasRoles;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;

class User extends Authenticatable implements FilamentUser {
    use HasRoles, HasPanelShield;
    
    // ...
}
```

يتم استخدام `HasRoles` و `HasPanelShield` في نموذج المستخدم لتمكين التحقق من الصلاحيات.

## تخصيص الصلاحيات باستخدام SmartResourceTrait

### 1. الدالة filterPermissions

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

هذه الدالة توفر طريقة مرنة لتخصيص الصلاحيات لكل مورد:

- **skipKeys**: صلاحيات يجب استبعادها
- **keepKeys**: صلاحيات يجب الاحتفاظ بها (حتى لو كانت في alwaysRemove)
- **addKeys**: صلاحيات إضافية يجب إضافتها

### 2. تنفيذ getPermissionPrefixes في الموارد

```php
public static function getPermissionPrefixes(): array {
    return static::filterPermissions(
        skipKeys: ['slug', 'view'],  // استبعاد بعض الصلاحيات
        keepKeys: ['delete'],        // الاحتفاظ ببعض الصلاحيات
        addKeys: [                   // إضافة صلاحيات مخصصة
            'keys' => ['approve', 'reject'],
            'placeIn' => 'after'
        ],
    );
}
```

كل مورد يمكنه تخصيص قائمة الصلاحيات الخاصة به من خلال تنفيذ دالة `getPermissionPrefixes()`.

## الصلاحيات الافتراضية في النظام

الصلاحيات الافتراضية التي يوفرها Shield:

| الصلاحية | الوصف |
|----------|-------|
| `view_any` | عرض قائمة السجلات |
| `view` | عرض سجل محدد |
| `create` | إنشاء سجل جديد |
| `update` | تعديل سجل موجود |
| `delete` | حذف سجل |
| `delete_any` | حذف مجموعة من السجلات |
| `force_delete` | حذف نهائي لسجل (مع الحذف الناعم) |
| `force_delete_any` | حذف نهائي لمجموعة من السجلات |
| `restore` | استعادة سجل محذوف |
| `restore_any` | استعادة مجموعة من السجلات المحذوفة |
| `replicate` | نسخ سجل |
| `reorder` | إعادة ترتيب السجلات |

## الصلاحيات المخصصة

يمكن إضافة صلاحيات مخصصة لكل مورد من خلال وسيطة `addKeys`:

```php
public static function getPermissionPrefixes(): array {
    return static::filterPermissions(
        skipKeys: [],
        keepKeys: [],
        addKeys: [
            'keys' => [
                'publish',        // نشر العنصر
                'unpublish',      // إلغاء نشر العنصر
                'feature',        // تمييز العنصر
                'approve_comment' // الموافقة على تعليق
            ],
            'placeIn' => 'after'
        ],
    );
}
```

## تعيين الصلاحيات للإجراءات في الجداول

```php
public static function table(Table $table): Table {
    return $table
        // ...
        ->actions([
            Tables\Actions\EditAction::make()
                ->iconButton()
                ->visible(fn ($record) => auth()->user()->can('update', $record)),
                
            Tables\Actions\DeleteAction::make()
                ->iconButton()
                ->visible(fn ($record) => auth()->user()->can('delete', $record)),
                
            // إجراء مخصص مع صلاحية مخصصة
            Action::make('publish')
                ->label(__('default/posts.actions.publish'))
                ->icon('heroicon-o-globe')
                ->visible(fn ($record) => auth()->user()->can('publish', $record))
                ->action(fn ($record) => $record->publish()),
        ]);
}
```

## تنظيم الأدوار في النظام

### 1. تعريف الأدوار الرئيسية

بشكل افتراضي، يتم تعريف الأدوار التالية:

- **Super Admin**: يملك جميع الصلاحيات
- **Admin**: مدير النظام مع صلاحيات محدودة
- **Editor**: محرر المحتوى
- **User**: مستخدم عادي مع صلاحيات الوصول الأساسية

### 2. ربط المستخدمين بالأدوار

```php
public static function form(Form $form): Form {
    return $form->schema([
        // ...
        Section::make(__('default/users.card.Roles'))->schema([
            Select::make('role')
                ->hiddenLabel()
                ->relationship('roles', 'name')
                ->preload()
                ->multiple()
                ->columnSpanFull()
                ->required(),
        ])->columns(2),
    ]);
}
```

### 3. فلترة المستخدمين حسب الدور

```php
SelectFilter::make('roles')
    ->label(__('default/users.roles'))
    ->relationship('roles', 'name')
    ->multiple()
    ->preload(),
```

## صلاحيات الصفحات

بالإضافة إلى صلاحيات الموارد، يمكن أيضًا تطبيق الصلاحيات على الصفحات المستقلة باستخدام `HasPageShield`:

```php
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class MyProfileCustomPage extends MyProfilePage {
    use HasPageShield;

    // ...
}
```

## أمثلة عملية لتطبيق الصلاحيات

### 1. تقييد الوصول إلى مورد كامل

```php
// لتقييد الوصول إلى المورد بأكمله
protected static function shouldRegisterNavigation(): bool {
    return auth()->user()->can('view_any_user');
}
```

### 2. تقييد تنفيذ إجراء بناءً على الصلاحيات

```php
// في تعريف الإجراء
Action::make('approve')
    ->label('الموافقة')
    ->icon('heroicon-o-check')
    ->visible(fn ($record) => auth()->user()->can('approve', $record))
    ->action(fn ($record) => $record->approve())
```

### 3. التحقق من الصلاحيات في وحدات التحكم

```php
// في وحدة تحكم
public function approve(Post $post)
{
    if (! auth()->user()->can('approve', $post)) {
        abort(403);
    }

    $post->approve();
    
    return redirect()->back()->with('success', 'تمت الموافقة بنجاح');
}
```

### 4. التحقق من صلاحيات مخصصة في النماذج

```php
// في ملف السياسة (Policy)
public function publish(User $user, Post $post)
{
    // التحقق من شروط النشر
    return $user->hasRole('Editor') && !$post->is_published;
}
```

## إدارة الصلاحيات من خلال واجهة Shield

Shield يوفر واجهة رسومية لإدارة الصلاحيات والأدوار:

1. قائمة بجميع الصلاحيات المتاحة
2. إمكانية تعيين صلاحيات متعددة لدور معين
3. إمكانية تعيين أدوار متعددة للمستخدم
4. إنشاء وتعديل وحذف الأدوار

## التحقق من الصلاحيات في قوالب Blade

```php
@can('view_user')
    <!-- المحتوى الذي سيظهر للمستخدمين الذين لديهم صلاحية العرض -->
@endcan

@role('Admin')
    <!-- المحتوى الذي سيظهر للمسؤولين فقط -->
@endrole
```

## استراتيجيات لإدارة الصلاحيات

### 1. تطبيق أقل الصلاحيات اللازمة

استخدم `skipKeys` لإزالة أي صلاحيات غير ضرورية للمورد.

```php
public static function getPermissionPrefixes(): array {
    return static::filterPermissions(
        skipKeys: ['force_delete', 'restore', 'replicate'],
        keepKeys: [],
    );
}
```

### 2. تجميع الصلاحيات المتشابهة

استخدم العلاقات في `filterPermissions` لإزالة مجموعات من الصلاحيات المرتبطة.

### 3. إنشاء سياسات خاصة بالتطبيق

استخدم سياسات Laravel (Policies) لتعريف قواعد معقدة للتحقق من الصلاحيات.

```php
// app/Policies/PostPolicy.php
public function update(User $user, Post $post)
{
    // يمكن للمستخدم تعديل المنشور إذا كان هو الكاتب أو كان لديه دور محرر
    return $user->id === $post->user_id || $user->hasRole('Editor');
}
```

## ملاحظات هامة

1. **المستخدم Super Admin**: يمكنه دائمًا الوصول إلى كل شيء، بغض النظر عن إعدادات الصلاحيات
2. **تحديث الصلاحيات**: عند إضافة صلاحيات جديدة، تأكد من تشغيل الأمر `php artisan shield:generate --all`
3. **التسلسل الهرمي للصلاحيات**: صلاحيات المورد تأخذ الأولوية على الصلاحيات العامة
4. **الأداء**: استخدم Cache لتحسين أداء التحقق من الصلاحيات المتكرر

## تكوين Shield

يمكن تخصيص سلوك Shield من خلال ملف الإعداد `config/filament-shield.php`:

```php
return [
    'shield_resource' => [
        'should_register_navigation' => true,
        'slug' => 'shield/roles',
        'navigation_sort' => -1,
        'navigation_badge' => true,
        'navigation_group' => true,
        'is_globally_searchable' => false,
    ],
    'auth_provider_model' => [
        'fqcn' => 'App\\Models\\User',
    ],
    'super_admin' => [
        'enabled' => true,
        'name' => 'super_admin',
        'define_via_gate' => false,
        'intercept_gate' => 'before', // after
    ],
    'filament_user' => [
        'enabled' => true,
        'name' => 'filament_user',
    ],
    'permission_prefixes' => [
        'resource' => [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
        ],
        'page' => 'page',
        'widget' => 'widget',
    ],
    'entities' => [
        'pages' => true,
        'widgets' => true,
        'resources' => true,
        'custom_permissions' => false,
    ],
    'generator' => [
        'option' => 'policies_and_permissions',
    ],
    'exclude' => [
        'enabled' => true,
        'pages' => [
            'Dashboard',
        ],
        'widgets' => [
            'AccountWidget',
            'FilamentInfoWidget',
        ],
        'resources' => [],
    ],
    'register_role_policy' => [
        'enabled' => true,
    ],
];
```

## الخلاصة

نظام إدارة الصلاحيات في الكور المخصص يوفر تحكمًا دقيقًا ومرنًا في الوصول إلى موارد وصفحات النظام. من خلال الجمع بين مكتبة Shield ونمط SmartResourceTrait، يمكن تخصيص الصلاحيات لكل مورد بشكل فردي وتطبيق قواعد الأعمال المعقدة بسهولة.
