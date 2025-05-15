<?php

namespace App\Models\WebSetting;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class WebPrivacy extends Model implements TranslatableContract {
    use Translatable;
    use SoftDeletes;
    protected $table = "config_web_privacy";
    protected $primaryKey = 'id';
    public $translationModel = WebPrivacyTranslation::class;
    protected $translationForeignKey = 'privacy_id';
    public array $translatedAttributes = ['h1', 'h2', 'des', 'lists'];
    protected $fillable = ['id', 'name', 'position'];


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#|||||||||||||||||||||||||||||||||||||| #
    public function scopeDefquery(Builder $query): Builder {
        return $query->with('translations');
    }

}
