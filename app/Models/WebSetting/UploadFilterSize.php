<?php

namespace App\Models\WebSetting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class UploadFilterSize extends Model {

    protected $table = "config_upload_filter_sizes";
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'filter_id',
        'type',
        'width',
        'height',
        'canvas_back',
        'text_state',
        'watermark_state'
    ];

    public function filter(): BelongsTo {
        return $this->belongsTo(UploadFilter::class,'filter_id');
    }

}
