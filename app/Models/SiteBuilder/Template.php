<?php
namespace App\Models\SiteBuilder;

use App\Traits\Admin\Model\WithModelEvents;
use App\Traits\Admin\Query\TranslatableScopes;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model  {
//    use WithModelEvents;
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
//        static::bootWithModelEvents();
    }


}
