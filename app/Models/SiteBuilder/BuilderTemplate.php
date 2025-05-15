<?php

namespace App\Models\SiteBuilder;

use App\Traits\Admin\Model\WithModelUploadPhoto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class BuilderTemplate extends Model {
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

    public function layouts():HasMany {
        return $this->hasMany(BuilderTemplateLayout::class, 'template_id');
    }

// تقدر تضيف علاقات filtered:
    public function headers() {
        return $this->layouts()->where('type', 'header');
    }

    public function footers() {
        return $this->layouts()->where('type', 'footer');
    }


}
