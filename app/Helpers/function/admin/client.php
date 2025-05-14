<?php

use Illuminate\Support\Facades\File;
use Spatie\Valuestore\Valuestore;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('client_config')) {
    function client_config(string $fileName, $clientFolder = true) {
        $folderName = null;
        $default = [];
        if ($clientFolder) {
            $folderName = config('appConfig.client_name') . "/";
        }
        $basePath = 'app/config/' . $folderName;
        $file = base_path($basePath . $fileName . '.php');
        // تحقق إذا الملف موجود
        if (file_exists($file)) {
            return require $file;
        }
        return $default;
    }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getbrandLogo')) {
    function getbrandLogo(): string {
        $defLogo = asset('assets/client/_def/logo.png');
        $folderName = config('appConfig.client_name');

        if ($folderName) {
            $filePath = public_path("assets/client/{$folderName}/logo.png");
            if (File::isFile($filePath)) {
                return asset("assets/client/{$folderName}/logo.png");
            }
        }
        return $defLogo;
    }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getFavIcon')) {
    function getFavIcon(): string {
        $defFav = asset('assets/client/_def/fav.png');
        $folderName = config('appConfig.client_name');
        if ($folderName) {
            $filePath = public_path("assets/client/{$folderName}/logo.png");
            if (File::isFile($filePath)) {
                return asset("assets/client/{$folderName}/fav.png");
            }
        }
        return $defFav;
    }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getBackgroundsDirectory')) {
    function getBackgroundsDirectory(): string {
        $default = 'assets/client/_def/backgrounds';
        $folderName = config('appConfig.client_name');
        if ($folderName) {
            $relativePath = "assets/client/{$folderName}/backgrounds";
            $absolutePath = public_path($relativePath);
            if (File::isDirectory($absolutePath)) {
                return $relativePath;
            }
        }
        return $default;
    }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getConfigJsonFile')) {
    function getConfigJsonFile($fileName) {
        $clientFolder = config('appConfig.client_name');
        $pathToFile = base_path("app/config/$clientFolder/$fileName.json");
        if (!file_exists(dirname($pathToFile))) {
            mkdir(dirname($pathToFile), 0755, true);
        }
        $valuestore = Valuestore::make($pathToFile);
        return $valuestore;
    }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getModuleConfigKey')) {
    function getModuleConfigKey($key, $default) {
        $valueStore = getConfigJsonFile("module");
        $state = $valueStore->get('modules', []); // هنا بنجيب كل الحقول العادية
        return data_get($state, $key, $default);
    }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('loadArrayFromPhpFile')) {
    function loadArrayFromPhpFile($path): array {
        if (file_exists($path)) {
            return require $path;
        }
        return [];
    }
}








