<?php

namespace App\Traits\Admin\Model;

use Illuminate\Support\Facades\Cache;


trait WithModelEventsCache {

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function bootWithModelEventsCache(): void {
        static::saved(function ($model) {
            foreach (config('app.admin_lang') as $key => $value) {
                Cache::forget(self::$cashKey.$key);
            }
        });

        static::deleted(function ($model) {
            foreach (config('app.admin_lang') as $key => $value) {
                Cache::forget(self::$cashKey.$key);
            }
        });

    }
}
