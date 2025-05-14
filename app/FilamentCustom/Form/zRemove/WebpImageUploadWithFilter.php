<?php

namespace App\FilamentCustom\Form\zRemove;

use App\Models\WebSetting\UploadFilter;
use Closure;
use Filament\Forms\Components\FileUpload;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Encoders\WebpEncoder;
use Illuminate\Support\Facades\Storage;


class WebpImageUploadWithFilter extends FileUpload {
    protected string $uploadDirectory = 'profile_photos';
    protected string $diskDir = 'root_folder';
    protected int $width = 300;
    protected int $height = 300;
    protected int $quality = 90;
    protected bool $generateThumbnail = false;
    protected int $thumbWidth = 100;
    protected int $thumbHeight = 100;
    protected Closure|string|null $renameTo = null;
    protected int|null $filterId = null;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function setFilterId(int $filterId): static {
        $this->filterId = $filterId;
        return $this;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function setDiskDir(string $diskDir): static {
        $this->diskDir = $diskDir;
        return $this;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function uploadDirectory(string $directory): static {
        $this->uploadDirectory = $directory;
        return $this;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function resize(int $width, int $height, int $quality = 90): static {
        $this->width = $width;
        $this->height = $height;
        $this->quality = $quality;
        return $this;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function enableThumbnail(bool $value = true): static {
        $this->generateThumbnail = $value;
        return $this;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function thumbnailSize(int $width, int $height): static {
        $this->thumbWidth = $width;
        $this->thumbHeight = $height;
        return $this;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function fileName(Closure|string $name): static {
        $this->renameTo = $name;
        return $this;
    }

    protected function sanitizeFileName(string $name): string {
        return Url_Slug($name);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function setUp(): void {
        parent::setUp();

        $this
            ->acceptedFileTypes(['image/*'])
            ->hiddenLabel()
            ->image()
            ->imageEditor()
            ->disk($this->diskDir)
            ->directory($this->uploadDirectory)
            ->downloadable()
            ->nullable()
            ->deletable(true)
            ->reorderable(true)
            ->preserveFilenames()
            ->visibility($this->diskDir)
            ->deleteUploadedFileUsing(function ($file, $record) {
                Storage::disk($this->diskDir)->delete($file);
                // حذف الصورة المصغرة لو موجودة
                if ($record && $record->photo_thumbnail) {
                    Storage::disk($this->diskDir)->delete($record->photo_thumbnail);
                    $record->photo_thumbnail = null; // عشان تتحدث في DB
                }
            })
            ->dehydrated(true)
            ->saveUploadedFileUsing(function ($file, $record) {

                $filterId = $this->getLivewire()->data['filter_id'] ?? 1;
                $filter = UploadFilter::query()->with('sizes')->withCount('sizes')->find($filterId);

                $manager = new ImageManager(new GdDriver());
                $month = now()->format('Y-m');

                // دايمًا هنشتغل على temp real path
                $realPath = $file->getRealPath();

                // لو فعلاً موجود في temp نكمل عادي
                if (!file_exists($realPath)) {
                    // هنا fallback خفيف لو temp فعلاً اتحذف
                    throw new \Exception('Temporary file not found: ' . $realPath);
                }

                $basePath = $this->uploadDirectory . '/' . $month;;
                $storagePath = Storage::disk($this->diskDir)->path($basePath);

                // ✅ حل المشكلة: تأكد إن المجلد موجود
                if (!file_exists($storagePath)) {
                    mkdir($storagePath, 0755, true);
                }

                // logic الاسم المخصص أو التلقائي
                $filenameBase = is_callable($this->renameTo)
                    ? call_user_func($this->renameTo, $file, $record)
                    : ($this->renameTo ?? uniqid('img_'));

                // sanitize للاسم النهائي
                $filenameBase = $this->sanitizeFileName($filenameBase);

                // مسارات الصور
                $newPath = $basePath . '/' . $filenameBase . '.webp';
                $thumbnailPath = $basePath . '/' . $filenameBase . '_thumb.webp';

                $this->processImage(
                    manager: $manager,
                    realPath: $realPath,
                    savePath: Storage::disk($this->diskDir)->path($newPath),
                    type: $filter->type,
                    width: $filter->width,
                    height: $filter->height,
                    canvas: $filter->canvas_back,
                    quality: $filter->quality_val
                );

                $firstSize = $filter->sizes->first();
                if ($firstSize) {
                    $this->processImage(
                        manager: $manager,
                        realPath: $realPath,
                        savePath: Storage::disk($this->diskDir)->path($thumbnailPath),
                        type: $firstSize->type,
                        width: $firstSize->width,
                        height: $firstSize->height,
                        canvas: $firstSize->canvas_back ?? '#ffffff',
                        quality: $filter->quality_val
                    );

                    if ($record) {
                        $record->photo_thumbnail = $thumbnailPath;
                    }

                }

                Storage::disk($this->diskDir)->delete($file);
                return $newPath;

            });

        $this->afterStateHydrated(function () {
            $filterId = $this->filterId ?? 1;
            $filter = UploadFilter::query()->with('sizes')->withCount('sizes')->find($filterId);
            if ($filter && filled($filter->crop_aspect_ratio)) {
                $this->imageCropAspectRatio($filter->crop_aspect_ratio);
            }
        });

    }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function processImage(
        ImageManager $manager,
        string       $realPath,
        string       $savePath,
        int          $type,
        int          $width,
        int          $height,
        string       $canvas = '#ffffff',
        int          $quality = 85 ): void {
        $image = $manager->read($realPath);

        switch ($type) {
            case 1:
                $image->encode(new WebpEncoder($quality));
                break;
            case 2:
                $image->scaleDown(width: $width)->encode(new WebpEncoder($quality));
                break;
            case 3:
                $image->scaleDown(height: $height)->encode(new WebpEncoder($quality));
                break;
            case 4:
                $image->cover($width, $height)->encode(new WebpEncoder($quality));
                break;
            case 5:
                $image->contain($width, $height, $canvas)->encode(new WebpEncoder($quality));
                break;
            default:
                $image->cover($width, $height)->encode(new WebpEncoder($quality));
                break;
        }
        $image->save($savePath);
    }

}
