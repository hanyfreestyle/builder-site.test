<?php

namespace App\Traits\Admin\Model;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

trait WithModelEvents {

    public static function bootWithModelEvents(): void {
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

        static::saving(function ($record) {
            if (is_null($record->photo)) {
                $record->photo_thumbnail = null;
            }
        });

        static::forceDeleted(function ($record) {
            $galleryRelation = method_exists($record, 'getGalleryRelation')
                ? $record::getGalleryRelation()
                : null;

            // حذف الصور المرتبطة إن وجدت العلاقة
            if ($galleryRelation && method_exists($record, $galleryRelation)) {
                foreach ($record->{$galleryRelation} as $photo) {
                    if ($photo->photo) {
                        Storage::disk('root_folder')->delete($photo->photo);
                    }
                    if ($photo->photo_thumbnail) {
                        Storage::disk('root_folder')->delete($photo->photo_thumbnail);
                    }
                    $photo->forceDelete();
                }
            }

            if ($record->photo) {
                Storage::disk('root_folder')->delete($record->photo);
            }
            if ($record->photo_thumbnail) {
                Storage::disk('root_folder')->delete($record->photo_thumbnail);
            }
            if ($record->icon) {
                Storage::disk('root_folder')->delete($record->icon);
            }
        });
    }
}
