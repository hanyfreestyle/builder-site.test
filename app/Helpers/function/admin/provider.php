<?php
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Illuminate\Support\Facades\File;

if (!function_exists('loadCssFiles')) {
    function loadCssFiles($folderName = null) {
        $cssPath = public_path($folderName);
        $cssFiles = collect(File::files($cssPath))
            ->filter(fn($file) => $file->getExtension() === 'css')
            ->map(function ($file) use ($folderName) {
                $relativePath = $folderName . '/' . $file->getFilename();
                return Css::make(
                    $file->getFilenameWithoutExtension(),
                    asset($relativePath)
                );
            })
            ->toArray();
        return $cssFiles;
    }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('loadJsFiles')) {
    function loadJsFiles($folderName = null) {
        $cssPath = public_path($folderName);
        $cssFiles = collect(File::files($cssPath))
            ->filter(fn($file) => $file->getExtension() === 'js')
            ->map(function ($file) use ($folderName) {
                $relativePath = $folderName . '/' . $file->getFilename();
                return Js::make(
                    $file->getFilenameWithoutExtension(),
                    asset($relativePath)
                );
            })
            ->toArray();
        return $cssFiles;
    }
}
