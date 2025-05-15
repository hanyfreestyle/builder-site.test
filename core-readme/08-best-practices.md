# أفضل الممارسات للأمان

### 1. استخدام Policies

- أنشئ Policy لكل نموذج للتحكم في الصلاحيات بشكل مركزي
- استخدم `php artisan make:policy {Name}Policy --model={Model}` لإنشاء ملفات السياسة
- نفذ منطق التحقق من الصلاحيات في دوال السياسة

```php
// app/Policies/ProductPolicy.php
class ProductPolicy {
    public function viewAny(User $user): bool {
        return $user->can('view_any_product');
    }
    
    public function view(User $user, Product $product): bool {
        return $user->can('view_product');
    }
    
    public function update(User $user, Product $product): bool {
        // فقط المالك أو المستخدم الذي لديه صلاحية التعديل
        return $user->id === $product->user_id || $user->can('update_product');
    }
    
    public function publish(User $user, Product $product): bool {
        return $user->can('publish_product');
    }
}
```

### 2. التحقق من المدخلات

- استخدم Form Requests للتحقق من المدخلات
- استخدم `php artisan make:request {Name}Request` لإنشاء طلبات التحقق
- حدد قواعد التحقق في دالة `rules()`

```php
// app/Http/Requests/StoreProductRequest.php
class StoreProductRequest extends FormRequest {
    public function authorize(): bool {
        return $this->user()->can('create_product');
    }
    
    public function rules(): array {
        return [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:2048'], // حد أقصى 2 ميجابايت
        ];
    }
    
    public function messages(): array {
        return [
            'name.required' => __('default/products.validation.name_required'),
            'price.required' => __('default/products.validation.price_required'),
            'price.min' => __('default/products.validation.price_min'),
            'category_id.required' => __('default/products.validation.category_required'),
            'image.image' => __('default/products.validation.image_format'),
            'image.max' => __('default/products.validation.image_size'),
        ];
    }
}
```

### 3. الحماية من هجمات CSRF

- استخدم `@csrf` في نماذج HTML
- تأكد من وجود وسيط `VerifyCsrfToken` في مجموعة الوسطاء
- استخدم `X-CSRF-TOKEN` في طلبات AJAX

```php
// التأكد من تنشيط الحماية من CSRF
protected $middlewareGroups = [
    'web' => [
        // ...
        \App\Http\Middleware\VerifyCsrfToken::class,
    ],
];
```

### 4. الحماية من SQL Injection

- استخدم استعلامات معلمة (Parameterized Queries)
- استخدم Eloquent ORM بدلاً من الاستعلامات المباشرة
- تجنب استخدام `DB::raw` مع مدخلات المستخدم

```php
// استخدام استعلامات معلمة
$products = Product::where('category_id', $categoryId)
    ->where('is_active', true)
    ->orderBy('created_at', 'desc')
    ->get();

// تجنب هذا النوع من الاستعلامات
$products = DB::select("SELECT * FROM products WHERE category_id = $categoryId"); // خطر!
```

### 5. تشفير البيانات الحساسة

- استخدم `Hash` لتشفير كلمات المرور
- استخدم `Crypt` لتشفير البيانات الحساسة
- استخدم HTTPS للاتصالات

```php
// تشفير كلمة المرور
$user->password = Hash::make($request->password);

// تشفير بيانات حساسة
$card = [
    'number' => $request->card_number,
    'expiry' => $request->card_expiry,
    'cvv' => $request->card_cvv,
];
$encryptedCard = Crypt::encrypt(json_encode($card));
```

## أفضل الممارسات للإختبارات

### 1. كتابة اختبارات الوحدة

- اكتب اختبارات لكل نموذج وخدمة
- استخدم `php artisan make:test {Name}Test` لإنشاء ملفات الاختبار
- اختبر حالات النجاح والفشل

```php
// tests/Unit/ProductTest.php
class ProductTest extends TestCase {
    use RefreshDatabase;
    
    /** @test */
    public function it_can_create_a_product() {
        $product = Product::factory()->create();
        
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $product->name,
        ]);
    }
    
    /** @test */
    public function it_can_format_price() {
        $product = Product::factory()->create([
            'price' => 1000.50,
        ]);
        
        $this->assertEquals('$1,000.50', $product->formatted_price);
    }
    
    /** @test */
    public function active_scope_only_returns_active_products() {
        Product::factory()->create(['is_active' => true]);
        Product::factory()->create(['is_active' => false]);
        
        $this->assertEquals(1, Product::active()->count());
    }
}
```

### 2. كتابة اختبارات التكامل

- اختبر تكامل المكونات المختلفة
- اختبر الطلبات والاستجابات HTTP
- استخدم `actingAs` لتمثيل المستخدمين المختلفين

```php
// tests/Feature/ProductManagementTest.php
class ProductManagementTest extends TestCase {
    use RefreshDatabase;
    
    /** @test */
    public function authorized_user_can_create_a_product() {
        $user = User::factory()->create();
        $user->givePermissionTo('create_product');
        
        $category = Category::factory()->create();
        
        $response = $this->actingAs($user)
            ->post(route('products.store'), [
                'name' => 'Test Product',
                'price' => 100,
                'description' => 'Test Description',
                'category_id' => $category->id,
                'is_active' => true,
            ]);
            
        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'user_id' => $user->id,
        ]);
    }
    
    /** @test */
    public function unauthorized_user_cannot_create_a_product() {
        $user = User::factory()->create();
        // لا يتم منح الصلاحية
        
        $category = Category::factory()->create();
        
        $response = $this->actingAs($user)
            ->post(route('products.store'), [
                'name' => 'Test Product',
                'price' => 100,
                'category_id' => $category->id,
            ]);
            
        $response->assertStatus(403); // Forbidden
        $this->assertDatabaseMissing('products', [
            'name' => 'Test Product',
        ]);
    }
}
```

### 3. اختبار مكونات Filament

- اختبر مكونات Filament باستخدام LivewireTesting
- اختبر العمليات مثل إنشاء وتعديل وحذف السجلات
- اختبر الصلاحيات والتحقق من المدخلات

```php
// tests/Feature/Filament/ProductResourceTest.php
class ProductResourceTest extends TestCase {
    use RefreshDatabase;
    
    /** @test */
    public function it_can_render_index_page() {
        $user = User::factory()->create();
        $user->givePermissionTo('view_any_product');
        
        $this->actingAs($user)
            ->get(ProductResource::getUrl('index'))
            ->assertSuccessful();
    }
    
    /** @test */
    public function it_can_create_a_product() {
        $user = User::factory()->create();
        $user->givePermissionTo('create_product');
        
        $category = Category::factory()->create();
        
        Livewire::actingAs($user)
            ->test(CreateProduct::class)
            ->fillForm([
                'name' => 'Test Product',
                'price' => 100,
                'description' => 'Test Description',
                'category_id' => $category->id,
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();
            
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'user_id' => $user->id,
        ]);
    }
}
```

## أفضل الممارسات للتوثيق

### 1. توثيق الكود

- استخدم DocBlocks لتوثيق الدوال والفئات
- وضح الغرض من الكود والمعلمات والقيم المرجعة
- وثق الاستثناءات والتحذيرات

```php
/**
 * احصل على المنتجات المميزة النشطة.
 *
 * @param int $limit عدد المنتجات المطلوبة
 * @return \Illuminate\Database\Eloquent\Collection
 */
public static function getFeaturedActive(int $limit = 10) {
    return static::active()
        ->featured()
        ->latest()
        ->take($limit)
        ->get();
}
```

### 2. توثيق API

- استخدم أدوات مثل Swagger أو OpenAPI لتوثيق واجهات API
- وثق نقاط النهاية (Endpoints) والمعلمات والاستجابات
- وفر أمثلة على الاستخدام

```php
/**
 * @OA\Get(
 *     path="/api/products",
 *     summary="الحصول على قائمة المنتجات",
 *     @OA\Parameter(
 *         name="category_id",
 *         in="query",
 *         description="معرف الفئة للتصفية",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="قائمة المنتجات",
 *     )
 * )
 */
public function index(Request $request) {
    // ...
}
```

### 3. توثيق المشروع

- أنشئ ملف README.md واضح
- وثق متطلبات التثبيت والإعداد
- وثق الميزات الرئيسية وكيفية استخدامها

```markdown
# اسم المشروع

وصف موجز للمشروع.

## المتطلبات

- PHP 8.2 أو أحدث
- Laravel 11.x
- MySQL 8.x

## التثبيت

1. استنساخ المستودع: `git clone https://github.com/username/project.git`
2. تثبيت التبعيات: `composer install`
3. نسخ ملف الإعدادات: `cp .env.example .env`
4. تكوين الإعدادات في ملف `.env`
5. إنشاء مفتاح التطبيق: `php artisan key:generate`
6. تنفيذ الترحيلات: `php artisan migrate --seed`

## الاستخدام

شرح كيفية استخدام المشروع...
```

## أفضل الممارسات للنشر

### 1. إعداد بيئة الإنتاج

- ضبط القيم المناسبة في ملف `.env`
- ضبط الصلاحيات المناسبة للملفات والمجلدات
- تكوين النسخ الاحتياطية التلقائية

```bash
# إعدادات بيئة الإنتاج
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com

# إعدادات الأداء
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# إعدادات البريد الإلكتروني
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=no-reply@example.com
MAIL_PASSWORD=secure-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. تحسين الأداء

- تشغيل أوامر تحسين الأداء قبل النشر
- ضبط ذاكرة التخزين المؤقت
- استخدام Workers للمهام الخلفية

```bash
# تحسين الأداء
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# تشغيل Workers للمهام الخلفية
php artisan queue:work --daemon --tries=3
```

### 3. المراقبة والتسجيل

- إعداد نظام مراقبة لتتبع أداء التطبيق
- تكوين تسجيل الأخطاء
- إعداد التنبيهات للمشاكل الحرجة

```php
// config/logging.php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['daily', 'slack'],
        'ignore_exceptions' => false,
    ],
    
    'daily' => [
        'driver' => 'daily',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
        'days' => 14,
    ],
    
    'slack' => [
        'driver' => 'slack',
        'url' => env('LOG_SLACK_WEBHOOK_URL'),
        'username' => 'Laravel Log',
        'emoji' => ':boom:',
        'level' => 'critical',
    ],
],
```

## خاتمة

اتباع أفضل الممارسات الموضحة في هذا الدليل سيساعد في تطوير مشاريع Laravel + Filament بجودة عالية وقابلة للصيانة والتوسعة. يجب دائمًا تحديث هذه الممارسات مع تطور التقنيات والأدوات المستخدمة.

يمكن تلخيص أهم النقاط في:

1. **التزم بالهيكل المحدد**: اتبع هيكل المشروع والنمط الموحد للترميز
2. **استخدم المكونات المشتركة**: استفد من السمات والمكونات المخصصة لإعادة استخدام الكود
3. **نظم الكود بشكل منطقي**: قسم الكود إلى أقسام منطقية واضحة
4. **استخدم الترجمات بشكل متسق**: وحد استخدام الترجمات عبر المشروع
5. **طبق نظام صلاحيات شامل**: تأكد من تطبيق الصلاحيات على جميع العمليات
6. **اختبر الكود بشكل شامل**: اكتب اختبارات للوظائف الرئيسية
7. **وثق المشروع والكود**: وفر توثيقًا واضحًا للكود والمشروع ككل
8. **حسن الأداء**: استخدم أفضل الممارسات لتحسين أداء التطبيق

من خلال اتباع هذه الممارسات، ستتمكن من بناء مشاريع Laravel + Filament قوية وفعالة ومستدامة باستخدام نظام الكور المخصص.
