<?php

namespace App\Models\WebSetting;

use Illuminate\Database\Eloquent\Model;

class WebSettingsTranslation extends Model {

    public $timestamps = false;
    protected $table = "config_setting_lang";
    protected $fillable = [
        'setting_id',
        'locale',
        'name',
        'closed_mass',
        'meta_des',
        'whatsapp_des',
        'schema_address',
        'schema_city',
    ];

}
