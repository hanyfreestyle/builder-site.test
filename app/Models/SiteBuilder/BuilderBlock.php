<?php

namespace App\Models\SiteBuilder;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BuilderBlock extends Model {

    protected $table = 'builder_blocks';
    public $timestamps = false;

    protected $fillable = [
        'page_id',
        'type',
        'data',
        'position',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function page(): BelongsTo {
        return $this->belongsTo(BuilderPage::class, 'page_id');
    }

    // ممكن تضيف علاقة مستقبلًا لو حبيت تجيب تعريف البلوك من النوع
    public function definition() :HasOne {
        return $this->hasOne(TemplateBlockDefinition::class, 'type', 'type');
    }
}
