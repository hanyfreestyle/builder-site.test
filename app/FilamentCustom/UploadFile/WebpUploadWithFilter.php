<?php

namespace App\FilamentCustom\UploadFile;

use App\Models\WebSetting\UploadFilter;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Encoders\WebpEncoder;
use Illuminate\Support\Facades\Storage;

class WebpUploadWithFilter {
    use UploadFileFunctionTrait;

    protected int $width = 300;
    protected int $height = 300;
    protected int $quality = 90;


    public static function make(): static {
        return new static();
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function getColumns(): array {
        $filterId = $this->filterId ?? 0;
        $filter = UploadFilter::query()->with('sizes')->withCount('sizes')->find($filterId);
        $settings = [];
        if ($filter) {
            $settings[] = self::FileUploadImages($filter);
            $settings[] = Placeholder::make('')->live()
                ->content(view('components.admin.settings.print_notes', compact('filter')));

            if ($this->changeFilter) {
                $settings[] = self::SelectFiled($filterId);
            } else {
                if ($this->canChangeFilter) {
                    $settings[] = Placeholder::make('')->live()
                        ->content(view('components.admin.settings.change-filter', compact('filter')));
                }
            }

            $settings[] = self::HiddenInputFiled($filterId);
        } else {
            $settings[] = Placeholder::make('')
                ->content(view('components.admin.settings.missing-filter'));
        }
        return $settings;
    }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function FileUploadImages($filter): FileUpload {

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
            ->multiple($this->multipleFiles)
            ->previewable($this->previewAble)
            ->deletable(true)
            ->reorderable(true)
            ->dehydrated(true)
            ->preserveFilenames()
            ->deleteUploadedFileUsing(fn($file, $record) => $this->handleFileDeletion($file, $record))
            ->saveUploadedFileUsing(fn($file, $record, $livewire) => $this->handleUpload($file, $record, $livewire, $filter))
            ->imageCropAspectRatio($this->handleAspectRatio($filter))
            ->required($this->requiredUpload);

        return $fileUpload;
    }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function handleUpload($file, $record, $livewire, $filter): string {

        if ($this->changeFilter) {
            $filterId = data_get($livewire->data, $this->hiddenFiledName);
            $filter = UploadFilter::query()->with('sizes')->withCount('sizes')->find($filterId);
        }

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
        $filenameBase = $this->resolveFilename($record);
        $newPath = $basePath . '/' . $filenameBase . '.webp';
        $thumbnailPath = $basePath . '/' . $filenameBase . '_thumb.webp';

        // معالجة الصورة الرئيسية
        $this->processImage($manager, $realPath, $newPath, $filter);

        // إن كان هناك حجم إضافي واحد على الأقل (thumbnail)
        $firstSize = $filter?->sizes->first();
        if ($firstSize) {
            $this->processImage($manager, $realPath, $thumbnailPath, $filter, $firstSize);

            if (!$this->multipleFiles) {
                if ($record) {
                    $record->photo_thumbnail = $thumbnailPath;
                }
                return $newPath;
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
    protected function processImage(ImageManager $manager, $realPath, $savePath, $filter, $firstSize = null): void {
        $type = $filter->type ?? 1;
        $width = $filter->width ?? 300;
        $height = $filter->height ?? 300;
        $canvas = $filter->canvas_back ?? '#ffffff';

        if ($firstSize) {
            $type = $firstSize->type ?? 1;
            $width = $firstSize->width ?? 300;
            $height = $firstSize->height ?? 300;
            $canvas = $firstSize->canvas_back ?? '#ffffff';
        }

        $quality = $filter->quality ?? 99;
        $savePath = Storage::disk($this->diskDir)->path($savePath);
        $greyscale = $filter->greyscale ?? false;
        $blur = $filter->blur ?? false;
        $blur_size = $filter->blur_size ?? 0;
        $pixelate = $filter->pixelate ?? false;
        $pixelate_size = $filter->pixelate_size ?? 0;
        $flip_state = $filter->flip_state ?? false;
        $flip_v = $filter->flip_v ?? false;

        $manager = new ImageManager(new GdDriver());
        $image = $manager->read($realPath);

        if ($greyscale) {
            $image->greyscale();
        }
        if ($blur) {
            $image->blur($blur_size);
        }
        if ($pixelate) {
            $image->pixelate($pixelate_size);
        }

        if ($flip_state) {
            $image->flop();
        }
        if ($flip_v) {
            $image->flip();
        }

        switch ($type) {
            case 1:
//                $image->encode(new WebpEncoder($quality));
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

