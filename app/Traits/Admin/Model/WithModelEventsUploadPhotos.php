<?php

namespace App\Traits\Admin\Model;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;


trait WithModelEventsUploadPhotos {

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function bootWithModelEventsUploadPhotos(): void {
        static::saving(function ($record) {
            if (is_null($record->photo)) {
                $record->photo_thumbnail = null;
            }
        });

        // نتحقق إذا الموديل بيستخدم SoftDeletes
        if (in_array(\Illuminate\Database\Eloquent\SoftDeletes::class, class_uses_recursive(static::class))) {
            // إذا كان فيه SoftDeletes → نستخدم forceDeleted
            static::forceDeleted(function ($record) {
                self::deletePhotos($record);
            });
        } else {
            // إذا ما فيش SoftDeletes → نستخدم deleted
            static::deleted(function ($record) {
                self::deletePhotos($record);
            });
        }
    }

    protected static function deletePhotos($record): void {
        if ($record->photo) {
            Storage::disk('root_folder')->delete($record->photo);
        }
        if ($record->photo_thumbnail) {
            Storage::disk('root_folder')->delete($record->photo_thumbnail);
        }
        if ($record->icon) {
            Storage::disk('root_folder')->delete($record->icon);
        }
    }
}
