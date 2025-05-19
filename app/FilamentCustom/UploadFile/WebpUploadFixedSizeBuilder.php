<?php

namespace App\FilamentCustom\UploadFile;

use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Encoders\WebpEncoder;
use Illuminate\Support\Facades\Storage;

class WebpUploadFixedSizeBuilder extends FileUpload {
    protected int $filter = 4;
    protected int $width = 300;
    protected int $height = 300;
    protected int $quality = 90;
    protected bool $generateThumbnail = false;
    protected int $thumbWidth = 100;
    protected int $thumbHeight = 100;
    protected string $diskDir = 'root_folder';
    protected string $uploadDirectory = 'uploads-site';

    // اسم اللاحقة للصورة المصغرة
    protected string $thumbnailSuffix = '_thumbnail';

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function setFilter(int $filter = 4): static {
        $this->filter = $filter;
        return $this;
    }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function setResize(int $width, int $height, int $quality = 90): static {
        $this->width = $width;
        $this->height = $height;
        $this->quality = $quality;
        return $this;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function setThumbnail(bool $value = true): static {
        $this->generateThumbnail = $value;
        return $this;
    }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function setThumbnailSize(int $width, int $height): static {
        $this->thumbWidth = $width;
        $this->thumbHeight = $height;
        return $this;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function setUploadDirectory(string $dir): static {
        $this->uploadDirectory = $dir;
        return $this;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function setRequiredUpload(bool $value = true): static {
        if ($value) {
            $this->required();
        }
        return $this;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function setThumbnailSuffix(string $suffix = '_thumbnail'): static {
        $this->thumbnailSuffix = $suffix;
        return $this;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function setUp(): void {
        parent::setUp();
        $this->disk($this->diskDir)
            ->visibility('public')
            ->directory($this->uploadDirectory)
            ->acceptedFileTypes(['image/*'])
            ->image()
            ->imageEditor()
            ->downloadable()
            ->deletable(true)
            ->reorderable(true)
            ->dehydrated(true)
            ->preserveFilenames()
            ->deleteUploadedFileUsing(fn($file, $record) => $this->handleFileDeletion($file, $record))
            ->saveUploadedFileUsing(fn($file, $record, $livewire) => $this->handleUploadFixedSize($file, $record, $livewire));
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function handleFileDeletion($file, $record): void {
        // 1. حذف الملف الأساسي من التخزين
        Storage::disk($this->diskDir)->delete($file);

        // 2. التحقق من تفعيل الثمبنايل ووجود السجل
        if ($this->generateThumbnail && $record) {
            // 3. الحصول على اسم الحقل الحالي (مثال: "photo")
            $currentField = $this->getFieldName();

            // 4. بناء اسم حقل الثمبنايل (مثال: "photo_thumbnail")
            $thumbnailField = $currentField . $this->thumbnailSuffix;

            // 5. التحقق من وجود الثمبنايل في الـ data
            if (isset($record->data[$thumbnailField])) {
                // 6. حذف ملف الثمبنايل من التخزين
                Storage::disk($this->diskDir)->delete($record->data[$thumbnailField]);

                if ($record && is_array($record->data)) {
                    $currentField = $this->getFieldName();
                    $thumbnailField = $currentField . $this->thumbnailSuffix;

                    $record->data = array_merge($record->data, [
                        $currentField => null,
                        $thumbnailField => null
                    ]);

                    if (method_exists($record, 'save')) {
                        $record->save();
                    }
                }
            }
        }
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    /*** عملية مساعدة لإنشاء المجلد إذا لم يكن موجودًا */
    protected function ensureDirectoryExists(string $basePath): void {
        $storagePath = Storage::disk($this->diskDir)->path($basePath);
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function resolveFilename($file, $record = null): string {
        $filenameBase = uniqid('img-');
        return Url_Slug($filenameBase);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function getFieldName(): string {
        try {
            $statePath = $this->getStatePath();
            $parts = explode('.', $statePath);
            return end($parts);
        } catch (\Exception $e) {
            return 'photo';
        }
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function handleUploadFixedSize($file, $record, $livewire = null): string {
        // تأكد من وجود الملف المؤقت
        $realPath = $file->getRealPath();
        if (!file_exists($realPath)) {
            throw new \Exception('Temporary file not found: ' . $realPath);
        }

        // تجهيز مدير الصور
        $manager = new ImageManager(new GdDriver());

        // تحضير المسارات
        $basePath = $this->uploadDirectory . '/' . now()->format('Y-m');
        $this->ensureDirectoryExists($basePath);

        // احتساب الاسم النهائي للملف
        $filenameBase = $this->resolveFilename($file, $record);
        $newPath = $basePath . '/' . $filenameBase . '.webp';
        $thumbnailPath = $basePath . '/' . $filenameBase . '_thumb.webp';

        $size = [
            'type' => $this->filter,
            'width' => $this->width,
            'height' => $this->height,
            'quality' => $this->quality
        ];
        $this->processImageFixedSize($manager, $realPath, $newPath, $size);

        // إذا كان توليد الصورة المصغرة مفعلاً
        if ($this->generateThumbnail) {
            $size = [
                'type' => $this->filter,
                'width' => $this->thumbWidth,
                'height' => $this->thumbHeight,
                'quality' => $this->quality
            ];
            $this->processImageFixedSize($manager, $realPath, $thumbnailPath, $size);

            // تحديث بيانات النموذج
            if ($livewire) {
                $statePath = $this->getStatePath();
                $fieldName = $this->getFieldName();
                $thumbnailField = $fieldName . $this->thumbnailSuffix;

                // سجلات للتصحيح
                Log::info('=== بدء عملية التحميل ===');
                Log::info('StatePath: ' . $statePath);
                Log::info('توليد الصورة المصغرة مفعل - Thumbnail: ' . $thumbnailPath);
                Log::info('اسم الحقل: ' . $fieldName . ' | حقل الصورة المصغرة: ' . $thumbnailField);

                // التعامل مع أنماط مختلفة من المسارات

                // نمط 1: المكرر مع UUID (مثل data.data.icons.6c56b753-f969-443b-b72f-6d6fca849f4b.photo)
                if (preg_match('/data\.data\.([a-zA-Z0-9_]+)\.([a-fA-F0-9\-]+)\.([a-zA-Z0-9_]+)$/', $statePath, $matches)) {
                    $repeaterName = $matches[1]; // اسم المكرر (مثال: "icons")
                    $itemUuid = $matches[2];     // UUID الخاص بالعنصر
                    $fieldInRepeater = $matches[3]; // اسم الحقل (مثال: "photo")
                    $thumbnailFieldName = $fieldInRepeater . $this->thumbnailSuffix;

                    // بناء المسار الكامل للصورة المصغرة في المكرر
                    $thumbnailPathInData = "data.{$repeaterName}.{$itemUuid}.{$thumbnailFieldName}";
                    
                    Log::info('نمط المكرر مع UUID: ' . $statePath);
                    Log::info('مسار الـ thumbnail: ' . $thumbnailPathInData);
                    
                    // 1. التحقق من موجودات data
                    if (isset($livewire->data['data'][$repeaterName])) {
                        Log::info('العناصر الموجودة في المكرر: ' . implode(', ', array_keys($livewire->data['data'][$repeaterName])));

                        // 2. التحقق من وجود العنصر المحدد في المكرر
                        if (isset($livewire->data['data'][$repeaterName][$itemUuid])) {
                            // 3. إضافة الصورة المصغرة إلى العنصر
                            $livewire->data['data'][$repeaterName][$itemUuid][$thumbnailFieldName] = $thumbnailPath;
                            Log::info('تم تحديث البيانات المتداخلة مع UUID: ' . $thumbnailFieldName . ' = ' . $thumbnailPath);
                        } else {
                            Log::warning('لم يتم العثور على العنصر في المكرر: ' . $itemUuid);
                            // المحاولة باستخدام data_set
                            data_set($livewire, $thumbnailPathInData, $thumbnailPath);
                            Log::info('محاولة تحديث باستخدام data_set: ' . $thumbnailPathInData);
                        }
                    } else {
                        Log::warning('لم يتم العثور على المكرر: ' . $repeaterName);
                        // المحاولة باستخدام data_set
                        data_set($livewire, $thumbnailPathInData, $thumbnailPath);
                        Log::info('محاولة تحديث باستخدام data_set: ' . $thumbnailPathInData);
                    }

                    // 4. طريقة بديلة باستخدام data_set مباشرة
                    data_set($livewire, $thumbnailPathInData, $thumbnailPath);
                    Log::info('تم استخدام data_set أيضًا: ' . $thumbnailPathInData . ' = ' . $thumbnailPath);
                }
                // نمط 2: المكرر بدون data.data (مثل data.icons.6c56b753-f969-443b-b72f-6d6fca849f4b.photo)
                else if (preg_match('/data\.([a-zA-Z0-9_]+)\.([a-fA-F0-9\-]+)\.([a-zA-Z0-9_]+)$/', $statePath, $matches)) {
                    $repeaterName = $matches[1];
                    $itemUuid = $matches[2];
                    $fieldInRepeater = $matches[3];
                    $thumbnailFieldName = $fieldInRepeater . $this->thumbnailSuffix;

                    $thumbnailPathInData = "data.{$repeaterName}.{$itemUuid}.{$thumbnailFieldName}";
                    
                    Log::info('نمط المكرر بدون data.data: ' . $statePath);
                    Log::info('مسار الـ thumbnail: ' . $thumbnailPathInData);
                    
                    // التحقق والتحديث
                    if (isset($livewire->data[$repeaterName][$itemUuid])) {
                        $livewire->data[$repeaterName][$itemUuid][$thumbnailFieldName] = $thumbnailPath;
                        Log::info('تم تحديث البيانات في المكرر بدون data.data: ' . $thumbnailFieldName . ' = ' . $thumbnailPath);
                    } else {
                        data_set($livewire, $thumbnailPathInData, $thumbnailPath);
                        Log::info('تم استخدام data_set للنمط 2: ' . $thumbnailPathInData . ' = ' . $thumbnailPath);
                    }
                }
                // نمط 3: الحقل العادي في data.data (مثل data.data.hany)
                else if (strpos($statePath, 'data.data.') === 0) {
                    $livewire->data['data'][$thumbnailField] = $thumbnailPath;
                    Log::info('نمط الحقل العادي في data.data: ' . $statePath);
                    Log::info('العناصر الموجودة في data: ' . implode(', ', array_keys($livewire->data['data'])));
                    Log::info('تم تحديث الحقل الرئيسي: ' . $thumbnailField . ' = ' . $thumbnailPath);
                    Log::info('التحقق من النتيجة للحقل الرئيسي: ' . $livewire->data['data'][$thumbnailField]);
                }
                // نمط 4: الحقل العادي في data (مثل data.hany)
                else if (strpos($statePath, 'data.') === 0) {
                    $livewire->data[$thumbnailField] = $thumbnailPath;
                    Log::info('نمط الحقل العادي في data: ' . $statePath);
                    Log::info('تم تحديث الحقل الرئيسي في data: ' . $thumbnailField . ' = ' . $thumbnailPath);
                }
                // نمط 5: أي حالة أخرى - استخدام data_set للتأكد
                else {
                    $pathParts = explode('.', $statePath);
                    array_pop($pathParts); // إزالة اسم الحقل
                    $basePath = implode('.', $pathParts);
                    $thumbnailPath_full = $basePath . '.' . $thumbnailField;
                    
                    Log::info('نمط آخر: ' . $statePath);
                    Log::info('محاولة تحديث في المسار: ' . $thumbnailPath_full);
                    
                    data_set($livewire, $thumbnailPath_full, $thumbnailPath);
                }

                Log::info('=== انتهت عملية التحميل ===');
            }
        }

        // حذف الملف الأصلي من temp بعد إتمام المعالجة
        Storage::disk($this->diskDir)->delete($file);

        // إعادة مسار الصورة الأساسية
        return $newPath;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    /*** تنفيذ المعالجة الفعلية بواسطة Intervention*/
    protected function processImageFixedSize(ImageManager $manager, $realPath, $savePath, $data = array()): void {

        $type = $data['type'] ?? 1;
        $width = $data['width'] ?? 300;
        $height = $data['height'] ?? 300;
        $canvas = $data['canvas'] ?? '#ffffff';
        $quality = $data['quality'] ?? 85;

        $savePath = Storage::disk($this->diskDir)->path($savePath);

        $image = $manager->read($realPath);

        switch ($type) {
            case 1:
                $image->encode(new WebpEncoder($quality));
                break;
            case 2:
                // scaleDown مع عرض محدد
                $image->scaleDown(width: $width)->encode(new WebpEncoder($quality));
                break;
            case 3:
                // scaleDown مع ارتفاع محدد
                $image->scaleDown(height: $height)->encode(new WebpEncoder($quality));
                break;
            case 4:
                // cover
                $image->cover($width, $height)->encode(new WebpEncoder($quality));
                break;
            case 5:
                // contain
                $image->contain($width, $height, $canvas)->encode(new WebpEncoder($quality));
                break;
            default:
                // القيمة الافتراضية: cover
                $image->cover($width, $height)->encode(new WebpEncoder($quality));
                break;
        }

        $image->save($savePath);
    }
}
