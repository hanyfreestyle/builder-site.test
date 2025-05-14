<?php
use Carbon\Carbon;
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('printFormattedDate')) {
    function printFormattedDate($getDate, $setLang = true, $format = "jS M Y"): string {
        if ($setLang) {
            return Carbon::parse($getDate)->locale(app()->getLocale())->translatedFormat($format);
        } else {
            return Carbon::parse($getDate)->format($format);
        }
    }
}






