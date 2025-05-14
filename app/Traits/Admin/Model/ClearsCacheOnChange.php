<?php

namespace App\Traits\Admin\Model;

use Illuminate\Support\Facades\Cache;

trait ClearsCacheOnChange {

    public static function bootClearsCacheOnChange() {
        static::saved(function ($model) {
            $model->clearCustomCache();
        });

        static::deleted(function ($model) {
            $model->clearCustomCache();
        });
    }

    public function clearCustomCache(): void {
        if (!method_exists($this, 'cacheKeys')) {
            return;
        }

        foreach (config('app.admin_lang') as $langKey => $langName) {
            foreach ($this->cacheKeys() as $key) {
                Cache::forget($key . $langKey);
            }
        }
    }
}
