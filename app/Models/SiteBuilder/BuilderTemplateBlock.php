<?php

namespace App\Models\SiteBuilder;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuilderTemplateBlock extends Model {

    protected $table = 'builder_template_blocks';
    public $timestamps = false;


    protected $fillable = [
        'template_id',
        'block_definition_id',
        'photo',
        'photo_thumbnail',
        'is_active',
        'position',
    ];
    // cast للراحة عند التعامل مع الصور مستقبلاً
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function template() :BelongsTo {
        return $this->belongsTo(BuilderTemplate::class, 'template_id');
    }

    public function definition() :BelongsTo {
        return $this->belongsTo(TemplateBlockDefinition::class, 'block_definition_id');
    }

}
