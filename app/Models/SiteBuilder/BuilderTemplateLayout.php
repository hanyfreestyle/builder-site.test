<?php

namespace App\Models\SiteBuilder;

use App\Traits\Admin\Model\WithModelUploadPhoto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class BuilderTemplateLayout extends Model {
    use WithModelUploadPhoto;

    protected $table = "builder_template_layout";
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['template_id', 'type', 'slug', 'name', 'photo', 'photo_thumbnail', 'is_default', 'is_active', 'position'];

    protected $casts = [
        'name' => 'array',
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
