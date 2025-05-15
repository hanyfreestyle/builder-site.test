<?php

namespace App\Traits\Admin\Model;

use Illuminate\Support\Facades\Storage;

trait WithModelUploadPhoto {

    public static function bootWithModelUploadPhoto(): void {

        static::saving(function ($record) {
            if (is_null($record->photo)) {
                $record->photo_thumbnail = null;
            }
        });

        if (in_array(\Illuminate\Database\Eloquent\SoftDeletes::class, class_uses_recursive(static::class))) {
            static::forceDeleted(function ($record) {
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
        } else {
            static::deleted(function ($record) {
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
}
