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
        static::saving(function ($layout) {
            if ($layout->is_default) {
                // نلغي الافتراضية عن البقية لنفس القالب ولنفس النوع
                static::where('template_id', $layout->template_id)
                    ->where('type', $layout->type)
                    ->where('id', '!=', $layout->id)
                    ->update(['is_default' => false]);
            }
        });
    }

    public function template(): BelongsTo {
        return $this->belongsTo(BuilderTemplate::class, 'template_id');
    }


}
