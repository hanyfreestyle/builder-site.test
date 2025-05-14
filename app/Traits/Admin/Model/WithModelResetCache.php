<?php

namespace App\Traits\Admin\Model;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

trait WithModelResetCache {
    public static function bootWithResetCache(): void {
        static::saved(function ($model) {
            if (method_exists($model, 'getCacheKey')) {
                foreach (config('app.admin_lang') as $key => $value) {
                    Cache::forget($model::getCacheKey() . $key);
                }
            }
        });

        static::deleted(function ($model) {
            if (method_exists($model, 'getCacheKey')) {
                foreach (config('app.admin_lang') as $key => $value) {
                    Cache::forget($model::getCacheKey() . $key);
                }
            }
        });

    }
}
