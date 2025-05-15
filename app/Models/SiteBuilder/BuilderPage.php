<?php

namespace App\Models\SiteBuilder;

use Illuminate\Database\Eloquent\Model;

class BuilderPage extends Model {

    protected $table = 'builder_pages';
    public $timestamps = false;


    protected $fillable = [
        'template_id',
        'slug',
        'title',
        'locale',
        'is_active',
    ];

    protected $casts = [
        'title' => 'array',
        'is_active' => 'boolean',
    ];

    public function template() {
        return $this->belongsTo(BuilderTemplate::class, 'template_id');
    }

    public function blocks() {
        return $this->hasMany(BuilderBlock::class, 'page_id');
    }

}
