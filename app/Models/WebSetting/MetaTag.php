<?php

namespace App\Models\WebSetting;


use App\Traits\Admin\Model\WithModelEventsCache;
use App\Traits\Admin\Model\WithModelEventsUploadPhotos;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;


class MetaTag extends Model implements TranslatableContract  {
    use Translatable;
    use WithModelEventsCache;
    use WithModelEventsUploadPhotos;
    use SoftDeletes;

    protected $table = "config_meta_tag";
    protected static string $cashKey = 'cash_config_meta_tag';
    protected $primaryKey = 'id';
    public $translationModel = MetaTagTranslation::class;
    protected $translationForeignKey = 'meta_tag_id';

    public array $translatedAttributes = ['name', 'g_title', 'g_des', 'des',];
    protected $fillable = ['cat_id', 'photo', 'photo_thumbnail', 'deleted_at'];

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected static function booted() {
        static::bootWithModelEventsCache();
        static::bootWithModelEventsUploadPhotos();
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function translations(): HasMany {
        return $this->hasMany(MetaTagTranslation::class, 'meta_tag_id');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getMetaTagCashList($IsCash = true) {
        $cashKey = self::$cashKey;
        if ($IsCash) {
            return Cache::remember($cashKey . app()->getLocale(), 3600, function () {
                return MetaTag::query()->get();
            });
        } else {
            return MetaTag::query()->get();
        }
    }

}
