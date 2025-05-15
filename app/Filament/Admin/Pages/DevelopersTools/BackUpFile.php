<?php

namespace App\Filament\Admin\Pages\DevelopersTools;

use App\Filament\Admin\Resources\DevelopersTools\FilesListGroupResource;
use App\Models\DevelopersTools\FilesList;
use App\Models\DevelopersTools\FilesListGroup;
use App\Traits\Admin\Helper\SmartResourceTrait;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\File;

class BackUpFile extends Page {
    use SmartResourceTrait;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.admin.pages.developers-tools.back-up-file';
    public $isModalOpen = false; // المتغير المتحكم في المودال
    protected static ?string $navigationGroup = 'admin-core';

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function canAccess(): bool {
        return isLocalSuperAdmin();
    }

    protected function getViewData(): array {
        return [
            'gridClass' => 'grid flex-1 auto-cols-fr gap-y-2', // تقليل gap-y
        ];
    }

    public ?string $backUpFolderPath = 'D:/__filament';
    public ?string $corePath = '_core';
    public ?string $versionFolder = 'app/Version';

    public function fileListGroupResource() {
        $fileListGroupResource = FilesListGroupResource::getResource();
        return $fileListGroupResource;
    }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function getFileListGroup() {
        return FilesListGroup::query()
            ->where('is_active', true)
            ->with('files_list')
            ->withCount('files_list')
            ->orderBy('position')
            ->get();
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function getFileListFromDB() {
        $fileList = [];
        $records = FilesList::query()->where('is_active', true)->get();

        foreach ($records as $record) {
            $fileList[$record->cat_id] = [
                'cat_id' => $record->cat_id,
                'title' => $record->title,
                'active' => $record->is_active,
                'copy' => $record->copy,
                'delete' => $record->delete,
                'is_exist' => $record->is_exist,
                'files' => is_string($record->files) ? array_column(json_decode($record->files, true) ?? [], 'value') : array_column($record->files, 'value'),
                'folder' => is_string($record->folders) ? array_column(json_decode($record->folders, true) ?? [], 'value') : array_column($record->folders, 'value'),
            ];
        }
        return $fileList;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function getDestination($key, $type = 'core') {
        $folderPath = $this->backUpFolderPath;
        $corePath = $this->corePath;
        $month = now()->format('Y-m-d');
        $timestamp = time();
        $version = config('app.version');

        if ($type == 'core') {
            $destinationBasePath = "{$folderPath}/{$corePath}/{$key}/";
            self::UpdateLogFile($key);
        } else {
            $destinationBasePath = "{$folderPath}/version-{$version}/{$key}/{$timestamp}-{$month}/";
        }
        return $destinationBasePath;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function copyFiles($key) {
        $fileList = $this->getFileListFromDB();
        $item = $fileList[$key] ?? null;
        if ($item) {
            $this->copyFunction($item, $this->getDestination($key, 'core'));
//            $this->copyFunction($item, $this->getDestination($key, 'version'));
            Notification::make()
                ->title('تم نسخ الملفات بنجاح')
                ->success()
                ->send();
        }
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function copyFunction($item, $destinationBasePath) {
        // ✅ نسخ الملفات مع الحفاظ على ترتيب المجلدات
        self::CopyLogFile($item, $destinationBasePath);
        foreach ($item['files'] as $file) {
            $sourcePath = base_path($file);
            if (File::exists($sourcePath)) {
                $relativePath = $file;
                $destinationFullPath = $destinationBasePath . $relativePath;
                // إنشاء المجلدات المطلوبة
                $destinationDirectory = dirname($destinationFullPath);
                if (!File::exists($destinationDirectory)) {
                    File::makeDirectory($destinationDirectory, 0777, true);
                }
                // نسخ الملف
                File::copy($sourcePath, $destinationFullPath);
            }
        }
        // ✅ نسخ المجلدات بكامل محتوياتها
        foreach ($item['folder'] as $folder) {
            $sourceFolderPath = base_path($folder);
            $destinationFolderPath = $destinationBasePath . $folder;
            if (File::exists($sourceFolderPath)) {
                $this->copyFolderRecursively($sourceFolderPath, $destinationFolderPath);
            }
        }
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function CopyLogFile($item, $destinationBasePath) {
        $destination = base_path($this->versionFolder);
        $fileName = $item['cat_id'] . '.log';
        $sourcePath = $destination . "/" . $item['cat_id'] . '.log';
        if (File::exists($sourcePath)) {
            $destinationDirectory = $destinationBasePath . $this->versionFolder;
            if (!File::exists($destinationDirectory)) {
                File::makeDirectory($destinationDirectory, 0777, true);
            }
            File::copy($sourcePath, $destinationDirectory . "/" . $fileName);
        }
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    private function copyFolderRecursively($source, $destination) {
        // التأكد من وجود مجلد الوجهة
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0777, true);
        }

        // الحصول على جميع الملفات والمجلدات الفرعية
        $items = File::allFiles($source);
        foreach ($items as $item) {
            $relativePath = $item->getRelativePathname();
            $destinationPath = $destination . '/' . $relativePath;

            // إنشاء المجلد الفرعي في الوجهة إذا لم يكن موجودًا
            $destinationDir = dirname($destinationPath);
            if (!File::exists($destinationDir)) {
                File::makeDirectory($destinationDir, 0777, true);
            }

            // نسخ الملف إلى الوجهة
            File::copy($item->getPathname(), $destinationPath);
        }
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function deleteFiles($key) {
        $fileList = $this->getFileListFromDB();
        $item = $fileList[$key] ?? null;
        if ($item) {

//            $migrationFile = str_replace(['database/migrations/', '.php'], ['', ''], $item['is_exist']);
//            DB::table('migrations')->where('migration', $migrationFile)->delete();

            $destination = base_path($this->versionFolder);
            $sourcePath = $destination . "/" . $item['cat_id'] . '.log';
            if (File::exists($sourcePath)) {
                File::delete($sourcePath);
            }


            foreach ($item['files'] as $file) {
                $filePath = base_path($file);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }
            foreach ($item['folder'] as $folder) {
                $folderPath = base_path($folder);
                if (File::exists($folderPath)) {
                    File::deleteDirectory($folderPath);
                }
            }

            // ✅ إشعار عند نجاح الحذف
            Notification::make()
                ->title('تم حذف الملفات والمجلدات بنجاح!')
                ->danger()
                ->send();
        }
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function ImportFolder($key) {
        $folderPath = $this->backUpFolderPath;
        $corePath = $this->corePath;
        $destinationFolder = base_path();
        $BackFolder = "{$folderPath}/{$corePath}/{$key}/";
        if (File::isDirectory($BackFolder)) {
            self::copyFolderRecursively($BackFolder, $destinationFolder);
        }
        return redirect()->back();
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function UpdateLogFile($key) {
        $destination = base_path($this->versionFolder);
        $fileName = $key . '.log';
        $timeUpdate = time();
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0777, true);
        }
        $logFile = $destination . "/" . $fileName;
        if (File::exists($logFile)) {
            File::put($logFile, ""); // إعادة تعيين الملف
        }
        File::append($logFile, $timeUpdate);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function getLastLog($key) {
        $logFileContent = null;
        $backUpLogFileContent = null;
        $soursLogPrint = 'No log File Found';
        $destination = base_path($this->versionFolder);
        $fileName = $key . '.log';
        $logFile = $destination . "/" . $fileName;

        if (File::exists($logFile)) {
            $logFileContent = File::get($logFile);
            $soursLogPrint = date("Y-m-d   H:i:s", $logFileContent);
            $soursLogPrint = $logFileContent;
        }
        $backUpFolder = $this->backUpFolderPath . '/' . $this->corePath . '/' . $key;
        $backUpLogFilePath = $backUpFolder . '/' . $this->versionFolder . '/' . $key . '.log';
        if (File::exists($backUpLogFilePath)) {
            $backUpLogFileContent = File::get($backUpLogFilePath);
        }

        $data = [
            'soursLogTime' => $logFileContent,
            'soursLogPrint' => $soursLogPrint,
            'backUpLogTime' => $backUpLogFileContent
        ];

        return $data;
    }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function formatSmartDateWithTime(int $timestamp): string {

        if ($timestamp == 0) {
            return <<<HTML
        <span class="badge badge-red">Hnay </span>
HTML;
        }
        $date = \Carbon\Carbon::createFromTimestamp($timestamp);
        $now = \Carbon\Carbon::now();

        $isToday = $date->isToday();
        $isThisYear = $date->isCurrentYear();
        $isPastYear = $date->year < $now->year;

        // تحديد لون البادج
        $color = 'red'; // اللون الافتراضي

        // تحديد شكل التاريخ
        if ($isToday) {
            // تحقق إذا مر أكثر من ساعة على الوقت
            if ($date->diffInHours($now) >= 1) {
                $color = 'blue'; // مر أكثر من ساعة
            } else {
                $color = 'green'; // أقل من ساعة
            }
        } elseif ($isThisYear) {
            $color = 'orange';
        }

        if ($isToday) {
            $dateLabel = 'اليوم';
        } elseif ($isThisYear) {
            $dateLabel = $date->translatedFormat('j F');
        } else {
            $dateLabel = $date->translatedFormat('F Y');
        }

        // الوقت
        $time = $date->format('H:i');

        // الوقت النسبي
        $diff = $date->diffForHumans([
            'parts' => 1,
            'join' => true,
            'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
        ]);

        return <<<HTML
        <span class="badge badge-$color">{$dateLabel}</span>
        <small class="text-muted ms-1">{$time}</small>
        <small class="text-muted d-block">{$diff}</small>
    HTML;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function printLogDate($timestamp) {
        $html = '';
        if ($timestamp) {
            $html .= date("Y-m-d   H:i:s", $timestamp);
        }
        return $html;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getNavigationGroup(): ?string {
        return __('developers-tools/fileList.navigation_group');
    }

    public static function getNavigationLabel(): string {
        return __('developers-tools/fileList.backup.NavigationLabel');
    }

    public function getTitle(): string|Htmlable {
        return __('developers-tools/fileList.backup.Title');
    }


}
