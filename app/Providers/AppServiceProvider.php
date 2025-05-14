<?php

namespace App\Providers;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class AppServiceProvider extends ServiceProvider {

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function register(): void {
        $adminRoutesPath = base_path('app/Helpers/function');
        if (File::isDirectory($adminRoutesPath)) {
            foreach (File::allFiles($adminRoutesPath) as $file) {
                if ($file->getExtension() === 'php') {
                    require $file->getRealPath();
                }
            }
        }

    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function boot(): void {
        URL::forceScheme('https');
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch->locales(['ar', 'en']); // also accepts a closure
        });

        $cssFiles = loadCssFiles('assets/admin/css');
        FilamentAsset::register($cssFiles);

//        $jsFiles = loadJsFiles('assets/admin/js');
//        FilamentAsset::register($jsFiles);

    }
}
