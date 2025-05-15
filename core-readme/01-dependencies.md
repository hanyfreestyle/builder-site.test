# المتطلبات والإعتماديات

## نسخ المكتبات الرئيسية

يعتمد نظام الكور المخصص على مجموعة محددة من المكتبات والإصدارات. يجب الالتزام بهذه النسخ لضمان توافق المكونات وتجنب أي مشاكل قد تنشأ من استخدام إصدارات غير متوافقة.

### المتطلبات الأساسية

| المكتبة | النسخة | الوصف |
|---------|--------|-------|
| PHP | `^8.2` | نسخة PHP المطلوبة |
| Laravel | `^11.0` | إطار عمل Laravel |
| Filament | `^3.3` | إطار عمل Filament لبناء لوحات التحكم |

### المكتبات المستخدمة في الكور

#### مكتبات Filament الأساسية والإضافات

| المكتبة | النسخة | الوصف |
|---------|--------|-------|
| `filament/filament` | `^3.3` | المكتبة الأساسية لـ Filament |
| `bezhansalleh/filament-shield` | `^3.3` | إدارة الصلاحيات والأدوار |
| `bezhansalleh/filament-language-switch` | `^3.1` | تبديل اللغات في واجهة Filament |
| `jeffgreco13/filament-breezy` | `^2.6` | تعزيزات للمصادقة وإدارة المستخدمين |
| `malzariey/filament-daterangepicker-filter` | `^3.2` | عنصر فلتر بنطاق تاريخي |
| `swisnl/filament-backgrounds` | `^1.1` | تخصيص خلفيات الواجهة |
| `ysfkaya/filament-phone-input` | `^3.1` | حقل إدخال رقم الهاتف |
| `hanyfreestyle/dev-filament-icon-picker` | `dev-master` | أداة اختيار الأيقونات |
| `hanyfreestyle/filament-locationpickr-field` | `dev-master` | حقل اختيار الموقع |

#### مكتبات Laravel الإضافية

| المكتبة | النسخة | الوصف |
|---------|--------|-------|
| `artesaos/seotools` | `^1.3` | أدوات تحسين محركات البحث (SEO) |
| `astrotomic/laravel-translatable` | `^11.15` | إدارة المحتوى متعدد اللغات |
| `blade-ui-kit/blade-icons` | `^1.8` | دعم الأيقونات في Blade |
| `diglactic/laravel-breadcrumbs` | `^10.0` | إدارة مسارات التنقل |
| `intervention/image` | `^3.11` | معالجة الصور |
| `jenssegers/agent` | `^2.6` | الكشف عن متصفح المستخدم وجهازه |
| `mcamara/laravel-localization` | `^2.3` | دعم تعدد اللغات في المسارات |
| `spatie/valuestore` | `^1.3` | تخزين القيم والإعدادات |
| `staudenmeir/laravel-adjacency-list` | `^1.23` | دعم العلاقات الهرمية |
| `league/color-extractor` | `^0.4.0` | استخراج الألوان من الصور |
| `mpdf/mpdf` | `^8.2` | إنشاء ملفات PDF |
| `owenvoke/blade-fontawesome` | `^2.9` | أيقونات Font Awesome |

#### أدوات التطوير

| المكتبة | النسخة | الوصف |
|---------|--------|-------|
| `barryvdh/laravel-debugbar` | `^3.15` | شريط تصحيح للتطوير |
| `laravel/pint` | `^1.13` | أداة لتنسيق الكود |
| `fakerphp/faker` | `^1.23` | توليد بيانات وهمية للاختبار |
| `nunomaduro/collision` | `^8.0` | تحسين عرض الأخطاء |
| `spatie/laravel-ignition` | `^2.4` | واجهة تصحيح الأخطاء |

## مقتطف من composer.json

```json
{
    "require": {
        "php": "^8.2",
        "artesaos/seotools": "^1.3",
        "astrotomic/laravel-translatable": "^11.15",
        "bezhansalleh/filament-language-switch": "^3.1",
        "bezhansalleh/filament-shield": "^3.3",
        "blade-ui-kit/blade-icons": "^1.8",
        "diglactic/laravel-breadcrumbs": "^10.0",
        "filament/filament": "^3.3",
        "hanyfreestyle/dev-filament-icon-picker": "dev-master",
        "hanyfreestyle/filament-locationpickr-field": "dev-master",
        "intervention/image": "^3.11",
        "jeffgreco13/filament-breezy": "^2.6",
        "jenssegers/agent": "^2.6",
        "laravel/framework": "^11.0",
        "laravel/tinker": "^2.9",
        "league/color-extractor": "^0.4.0",
        "league/commonmark": "^2.6",
        "malzariey/filament-daterangepicker-filter": "^3.2",
        "mcamara/laravel-localization": "^2.3",
        "mpdf/mpdf": "^8.2",
        "owenvoke/blade-fontawesome": "^2.9",
        "spatie/valuestore": "^1.3",
        "staudenmeir/laravel-adjacency-list": "^1.23",
        "swisnl/filament-backgrounds": "^1.1",
        "ysfkaya/filament-phone-input": "^3.1"
    },
    "require-dev": {
        "barryvdh/laravel-debugbar": "^3.15",
        "fakerphp/faker": "^1.23",
        "laravel/pint": "^1.13",
        "laravel/sail": "^1.26",
        "mockery/mockery": "^1.6",
        "nunomaduro/collision": "^8.0",
        "phpunit/phpunit": "^10.5",
        "spatie/laravel-ignition": "^2.4"
    }
}
```

## ملاحظات هامة حول النسخ

- **نسخة PHP:** المشروع يتطلب PHP 8.2 أو أحدث، لذا يجب التأكد من توافق بيئة التطوير.
- **نسخة Laravel:** المشروع يستخدم Laravel 11، وهي أحدث نسخة رئيسية من Laravel.
- **نسخة Filament:** المشروع يعتمد على Filament 3.3، وهي متوافقة مع Laravel 11.
- **المكتبات المخصصة:** هناك بعض المكتبات من `hanyfreestyle` تستخدم إصدار `dev-master`، وهذه المكتبات قد تتطلب تحديثات دورية.

## التثبيت

لتثبيت جميع المتطلبات بالنسخ الصحيحة، قم بتنفيذ الأمر التالي:

```bash
composer install
```

للتأكد من تحديث جميع المكتبات إلى آخر إصدار متوافق:

```bash
composer update
```

## التوافق

تم اختبار هذه النسخ معًا للتأكد من توافقها وعملها بشكل صحيح. في حالة ظهور أي مشاكل تتعلق بالتوافق، يرجى التحقق من النسخ المثبتة ومقارنتها بالنسخ المحددة في هذا الدليل.
