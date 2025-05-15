<?php

namespace App\Models\SiteBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateBlockDefinition extends Model {

    protected $table = 'builder_template_block_definitions';
    public $timestamps = false;

    protected $fillable = [
        'type',
        'name',
        'schema',
    ];

    protected $casts = [
        'name' => 'array',
        'schema' => 'array',
    ];

    /**
     * العلاقة مع builder_template_blocks
     */
    public function templateBlocks(): HasMany {
        return $this->hasMany(BuilderTemplateBlock::class, 'block_definition_id');
    }
}
