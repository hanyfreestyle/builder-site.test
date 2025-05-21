<?php

namespace App\Models\Builder;

use App\Traits\Admin\Model\WithModelEvents;
use App\Traits\Admin\Model\WithModelUploadPhoto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Template extends Model {
    use   SoftDeletes;
//    use WithModelEvents;
    use WithModelUploadPhoto;

    protected $table = 'builder_templates';
    protected $fillable = [
        'name',
        'slug',
        'description',
        'photo',
        'photo_thumbnail',
        'settings',
        'supported_languages',
        'is_active',
        'is_default',
    ];
    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'settings' => 'array',
        'supported_languages' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected static function boot() {
        parent::boot();
//        static::bootWithModelEvents();
        static::bootWithModelUploadPhoto();
        // عند تعيين قالب جديد كافتراضي، إلغاء الافتراضي من القوالب الأخرى
        static::saving(function ($template) {
            if ($template->is_default && $template->isDirty('is_default')) {
                // إلغاء الافتراضي من القوالب الأخرى
                static::where('id', '!=', $template->id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }
        });
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function pages(): HasMany {
        return $this->hasMany(Page::class, 'template_id');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function menus(): HasMany {
        return $this->hasMany(Menu::class, 'template_id');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function blockTypes(): BelongsToMany {
        return $this->belongsToMany(BlockType::class, 'builder_template_block_types', 'template_id', 'block_type_id')
            ->withPivot(['view_versions', 'default_view_version', 'is_enabled', 'sort_order'])
            ->withTimestamps();
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function getAvailableViewVersions(int $blockTypeId): array {
        $relation = $this->blockTypes()->where('block_type_id', $blockTypeId)->first();
        if (!$relation) {
            return ['default'];
        }
        return json_decode($relation->pivot->view_versions, true) ?: ['default'];
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function getDefaultViewVersion(int $blockTypeId): string {
        $relation = $this->blockTypes()->where('block_type_id', $blockTypeId)->first();
        if (!$relation) {
            return 'default';
        }
        return $relation->pivot->default_view_version ?: 'default';
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function isBlockTypeEnabled(int $blockTypeId): bool {
        $relation = $this->blockTypes()->where('block_type_id', $blockTypeId)->first();
        if (!$relation) {
            return false;
        }
        return (bool)$relation->pivot->is_enabled;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function setAsDefault(): bool {
        // استخدام معاملة قاعدة البيانات لضمان السلامة
        DB::transaction(function () {
            // إلغاء تعيين أي قالب آخر كافتراضي
            self::where('id', '!=', $this->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
            // تعيين هذا القالب كافتراضي
            $this->is_default = true;
            $this->save();
        });
        return true;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getDefault() {
        return self::where('is_default', true)
            ->where('is_active', true)
            ->first();
    }
}
