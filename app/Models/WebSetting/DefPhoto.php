<?php

namespace App\Models\WebSetting;

use App\Traits\Admin\Model\ClearsCacheOnChange;
use App\Traits\Admin\Model\WithModelEventsUploadPhotos;
use Illuminate\Database\Eloquent\Model;

class DefPhoto extends Model {
    use ClearsCacheOnChange;
    use WithModelEventsUploadPhotos;

    protected $table = "config_def_photos";
    protected $primaryKey = 'id';
    protected static string $cashKey = 'cash_config_def_photos';
    protected $fillable = ['cat_id', 'photo', 'photo_thumbnail', 'position', 'deleted_at'];

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected static function booted() {
        self::bootClearsCacheOnChange();
        static::bootWithModelEventsUploadPhotos();
    }

    public function cacheKeys(): array {
        return [
            'DefPhoto_CashList_',
        ];
    }

}
