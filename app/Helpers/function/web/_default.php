<?php

use App\Http\Controllers\DefaultWebController;
use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('defPublicUrl')) {
    function defPublicUrl($type, $fileName, $secure = null): string {
        $FolderName = config('appConfig.client_name');
        $clientFolderName = $FolderName . '/';
        if ($type == 'FavIcon') {
            return app('url')->asset('fav/' . $clientFolderName . $fileName, $secure);
        } elseif ($type == 'Fonts') {
            return app('url')->asset('assets/fonts/' . $fileName, $secure);
        } elseif ($type == 'web-def') {
            return app('url')->asset('assets/web-def/' . $fileName, $secure);
        } elseif ($type == 'web-quiz') {
            return app('url')->asset('assets/web-quiz/' . $fileName, $secure);
        } elseif ($type == 'portal-card') {
            return app('url')->asset('assets/portal-card/' . $fileName, $secure);
        } else {
            return app('url')->asset($fileName, $secure);
        }
    }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getPhotoPath')) {
    function getPhotoPath($file, $defPhoto = "logo", $defPhotoRow = "photo_thumbnail"): string {
        if ($file) {
            $sendImg = Storage::disk('root_folder')->url($file);
        } else {
//            $defPhotoList = DefaultWebController::getDefPhotoById($defPhoto);
            $sendImg = defImagesDir($defPhotoList->{$defPhotoRow} ?? '');
        }
        return $sendImg;
    }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('defImagesDir')) {
    function defImagesDir($defPhoto,$defPhotoRow = "photo_thumbnail"): string {
        $defPhotoList = DefaultWebController::getDefPhotoById($defPhoto);
        $path = $defPhotoList->{$defPhotoRow} ?? '' ;
        return Storage::disk('root_folder')->url($path);
    }
}


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getCopyRight')) {
    function getCopyRight($StartDate, $CompanyName): string {
        if ($StartDate > date("Y")) {
            $StartDate = date("Y");
        }
        $Lang = LaravelLocalization::getCurrentLocale();
        switch ($Lang) {
            case 'ar':
                $copyname = "جميع الحقوق محفوظة";
                if ($StartDate == date("Y")) {
                    $CopyRight = $copyname . " &copy; " . date("Y") . ' <span class="CompanyName">' . $CompanyName . '</span>';
                } else {
                    $CopyRight = $copyname . ' &copy; <span class="CompanyName">' . $CompanyName;
                }
                break;
            default:
                $copyname = "All Rights Reserved";
                if ($StartDate == date("Y")) {
                    $CopyRight = $copyname . " &copy; " . date("Y") . ' <span class="CompanyName">' . $CompanyName . '</span>';
                } else {
                    $CopyRight = $copyname . " &copy; " . '<span class="CompanyName">' . $CompanyName . '</span>';
                }
        }
        return $CopyRight;
    }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('IsArr')) {
    function IsArr($Arr, $Name, $DefVall = "") {
        if (isset($Arr[$Name])) {
            $SendVal = $Arr[$Name];
        } else {
            $SendVal = $DefVall;
        }
        return $SendVal;
    }
}

