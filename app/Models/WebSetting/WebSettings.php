<?php

namespace App\Models\WebSetting;

use App\Traits\Admin\Model\WithModelResetCache;
use App\Traits\Admin\Query\TranslatableScopes;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class WebSettings extends Model {

    use Translatable;
    use TranslatableScopes;
    use WithModelResetCache;

    protected $table = "config_setting";
    public $translationModel = WebSettingsTranslation::class;
    protected $translationForeignKey = 'setting_id';
    protected $primaryKey = 'id';
    public $timestamps = false;


    public array $translatedAttributes = ['name', 'g_title', 'g_des', 'closed_mass', 'meta_des', 'whatsapp_des', 'schema_address', 'schema_city'];
    protected $fillable = [
        'web_url',
        'web_status',
        'web_status_date',
        'switch_lang',
        'lang',
        'users_login',
        'users_register',
        'users_forget_password',
        'phone_num',
        'whatsapp_num',
        'phone_call',
        'whatsapp_send',
        'email',
        'def_url',
        'facebook',
        'youtube',
        'twitter',
        'instagram',
        'linkedin',
        'google_api',
        'telegram_send',
        'telegram_phone',
        'telegram_group',
        'schema_type',
        'schema_lat',
        'schema_long',
        'schema_postal_code',
        'schema_country',
    ];

    protected $casts = [
        'telegram_key' => 'encrypted',
        'telegram_phone' => 'encrypted',
        'telegram_group' => 'encrypted',
    ];

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected static function booted() {
        static::bootWithResetCache();
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getCacheKey(): string {
        return "WebSettings_CashList_";
    }

}
