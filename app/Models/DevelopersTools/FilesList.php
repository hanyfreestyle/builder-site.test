<?php

namespace App\Models\DevelopersTools;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FilesList extends Model {

    protected $connection = 'app_files_db';
    protected $table = "admin_files_lists";
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'title', 'cat_id', 'group_id', 'is_exist', 'files', 'folders', 'copy', 'delete', 'import',
        'is_active', 'is_archived', 'is_feature', 'position'
    ];

    protected $casts = [
        'files' => 'array', // يجعل Laravel يخزن البيانات كمصفوفة JSON
        'folders' => 'array', // يجعل Laravel يخزن البيانات كمصفوفة JSON
    ];

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function group(): BelongsTo {
        return $this->belongsTo(FilesListGroup::class,'group_id','id');
    }
}
