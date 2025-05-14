<?php

namespace App\FilamentCustom\UploadFile;

use Filament\Forms\Components\FileUpload;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Encoders\WebpEncoder;
use Illuminate\Support\Facades\Storage;

class WebpUploadFixedSize {
    use UploadFileFunctionTrait;


    protected int $filter = 4;
    protected int $width = 300;
    protected int $height = 300;
    protected int $quality = 90;
    protected bool $generateThumbnail = false;
    protected int $thumbWidth = 100;
    protected int $thumbHeight = 100;


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function make(): static {
        return new static();
    }

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
    public function getColumns(): array {
        $settings = [];
        $settings[] = self::FileUploadImages();
        return $settings;
    }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function FileUploadImages(): FileUpload {
        $fileUpload = FileUpload::make($this->fileName)
            ->label(__('default/lang.columns.photo'))
            ->disk($this->diskDir)
            ->visibility($this->diskDir)
            ->directory($this->uploadDirectory)
            ->acceptedFileTypes(['image/*'])
            ->hiddenLabel()
            ->image()
            ->imageEditor()
            ->downloadable()
            ->deletable(true)
            ->reorderable(true)
            ->dehydrated(true)
            ->preserveFilenames()
            ->deleteUploadedFileUsing(fn($file, $record) => $this->handleFileDeletion($file, $record))
            ->saveUploadedFileUsing(fn($file, $record, $livewire) => $this->handleUploadFixedSize($file, $record, $livewire))
            ->imageCropAspectRatio($this->handleAspectRatioFixedSize())
            ->required($this->requiredUpload);

        return $fileUpload;
    }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
//    protected function handleAspectRatioFixedSize(): string|null {
//        return null;
//    }

    protected function handleAspectRatioFixedSize(): ?string {
        // إذا لم يتم تحديد العرض أو الارتفاع، لا نحتاج لنسبة
        if (!$this->width || !$this->height) {
            return null;
        }
        // إيجاد القاسم المشترك الأكبر (GCD) لتبسيط النسبة
        $gcd = $this->gcd($this->width, $this->height);

        $widthRatio = $this->width / $gcd;
        $heightRatio = $this->height / $gcd;
        // إعادة النسبة بالشكل "عرض:ارتفاع"
        // مثال: لو كانت (300, 300) => "1:1"
        //       لو كانت (1920,1080) => "16:9"
        return $widthRatio . ':' . $heightRatio;
    }

    protected function gcd(int $a, int $b): int {
        return ($b === 0)
            ? $a
            : $this->gcd($b, $a % $b);
    }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function handleUploadFixedSize($file, $record): string {
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
        ];
        $this->processImageFixedSize($manager, $realPath, $newPath, $size);

        if ($this->generateThumbnail) {
            $size = [
                'type' => $this->filter,
                'width' => $this->thumbWidth,
                'height' => $this->thumbHeight,
            ];
            $this->processImageFixedSize($manager, $realPath, $thumbnailPath, $size);
            if ($record) {
                $record->photo_thumbnail = $thumbnailPath;
            }
        }

//        if (!$this->isMultiple()) {
//            if ($this->generateThumbnail && $record) {
//                $record->photo_thumbnail = $thumbnailPath;
//            }
//            return $newPath;
//        }

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

//        $manager = new ImageManager(new GdDriver());
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
