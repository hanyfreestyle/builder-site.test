<?php

namespace App\Traits\Web;

use App\Models\WebSetting\DefPhoto;
use App\Models\WebSetting\MetaTag;
use App\Models\WebSetting\WebSettings;
use Illuminate\Support\Facades\Cache;

trait LoadWebSettings {

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    static function getWebSettingsCash($cash = true) {
        if ($cash) {
            $WebConfig = Cache::remember("WebSettings_CashList_" . app()->getLocale(), cashDay(1), function () {
                return WebSettings::where('id', 1)->with('translation')->first();
            });
        } else {
            $WebConfig = WebSettings::where('id', 1)->with('translation')->first();
        }
        return $WebConfig;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    static function getMetaTagCash($cash = true) {
        if ($cash) {
            $metaTags = Cache::remember("MetaTag_CashList_" . app()->getLocale(), cashDay(1), function () {
                return MetaTag::query()->with('translation')->get()->keyBy('cat_id');
            });
        } else {
            $metaTags = MetaTag::query()->with('translation')->get()->keyBy('cat_id');
        }
        return $metaTags;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    static function getDefPhotoCash($cash = true) {
        if ($cash) {
            $defPhotos = Cache::remember("DefPhoto_CashList_" . app()->getLocale(), cashDay(1), function () {
                return DefPhoto::query()->get()->keyBy('cat_id');
            });
        } else {
            $defPhotos = DefPhoto::query()->get()->keyBy('cat_id');
        }
        return $defPhotos;
    }


}
