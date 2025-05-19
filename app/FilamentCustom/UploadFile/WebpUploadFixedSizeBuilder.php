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
            ->deleteUploadedFileUsing(fn($file, $record,$livewire) => $this->handleFileDeletion($file, $record,$livewire))
            ->saveUploadedFileUsing(fn($file, $record, $livewire) => $this->handleUploadFixedSize($file, $record, $livewire));
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function handleFileDeletion_is_work($file, $record): void {
        try {
            Log::info("==== بدء عملية الحذف ====");
            Log::info("الملف المراد حذفه: {$file}");

            // 1. حذف الملف الأساسي
            if (Storage::disk($this->diskDir)->exists($file)) {
                Storage::disk($this->diskDir)->delete($file);
                Log::info("تم حذف الملف الأساسي: {$file}");
            } else {
                Log::warning("الملف الأساسي غير موجود: {$file}");
            }

            // 2. الحصول على مسار الحقل وتحليله
            $statePath = $this->getStatePath();
            Log::info("مسار الحقل (statePath): {$statePath}");

            // 3. تحديد إذا كان الحقل داخل مكرر (مع UUID)
            $isInRepeater = preg_match(
                '/^data\.data\.([a-zA-Z0-9_]+)\.([a-f0-9\-]{36})\.([a-zA-Z0-9_]+)$/',
                $statePath,
                $matches
            );

            if ($isInRepeater) {
                Log::info("تم التعرف على الحقل داخل مكرر", $matches);
                $repeaterName = $matches[1]; // مثال: "icons"
                $itemUuid = $matches[2];     // مثال: "e5c3a475-55e4-4b3f-a62e-c7b6c441433b"
                $currentField = $matches[3];  // مثال: "photo"
                $thumbnailField = $currentField . $this->thumbnailSuffix;

                // 4. نسخ البيانات لتجنب مشكلة التعديل غير المباشر
                $data = $record->data;
                Log::info("البيانات قبل التعديل:", $data);

                // المكرر موجود ولكن قد يكون كمصفوفة عددية وليس مصفوفة مفتاحية
                if (isset($data[$repeaterName]) && is_array($data[$repeaterName])) {
                    $foundIndex = null;

                    // البحث في جميع عناصر المكرر
                    foreach ($data[$repeaterName] as $index => $item) {
                        // نبحث بناءً على محتوى الحقل - الصورة يجب أن تكون موجودة لنتأكد من أننا نحذف العنصر الصحيح
                        if (isset($item[$currentField]) && $item[$currentField] === $file) {
                            $foundIndex = $index;
                            break;
                        }

                        // إذا لم نجد الملف مباشرة، قد نبحث عن UUID في العنصر (إذا كان مخزنًا)
                        if (isset($item['uuid']) && $item['uuid'] === $itemUuid) {
                            $foundIndex = $index;
                            break;
                        }
                    }

                    if ($foundIndex !== null) {
                        Log::info("تم العثور على العنصر في المكرر بالفهرس: {$foundIndex}");

                        // 5. حذف ملف الثمبنايل إذا وجد
                        $thumbnailFile = $data[$repeaterName][$foundIndex][$thumbnailField] ?? null;
                        if ($thumbnailFile) {
                            if (Storage::disk($this->diskDir)->exists($thumbnailFile)) {
                                Storage::disk($this->diskDir)->delete($thumbnailFile);
                                Log::info("تم حذف ملف الثمبنايل: {$thumbnailFile}");
                            } else {
                                Log::warning("ملف الثمبنايل غير موجود: {$thumbnailFile}");
                            }
                        }

                        // 6. تحديث البيانات داخل المكرر
                        $data[$repeaterName][$foundIndex][$currentField] = null;
                        $data[$repeaterName][$foundIndex][$thumbnailField] = null;
                        Log::info("البيانات بعد التعديل:", $data[$repeaterName][$foundIndex]);

                        // 7. حفظ التغييرات
                        $record->data = $data;
                        if ($record->save()) {
                            Log::info("تم تحديث السجل بنجاح");
                        } else {
                            Log::error("فشل في حفظ التغييرات");
                        }
                    } else {
                        Log::error("لم يتم العثور على العنصر في المكرر {$repeaterName} بالملف {$file}");

                        // طريقة بديلة للبحث عن العنصر المناسب
                        // إذا كان هناك عنصر واحد فقط، قد نفترض أنه هو العنصر المطلوب
                        if (count($data[$repeaterName]) === 1) {
                            Log::info("المكرر يحتوي على عنصر واحد فقط، سنقوم بتحديثه");

                            $data[$repeaterName][0][$currentField] = null;
                            if (isset($data[$repeaterName][0][$thumbnailField])) {
                                $thumbnailFile = $data[$repeaterName][0][$thumbnailField];
                                if (Storage::disk($this->diskDir)->exists($thumbnailFile)) {
                                    Storage::disk($this->diskDir)->delete($thumbnailFile);
                                    Log::info("تم حذف ملف الثمبنايل: {$thumbnailFile}");
                                }
                                $data[$repeaterName][0][$thumbnailField] = null;
                            }

                            $record->data = $data;
                            if ($record->save()) {
                                Log::info("تم تحديث السجل بنجاح");
                            } else {
                                Log::error("فشل في حفظ التغييرات");
                            }
                        }
                    }
                } else {
                    Log::error("المكرر غير موجود: {$repeaterName}");
                }

            } else {
                // 8. التعامل مع الحقول العادية
                $currentField = $this->getFieldName();
                $thumbnailField = $currentField . $this->thumbnailSuffix;
                Log::info("حقل عادي - الحقل الحالي: {$currentField}, الثمبنايل: {$thumbnailField}");

                $data = $record->data;
                Log::info("البيانات قبل التعديل:", $data);

                // 9. حذف ملف الثمبنايل إذا وجد
                $thumbnailFile = $data[$thumbnailField] ?? null;
                if ($thumbnailFile) {
                    if (Storage::disk($this->diskDir)->exists($thumbnailFile)) {
                        Storage::disk($this->diskDir)->delete($thumbnailFile);
                        Log::info("تم حذف ملف الثمبنايل: {$thumbnailFile}");
                    } else {
                        Log::warning("ملف الثمبنايل غير موجود: {$thumbnailFile}");
                    }
                }

                // 10. تحديث البيانات
                $data[$currentField] = null;
                $data[$thumbnailField] = null;
                Log::info("البيانات بعد التعديل:", $data);

                $record->data = $data;
                if ($record->save()) {
                    Log::info("تم تحديث السجل بنجاح");
                } else {
                    Log::error("فشل في حفظ التغييرات");
                }
            }

            Log::info("==== انتهت عملية الحذف ====");

        } catch (\Exception $e) {
            Log::error("حدث خطأ أثناء الحذف: " . $e->getMessage());
            Log::error("تفاصيل الخطأ: " . $e->getTraceAsString());
        }
    }

    protected function handleFileDeletion($file, $record,$livewire): void {
        // التأكد من وجود الملف والسجل
        if (!$record || !$file) {
            return;
        }

        try {
            // 1. حذف الملف الأساسي من التخزين
            Storage::disk($this->diskDir)->delete($file);

            // 2. الحصول على مسار الحالة لتحديد نوع الحقل
            $statePath = $this->getStatePath();
            $currentField = $this->getFieldName();
            $thumbnailField = $currentField . $this->thumbnailSuffix;

            // 3. تحديد إذا كان الحقل داخل مكرر (مع UUID)
            $isInRepeater = preg_match(
                '/^data\.data\.([a-zA-Z0-9_]+)\.([a-f0-9\-]{36})\.([a-zA-Z0-9_]+)$/',
                $statePath,
                $matches
            );

            // نسخ بيانات السجل لتجنب المشاكل أثناء التعديل
            $data = $record->data;

            if ($isInRepeater) {
                // 4. معالجة الحقول داخل المكرر
                $repeaterName = $matches[1]; // مثال: "icons"
                $itemUuid = $matches[2];     // مثال: "uuid-string"
                $fieldInRepeater = $matches[3]; // مثال: "photo"
                $thumbnailFieldName = $fieldInRepeater . $this->thumbnailSuffix;

                // 5. التأكد من وجود المكرر
                if (isset($data[$repeaterName]) && is_array($data[$repeaterName])) {
                    $foundIndex = null;

                    // 6. البحث عن العنصر المناسب في المكرر
                    foreach ($data[$repeaterName] as $index => $item) {
                        // البحث باستخدام مسار الملف
                        if (isset($item[$fieldInRepeater]) && $item[$fieldInRepeater] === $file) {
                            $foundIndex = $index;
                            break;
                        }

                        // البحث باستخدام UUID إذا كان موجوداً
                        if (isset($item['uuid']) && $item['uuid'] === $itemUuid) {
                            $foundIndex = $index;
                            break;
                        }
                    }

                    // 7. إذا وجدنا العنصر، نقوم بتحديثه
                    if ($foundIndex !== null) {
                        // حذف الصورة المصغرة من التخزين
                        if ($this->generateThumbnail && isset($data[$repeaterName][$foundIndex][$thumbnailFieldName])) {
                            Storage::disk($this->diskDir)->delete($data[$repeaterName][$foundIndex][$thumbnailFieldName]);
                        }

                        // تعيين قيم الحقول إلى null
                        $data[$repeaterName][$foundIndex][$fieldInRepeater] = null;
                        if ($this->generateThumbnail) {
                            $data[$repeaterName][$foundIndex][$thumbnailFieldName] = null;
                        }
                    }
                    // 8. إذا لم نجد العنصر وكان هناك عنصر واحد فقط، نفترض أنه العنصر المطلوب
                    elseif (count($data[$repeaterName]) === 1) {
                        if ($this->generateThumbnail && isset($data[$repeaterName][0][$thumbnailFieldName])) {
                            Storage::disk($this->diskDir)->delete($data[$repeaterName][0][$thumbnailFieldName]);
                            $data[$repeaterName][0][$thumbnailFieldName] = null;
                        }
                        $data[$repeaterName][0][$fieldInRepeater] = null;
                    }
                }
            } else {
                // 9. معالجة الحقول العادية
                // حذف الصورة المصغرة من التخزين
                if ($this->generateThumbnail && isset($data[$thumbnailField])) {
                    Storage::disk($this->diskDir)->delete($data[$thumbnailField]);
                }

                // تعيين قيم الحقول إلى null
                $data[$currentField] = null;
                if ($this->generateThumbnail) {
                    $data[$thumbnailField] = null;
                }
            }

            // 10. حفظ التغييرات
            $record->data = $data;
            if (method_exists($record, 'save')) {
                $record->save();
                try {
                    // محاولة استخدام طريقة redirect إذا كانت متاحة
                    $livewire->redirect(request()->header('Referer'));
                } catch (\Exception $e) {
                    try {
                        // محاولة باستخدام الـ dispatchBrowserEvent
                        $livewire->dispatchBrowserEvent('refresh-page', []);
                    } catch (\Exception $e) {
                        // محاولة أخيرة باستخدام JavaScript مباشرة
                        echo "<script>window.location.reload();</script>";
                    }
                }
            }
        } catch (\Exception $e) {
            // الاستمرار في الكود حتى في حالة حدوث خطأ
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

                // التعامل مع أنماط مختلفة من المسارات

                // نمط 1: المكرر مع UUID (مثل data.data.icons.6c56b753-f969-443b-b72f-6d6fca849f4b.photo)
                if (preg_match('/data\.data\.([a-zA-Z0-9_]+)\.([a-fA-F0-9\-]+)\.([a-zA-Z0-9_]+)$/', $statePath, $matches)) {
                    $repeaterName = $matches[1]; // اسم المكرر (مثال: "icons")
                    $itemUuid = $matches[2];     // UUID الخاص بالعنصر
                    $fieldInRepeater = $matches[3]; // اسم الحقل (مثال: "photo")
                    $thumbnailFieldName = $fieldInRepeater . $this->thumbnailSuffix;

                    // بناء المسار الكامل للصورة المصغرة في المكرر
                    $thumbnailPathInData = "data.{$repeaterName}.{$itemUuid}.{$thumbnailFieldName}";

                    // 1. التحقق من موجودات data
                    if (isset($livewire->data['data'][$repeaterName])) {
                        // 2. التحقق من وجود العنصر المحدد في المكرر
                        if (isset($livewire->data['data'][$repeaterName][$itemUuid])) {
                            // 3. إضافة الصورة المصغرة إلى العنصر
                            $livewire->data['data'][$repeaterName][$itemUuid][$thumbnailFieldName] = $thumbnailPath;
                        } else {
                            // المحاولة باستخدام data_set
                            data_set($livewire, $thumbnailPathInData, $thumbnailPath);
                        }
                    } else {
                        // المحاولة باستخدام data_set
                        data_set($livewire, $thumbnailPathInData, $thumbnailPath);
                    }

                    // 4. طريقة بديلة باستخدام data_set مباشرة
                    data_set($livewire, $thumbnailPathInData, $thumbnailPath);
                }
                // نمط 2: المكرر بدون data.data (مثل data.icons.6c56b753-f969-443b-b72f-6d6fca849f4b.photo)
                else if (preg_match('/data\.([a-zA-Z0-9_]+)\.([a-fA-F0-9\-]+)\.([a-zA-Z0-9_]+)$/', $statePath, $matches)) {
                    $repeaterName = $matches[1];
                    $itemUuid = $matches[2];
                    $fieldInRepeater = $matches[3];
                    $thumbnailFieldName = $fieldInRepeater . $this->thumbnailSuffix;

                    $thumbnailPathInData = "data.{$repeaterName}.{$itemUuid}.{$thumbnailFieldName}";

                    // التحقق والتحديث
                    if (isset($livewire->data[$repeaterName][$itemUuid])) {
                        $livewire->data[$repeaterName][$itemUuid][$thumbnailFieldName] = $thumbnailPath;
                    } else {
                        data_set($livewire, $thumbnailPathInData, $thumbnailPath);
                    }
                }
                // نمط 3: الحقل العادي في data.data (مثل data.data.hany)
                else if (strpos($statePath, 'data.data.') === 0) {
                    $livewire->data['data'][$thumbnailField] = $thumbnailPath;
                }
                // نمط 4: الحقل العادي في data (مثل data.hany)
                else if (strpos($statePath, 'data.') === 0) {
                    $livewire->data[$thumbnailField] = $thumbnailPath;
                }
                // نمط 5: أي حالة أخرى - استخدام data_set للتأكد
                else {
                    $pathParts = explode('.', $statePath);
                    array_pop($pathParts); // إزالة اسم الحقل
                    $basePath = implode('.', $pathParts);
                    $thumbnailPath_full = $basePath . '.' . $thumbnailField;

                    data_set($livewire, $thumbnailPath_full, $thumbnailPath);
                }
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
