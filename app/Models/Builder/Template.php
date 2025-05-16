<?php

namespace App\Models\Builder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
}