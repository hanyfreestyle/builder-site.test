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
                $fieldName = $this->getFieldName();
                $thumbnailField = $fieldName . $this->thumbnailSuffix;
                $statePath = $this->getStatePath();

                // التعديل هنا: دعم UUID في المكرر
                if (preg_match('/data\.([a-zA-Z0-9_]+)\.([a-fA-F0-9\-]+)\.([a-zA-Z0-9_]+)$/', $statePath, $matches)) {
                    $repeaterName = $matches[1]; // اسم المكرر (مثال: "icons")
                    $itemUuid = $matches[2];     // UUID الخاص بالعنصر
                    $fieldInRepeater = $matches[3]; // اسم الحقل (مثال: "photo")

                    // بناء مسار الصورة المصغرة داخل المكرر
                    $thumbnailFieldPath = "data.{$repeaterName}.{$itemUuid}.{$fieldInRepeater}_thumbnail";

                    // تحديث بيانات الـ Livewire
                    data_set($livewire, $thumbnailFieldPath, $thumbnailPath);

                    // طباعة معلومات التصحيح (يمكن حذفها لاحقًا)
                    Log::info("Thumbnail Path in Repeater: {$thumbnailFieldPath} = {$thumbnailPath}");

                } else if (strpos($statePath, 'data.') === 0) {
                    // الحقول العادية خارج المكرر
                    $livewire->data['data'][$thumbnailField] = $thumbnailPath;
                   Log::info("Thumbnail Path in Main: {$thumbnailField} = {$thumbnailPath}");
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
