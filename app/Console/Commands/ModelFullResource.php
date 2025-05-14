<?php

namespace App\Console\Commands;


use App\Traits\Admin\Command\CommandTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;

class ModelFullResource extends Command {
    use CommandTrait;


    protected $signature = 'app:model
        {path : The relative model path, e.g., Admin\\BlogPost}
        {--table= : The table name to use}
        {--key= : The foreign key for translations}
        {--trans : Generate translation model}
        {--Resource : Also generate a Filament Resource}
        {--Lang : Also generate a Lang File }';

    protected $description = 'Generate a custom model (and translation model if needed) using stubs';

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function handle() {
        $inputPath = str_replace('/', '\\', $this->argument('path'));
        $className = Str::studly(class_basename($inputPath));
        $namespacePath = Str::beforeLast($inputPath, '\\');
        $this->namespace = 'App\\Models\\' . $namespacePath;
        $this->relativePath = str_replace('\\', '/', $namespacePath);
        $this->key = $this->option('key') ?? Str::camel($className) . '_id';
        $this->table = $this->option('table') ?? Str::snake($className);

        $columns = $this->getTableColumnsList($this->table);
        $parentRelations = $this->generateParentRelations($className, $columns);
        $fillableLine = "protected \$fillable = [" . implode(', ', array_map(fn($col) => "'$col'", $columns)) . "];";

        $translationModelLine = $this->createTranslationLine($className);

        // === Create Main Model ===
        $modelStubPath = base_path('stubs/custom/model.stub');
        if (!File::exists($modelStubPath)) {
            $this->error("Model stub not found at {$modelStubPath}");
            return;
        }

        $modelStub = File::get($modelStubPath);
        $modelStub = str_replace(
            [
                '{{ class }}',
                '{{ namespace }}',
                '{{ table }}',
                '{{ key }}',
                '{{ translationModelLine }}',
                '{{ fillableLine }}',
                '{{ parentRelations }}',
            ],
            [
                $className,
                $this->namespace,
                $this->table,
                $this->key,
                $translationModelLine,
                $fillableLine,
                $parentRelations,
            ],
            $modelStub
        );

        $modelPath = app_path("Models/{$this->relativePath}/{$className}.php");
        File::ensureDirectoryExists(dirname($modelPath));
        if (!File::exists($modelPath)) {
            File::put($modelPath, $modelStub);
            $this->info("✅ Model created: {$this->relativePath}/{$className}");
        } else {
            $this->error("❌ Model {$this->relativePath}/{$className} Is Exists");
            return false;
        }

        // === Create Translation Model ===
        $this->createTranslation($className);

        // ===  Create Resource if --Resource flag is set ===
        if ($this->option('Resource')) {
            $this->call('app:make-resource', [
                'modelPath' => $inputPath,
                'translationTable' => $this->table . "_lang",
            ]);
        }

        // ===  Create Resource if --Resource flag is set ===
        if ($this->option('Lang')) {
            $this->call('app:make:model-lang', [
                'model' => $this->argument('path'),
            ]);
        }

    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function createTranslationLine($className) {
        $translationModelLine = '';
        if ($this->option('trans')) {
            $columnsLang = $this->getTableColumnsList($this->table . "_lang");
            $translationModelLine .= "public \$translationModel = {$className}Translation::class; \n";
            $translationModelLine .= "public array \$translatedAttributes = [" . implode(', ', array_map(fn($col) => "'$col'", $columnsLang)) . "];";
        }
        return $translationModelLine;
    }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function createModelFile($modelPath, $modelStub, $className) {
        if ($this->reWrite) {
            File::put($modelPath, $modelStub);
            return $this->info("✅ Model created: {$this->relativePath}/{$className}");
        } else {
            File::ensureDirectoryExists(dirname($modelPath));
            if (!File::exists($modelPath)) {
                File::put($modelPath, $modelStub);
                $this->info("✅ Model created: {$this->relativePath}/{$className}");
            } else {
                $this->error("❌ Model {$this->relativePath}/{$className} Is Exists");
                return false;
            }
        }
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function createTranslation($className) {
        if ($this->option('trans')) {

            $translationClass = $className . 'Translation';
            $translationStubPath = base_path('stubs/custom/model-translation.stub');

            if (!File::exists($translationStubPath)) {
                $this->error("Translation stub not found at {$translationStubPath}");
                return;
            }

            $columns = $this->getTableColumnsList($this->table . "_lang");
            $translationFillableLine = "protected \$fillable = [" . implode(', ', array_map(fn($col) => "'$col'", $columns)) . "];";

            $translationStub = File::get($translationStubPath);
            $translationStub = str_replace(
                ['{{ class }}', '{{ namespace }}', '{{ table }}', '{{ fillableLine }}'],
                [$className, $this->namespace, $this->table, $translationFillableLine],
                $translationStub
            );

            $translationPath = app_path("Models/{$this->relativePath}/{$translationClass}.php");
            File::put($translationPath, $translationStub);
            $this->info("✅ Translation created: {$this->relativePath}/{$translationClass}");
        }
    }
}
