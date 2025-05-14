<?php

use Illuminate\Support\Str;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('seoDesClean')) {
    function seoDesClean($getDes): string {
        $str = strip_tags($getDes);
        $str = str_replace('&nbsp;', ' ', $str);
        $str = preg_replace("/\r|\n/", " ", $str);
        $str = preg_replace('/\s+/', ' ', $str);
        $str = Str::limit($str, 155, "");
        $last_space_position = strrpos($str, ' ');
        $str = substr($str, 0, $last_space_position);
        return $str;
    }
}






