<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ResourceWithPages extends Command {
    protected $signature = 'app:make-resource {modelPath} {translationTable}';


    protected $description = 'Generate a custom Filament Resource for the given model path';

    public function handle() {

        $translationTable = $this->argument('translationTable') ?? '';
        $translationTableLine = "protected static ?string \$translationTable = '{$translationTable}';";


        $modelPath = str_replace('/', '\\', $this->argument('modelPath'));
        $modelName = Str::studly(class_basename($modelPath)); // BlogPost
        $modelNamespacePath = Str::beforeLast($modelPath, '\\'); // Admin\ModelName
        $modelFullClass = 'App\\Models\\' . $modelPath;
        $resourceName = $modelName . 'Resource';


        ///getLang File Var
        $modelPathForLang = collect(explode('\\', $modelPath))
            ->map(fn($segment) => Str::kebab($segment))
            ->implode('/');

        // ✅ هنا التعديل الرئيسي
        $resourceNamespace = 'App\\Filament\\Admin\\Resources\\' . $modelNamespacePath;
        $resourceRelativePath = str_replace('\\', '/', $modelNamespacePath);
        $resourcePath = base_path("app/Filament/Admin/Resources/{$resourceRelativePath}/{$resourceName}.php");

        $modelNameSnake = Str::snake($modelName);
        $modelNamePlural = Str::pluralStudly($modelName);

        $stubPath = base_path('stubs/custom/resource.stub');
        if (!File::exists($stubPath)) {
            $this->error("❌ Stub not found at {$stubPath}");
            return;
        }

        $stub = File::get($stubPath);

        $stub = str_replace(
            [
                '{{ class }}',
                '{{ namespace }}',
                '{{ modelNamespace }}',
                '{{ resourcePath }}',
                '{{ modelName }}',
                '{{ modelNamePlural }}',
                '{{ modelNameSnake }}',
                '{{ modelPathForLang }}',
                '{{ translationTableLine }}',
            ],
            [
                $resourceName,
                $resourceNamespace,
                $modelPath,
                $modelNamespacePath,
                $modelName,
                $modelNamePlural,
                $modelNameSnake,
                $modelPathForLang,
                $translationTableLine,
            ],
            $stub
        );

        File::ensureDirectoryExists(dirname($resourcePath));
        File::put($resourcePath, $stub);

        $this->info("✅ Resource created : {$resourceName}");

        $this->createPages(
            $modelNamePlural,
            $modelName,
            $resourceNamespace,
            $resourceName,
            $resourceRelativePath,
            $modelPathForLang,
        );

        $this->createTraits(
            $resourceName,
            $modelName,
            $resourceRelativePath
        );

    }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function createPages($modelNamePlural, $modelName, $resourceNamespace, $resourceName, $resourceRelativePath, $modelPathForLang) {
        // ========== Create Pages ==========
        $pages = [
            'List' => 'resource-page-list.stub',
            'Create' => 'resource-page-create.stub',
            'Edit' => 'resource-page-edit.stub',
            'View' => 'resource-page-view.stub',
        ];

        foreach ($pages as $type => $stubFile) {
            $pageClass = "{$type}{$modelNamePlural}";
            if (in_array($type, ['Create', 'Edit', 'View'])) {
                $pageClass = "{$type}{$modelName}";
            }

            $stubPath = base_path("stubs/custom/{$stubFile}");
            if (!File::exists($stubPath)) {
                $this->warn("Stub for {$type} not found.");
                continue;
            }

            $pageStub = File::get($stubPath);
            $pageStub = str_replace(
                ['{{ namespace }}', '{{ modelName }}', '{{ modelNamePlural }}', '{{ resourceClass }}', '{{ modelPathForLang }}'],
                [$resourceNamespace, $modelName, $modelNamePlural, $resourceName, $modelPathForLang],
                $pageStub
            );

            $pagePath = base_path("app/Filament/Admin/Resources/{$resourceRelativePath}/{$resourceName}/Pages/{$pageClass}.php");
            File::ensureDirectoryExists(dirname($pagePath));
            File::put($pagePath, $pageStub);

            $this->info("✅ Page created: {$pageClass}");
        }
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function createTraits($resourceName, $modelName, $resourceRelativePath) {
        // ========== Create Traits ==========
        $traits = [
//            "Resource{$modelName}" => 'resource-trait.stub',
            "Table{$modelName}" => 'resource-trait-table.stub',
        ];

        foreach ($traits as $traitClass => $stubFile) {
            $traitNamespace = "App\\Filament\\Admin\\Resources\\{$resourceRelativePath}\\{$resourceName}";
            $stubPath = base_path("stubs/custom/{$stubFile}");

            if (!File::exists($stubPath)) {
                $this->warn("❌ Trait stub not found: {$stubPath}");
                continue;
            }

            $traitStub = File::get($stubPath);
            $traitStub = str_replace(
                ['{{ namespace }}', '{{ traitClass }}'],
                [$traitNamespace, $traitClass],
                $traitStub
            );

            $traitPath = base_path("app/Filament/Admin/Resources/{$resourceRelativePath}/{$resourceName}/{$traitClass}.php");
            File::put($traitPath, $traitStub);
            $this->info("✅ Trait created: {$traitClass}");
        }
    }


}
