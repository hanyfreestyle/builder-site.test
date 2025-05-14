<?php

namespace App\FilamentCustom\UploadFile;

use App\Models\WebSetting\UploadFilter;
use Closure;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Storage;

trait UploadFileFunctionTrait {

    protected int $filterId = 0;
    protected string $diskDir = 'root_folder';
    protected string $uploadDirectory = 'uploads';
    protected string $fileName = 'photo';
    protected string $hiddenFiledName = 'filter_id';
    protected string $selectFiledName = 'upload_filter';
    protected bool $changeFilter = false;
    protected bool $requiredUpload = false;
    protected bool $multipleFiles = false;
    protected bool $previewAble = true;
    protected bool $canChangeFilter = false;
    protected string|null $renameTo = null;
    protected string|null $renameFromDb = null;


    public function __construct() {
        $this->hiddenFiledName = $this->fileName . "_" . $this->hiddenFiledName;
        $this->selectFiledName = $this->fileName . "_" . $this->selectFiledName;
        $this->previewAble = true;

    }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function setFilterId(int $filterId): static {
        $this->filterId = $filterId;
        return $this;
    }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function setRenameTo(string|null $renameTo): static {
        $this->renameTo = $renameTo;
        return $this;
    }

    public function setRenameFromDb(string|null $renameFromDb): static {
        $this->renameFromDb = $renameFromDb;
        return $this;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function setCanChangeFilter(bool $canChangeFilter): static {
        $this->canChangeFilter = $canChangeFilter;
        return $this;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function setRequiredUpload(int $requiredUpload): static {
        $this->requiredUpload = $requiredUpload;
        return $this;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function setFileName(string $fileName): static {
        $this->fileName = $fileName;
        return $this;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function setChangeFilter(bool $changeFilter): static {
        $this->changeFilter = $changeFilter;
        return $this;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function setMultipleFiles(bool $multipleFiles): static {
        $this->multipleFiles = $multipleFiles;
        if ($this->multipleFiles) {
            $this->previewAble = false;
        }
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
    public function setUploadDirectory(string $directory): static {
        $this->uploadDirectory = $directory;
        return $this;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    /*** دالة مساعدة لحذف الملفات المرفوعة (الصورة + الصورة المصغرة)*/
    protected function handleFileDeletion($file, $record): void {
        Storage::disk($this->diskDir)->delete($file);

        if ($this->fileName == 'photo' and $record && $record->photo_thumbnail) {
            Storage::disk($this->diskDir)->delete($record->photo_thumbnail);
            $record->photo_thumbnail = null;
        }
    }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function resolveFilename($record): string {
        $filenameBase = uniqid('img_') ;
        if($this->renameTo){
            $filenameBase =  uniqid($this->renameTo."-");
            return Url_Slug($filenameBase);
        }
        if($this->renameFromDb ){
            if($record->{$this->renameFromDb}){
                $filenameBase =  uniqid($record->{$this->renameFromDb}."-");
                return Url_Slug($filenameBase);
            }
        }

        return Url_Slug($filenameBase);
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
    protected function handleAspectRatio($filter): string|null {
        if ($filter && $filter->crop_aspect_ratio) {
            return $filter->crop_aspect_ratio;
        } else {
            return null;
        }
    }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function sanitizeFileName(string $name): string {
        $maxLength = 100;
        if (mb_strlen($name) > $maxLength) {
            $name = mb_substr($name, 0, $maxLength);
        }
        return Url_Slug($name);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function SelectFiled($filterId): Select {
        return Select::make($this->selectFiledName)
            ->label('فلتر الرفع')
            ->options(fn() => UploadFilter::getUploadFilterCashList()->pluck('name', 'id')->toArray())
            ->afterStateHydrated(function (Set $set, $state) use ($filterId) {
                if (is_null($state)) {
                    $set($this->selectFiledName, $filterId);
                }
            })
            ->afterStateUpdated(function (Set $set, $state) {
                $set($this->hiddenFiledName, $state); // حفظ الفلتر في حقل منفصل
            })
            ->default(1)
            ->live()
            ->required()
            ->searchable()
            ->preload()
            ->dehydrated(false);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function HiddenInputFiled($filterId) {
        return Hidden::make($this->hiddenFiledName)
            ->afterStateHydrated(function (Set $set, $state) use ($filterId) {
                if (is_null($state)) {
                    $set($this->hiddenFiledName, $filterId);
                }
            });
    }

}
