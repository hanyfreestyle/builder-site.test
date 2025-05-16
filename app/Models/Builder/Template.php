<?php

namespace App\Models\Builder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Template extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'builder_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'thumbnail',
        'settings',
        'supported_languages',
        'is_active',
        'is_default',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'settings' => 'array',
        'supported_languages' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * The booting method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // عند تعيين قالب جديد كافتراضي، إلغاء الافتراضي من القوالب الأخرى
        static::saving(function($template) {
            if ($template->is_default && $template->isDirty('is_default')) {
                // إلغاء الافتراضي من القوالب الأخرى
                static::where('id', '!=', $template->id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }
        });
    }

    /**
     * Get the pages associated with the template.
     */
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'template_id');
    }

    /**
     * Get the menus associated with the template.
     */
    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class, 'template_id');
    }

    /**
     * Get the block types associated with the template.
     */
    public function blockTypes(): BelongsToMany
    {
        return $this->belongsToMany(BlockType::class, 'builder_template_block_types', 'template_id', 'block_type_id')
            ->withPivot(['view_versions', 'default_view_version', 'is_enabled', 'sort_order'])
            ->withTimestamps();
    }

    /**
     * Get available view versions for a block type.
     */
    public function getAvailableViewVersions(int $blockTypeId): array
    {
        $relation = $this->blockTypes()->where('block_type_id', $blockTypeId)->first();
        
        if (!$relation) {
            return ['default'];
        }
        
        return json_decode($relation->pivot->view_versions, true) ?: ['default'];
    }

    /**
     * Get default view version for a block type.
     */
    public function getDefaultViewVersion(int $blockTypeId): string
    {
        $relation = $this->blockTypes()->where('block_type_id', $blockTypeId)->first();
        
        if (!$relation) {
            return 'default';
        }
        
        return $relation->pivot->default_view_version ?: 'default';
    }

    /**
     * Check if a block type is enabled for this template.
     */
    public function isBlockTypeEnabled(int $blockTypeId): bool
    {
        $relation = $this->blockTypes()->where('block_type_id', $blockTypeId)->first();
        
        if (!$relation) {
            return false;
        }
        
        return (bool) $relation->pivot->is_enabled;
    }

    /**
     * Set this template as the default template
     */
    public function setAsDefault(): bool
    {
        // استخدام معاملة قاعدة البيانات لضمان السلامة
        DB::transaction(function() {
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

    /**
     * Get the default template
     */
    public static function getDefault()
    {
        return self::where('is_default', true)
            ->where('is_active', true)
            ->first();
    }
}