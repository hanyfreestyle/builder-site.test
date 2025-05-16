<?php

namespace App\Models\Builder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BlockType extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'builder_block_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'category',
        'schema',
        'default_data',
        'is_active',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'schema' => 'array',
        'default_data' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the blocks that use this block type.
     */
    public function blocks(): HasMany
    {
        return $this->hasMany(Block::class, 'block_type_id');
    }

    /**
     * Get the templates that use this block type.
     */
    public function templates(): BelongsToMany
    {
        return $this->belongsToMany(Template::class, 'builder_template_block_types', 'block_type_id', 'template_id')
            ->withPivot(['view_versions', 'default_view_version', 'is_enabled', 'sort_order'])
            ->withTimestamps();
    }

    /**
     * Get the default data for this block type.
     */
    public function getDefaultData(): array
    {
        return $this->default_data ?: [];
    }

    /**
     * Get the schema fields for this block type.
     */
    public function getSchemaFields(): array
    {
        return $this->schema ?: [];
    }

    /**
     * Check if this block type is available for a template.
     */
    public function isAvailableForTemplate(int $templateId): bool
    {
        $relation = $this->templates()->where('template_id', $templateId)->first();
        
        if (!$relation) {
            return false;
        }
        
        return (bool) $relation->pivot->is_enabled;
    }

    /**
     * Get the available view versions for a specific template.
     */
    public function getAvailableViewVersionsForTemplate(int $templateId): array
    {
        $relation = $this->templates()->where('template_id', $templateId)->first();
        
        if (!$relation) {
            return ['default'];
        }
        
        return json_decode($relation->pivot->view_versions, true) ?: ['default'];
    }
}