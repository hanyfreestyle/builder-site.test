<?php

namespace App\Models\WebSetting;

use Illuminate\Database\Eloquent\Model;

class MetaTagTranslation extends Model {
    protected $table = "config_meta_tag_lang";
    protected $fillable = ['meta_tag_id', 'locale', 'name', 'des', 'g_des', 'g_title'];
    public $timestamps = false;
}
