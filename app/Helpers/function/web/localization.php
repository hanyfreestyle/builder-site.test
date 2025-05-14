<?php

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('thisCurrentLocale')) {
    function thisCurrentLocale(): string {
        return LaravelLocalization::getCurrentLocale();
    }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('htmlDir')) {
    function htmlDir(): string {
        $sendStyle = ' dir="' . LaravelLocalization::getCurrentLocaleDirection() . '" ';
        return $sendStyle;
    }
}
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('webChangeLocale')) {
    function webChangeLocale(): string {
        $Current = LaravelLocalization::getCurrentLocale();
        if ($Current == 'ar') {
            $change = 'en';
        } else {
            $change = 'ar';
        }
        return $change;
    }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('webChangeLocaleText')) {
    function webChangeLocaleText(): string {
        $Current = LaravelLocalization::getCurrentLocale();
        if ($Current == 'ar') {
            $change = 'English';
        } else {
            $change = 'عربى';
        }
        return $change;
    }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('setLocalizationRoute')) {
    function setLocalizationRoute($route): string {
      return  LaravelLocalization::localizeUrl($route);
    }
}



