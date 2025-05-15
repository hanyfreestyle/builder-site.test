<?php

namespace App\Models\DevelopersTools;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FilesListGroup extends Model {
    protected $connection = 'app_files_db';
    protected $table = "admin_files_lists_group";
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'name', 'is_active', 'position'
    ];

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function files_list(): HasMany {
        return $this->hasMany(FilesList::class,'group_id','id')
            ->where('is_active',true)
            ->orderBy('position');
    }

}
