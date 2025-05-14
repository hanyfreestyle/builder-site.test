<?php

namespace App\Console\Commands;

use App\Traits\Admin\Command\CommandTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LangFileFromModel extends Command {
    use CommandTrait;

    protected $signature = 'app:make:model-lang {model}';
    protected $description = 'Generate language files from a model using a stub and config(admin_lang)';

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||

    public function handle() {
        $relativeModel = $this->argument('model'); // مثال: UserGuide\UserGuidePage
        $fullModelClass = 'App\\Models\\' . $relativeModel;

        if (!class_exists($fullModelClass)) {
            $this->error("Model class does not exist: {$fullModelClass}");
            return;
        }

        $modelInstance = new $fullModelClass;

        $modelBaseName = class_basename($modelInstance); // UserGuidePage
        $modelTitle = Str::title(Str::snake($modelBaseName, ' '));           // User Guide Page
        $modelTitlePlural = Str::plural($modelTitle);                        // User Guide Pages
        $modelTitleManage = 'Manage ' . $modelTitle;                         // Manage User Guide Page


        // جلب الحقول من fillable أو قاعدة البيانات
        $fields = method_exists($modelInstance, 'getFillable') && !empty($modelInstance->getFillable())
            ? $modelInstance->getFillable()
            : $this->getTableColumns($modelInstance);

        // الحقول التي سيتم تجاهلها
        $ignoreFields = $this->getIgnoreFields() ;

        // تجهيز الـ columns
        $columns = '';
        foreach ($fields as $field) {
            if (in_array($field, $ignoreFields)) continue;
            $columns .= "        '{$field}' => '" . Str::title(str_replace('_', ' ', $field)) . "',\n";
        }

        // تجهيز اسم الموديل بصيغ مختلفة
        $modelBaseName = class_basename($modelInstance); // UserGuidePage
        $modelKey = Str::snake($modelBaseName);          // user_guide_page

        // قراءة ملف الـ stub
        $stubPath = base_path('stubs/custom/model-lang.stub');
        if (!File::exists($stubPath)) {
            $this->error("Stub file not found: {$stubPath}");
            return;
        }

        $stubContent = File::get($stubPath);

        // استبدال الـ placeholders
        $finalContent = str_replace(
            ['{modelTitle}', '{modelTitlePlural}', '{modelTitleManage}', '// columns_here'],
            [$modelTitle, $modelTitlePlural, $modelTitleManage, trim($columns)],
            $stubContent
        );

        // إعداد المسارات
        $segments = explode('\\', $relativeModel);
        $fileName = Str::kebab(array_pop($segments)) . '.php';
        $folderPath = implode('/', collect($segments)->map(fn($seg) => Str::kebab($seg))->toArray());

        // جلب اللغات
        $languages = config('admin_lang', ['en', 'ar']);

        foreach ($languages as $lang) {
            $langDirectory = lang_path("{$lang}/{$folderPath}");
            $langFile = "{$langDirectory}/{$fileName}";

            if (!File::exists($langDirectory)) {
                File::makeDirectory($langDirectory, 0755, true);
            }

            if (!File::exists($langFile)) {
                File::put($langFile, $finalContent);
                $this->info("Created: {$langFile}");
            } else {
                $this->warn("Already exists: {$langFile}");
            }
        }
    }
}

