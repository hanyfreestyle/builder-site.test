<?php

namespace App\Models\WebSetting;


use Illuminate\Database\Eloquent\Model;

class WebPrivacyTranslation extends Model {


  protected $table = "config_web_privacy_lang";
  protected $fillable = ['h1', 'h2', 'des', 'lists'];
    public $timestamps = false;
}
