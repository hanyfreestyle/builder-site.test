<?php

namespace App\Models\Builder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Block extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'builder_blocks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'block_type_id',
        'data',
        'translations',
        'view_version',
        'sort_order',
        'is_active',
        'is_visible',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
        'translations' => 'array',
        'is_active' => 'boolean',
        'is_visible' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the pages related to this block.
     */
    public function pages()
    {
        return $this->belongsToMany(Page::class, 'builder_block_page')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderBy('builder_block_page.sort_order', 'asc');
    }

    /**
     * Get the block type that owns the block.
     */
    public function blockType(): BelongsTo
    {
        return $this->belongsTo(BlockType::class, 'block_type_id');
    }

    /**
     * Get the block data with translations applied.
     */
    public function getTranslatedData(string $locale = null): array
    {
        $locale = $locale ?: app()->getLocale();
        $data = $this->data ?: [];
        $translations = $this->translations ?: [];
        
        if (!isset($translations[$locale])) {
            return $data;
        }
        
        // Merge translations onto data
        $translatedData = $data;
        
        foreach ($translations[$locale] as $key => $value) {
            $keys = explode('.', $key);
            
            // Handle deep nesting using dot notation
            if (count($keys) > 1) {
                $current = &$translatedData;
                
                foreach ($keys as $i => $keyPart) {
                    if ($i === count($keys) - 1) {
                        $current[$keyPart] = $value;
                    } else {
                        if (!isset($current[$keyPart]) || !is_array($current[$keyPart])) {
                            $current[$keyPart] = [];
                        }
                        
                        $current = &$current[$keyPart];
                    }
                }
            } else {
                $translatedData[$key] = $value;
            }
        }
        
        return $translatedData;
    }

    /**
     * Set a translation for a specific field.
     */
    public function setTranslation(string $key, $value, string $locale = null): self
    {
        $locale = $locale ?: app()->getLocale();
        $translations = $this->translations ?: [];
        
        if (!isset($translations[$locale])) {
            $translations[$locale] = [];
        }
        
        $translations[$locale][$key] = $value;
        $this->translations = $translations;
        
        return $this;
    }
}