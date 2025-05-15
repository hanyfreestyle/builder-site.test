<?php

namespace App\Models\SiteBuilder;

use App\Traits\Admin\Model\WithModelUploadPhoto;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model {
    use WithModelUploadPhoto;

    protected $table = "builder_template";
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['slug', 'name', 'des', 'photo', 'photo_thumbnail', 'is_active'];

    protected $casts = [
        'name' => 'array',
        'des' => 'array',
        'is_active' => 'boolean',
    ];

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected static function booted() {
        static::bootWithModelUploadPhoto();
    }


}
