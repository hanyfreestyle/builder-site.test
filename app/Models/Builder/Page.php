<?php

namespace App\Models\Builder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'builder_pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'template_id',
        'title',
        'slug',
        'description',
        'meta_tags',
        'translations',
        'is_homepage',
        'is_active',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'meta_tags' => 'array',
        'translations' => 'array',
        'is_homepage' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the template that owns the page.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    /**
     * Get the blocks for the page.
     */
    public function blocks(): HasMany
    {
        return $this->hasMany(Block::class, 'page_id')->orderBy('sort_order');
    }

    /**
     * Get the menu items that link to this page.
     */
    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'page_id');
    }

    /**
     * Get the translated value for a field.
     */
    public function getTranslation(string $field, string $locale = null): ?string
    {
        $locale = $locale ?: app()->getLocale();
        $translations = $this->translations ?: [];

        if (isset($translations[$locale][$field])) {
            return $translations[$locale][$field];
        }

        // Fallback to the main field
        return $this->$field;
    }

    /**
     * Get the translated title.
     */
    public function getTranslatedTitle(string $locale = null): string
    {
        return $this->getTranslation('title', $locale) ?: $this->title;
    }

    /**
     * Get the translated description.
     */
    public function getTranslatedDescription(string $locale = null): ?string
    {
        return $this->getTranslation('description', $locale) ?: $this->description;
    }

    /**
     * Set the translation for a field.
     */
    public function setTranslation(string $field, string $value, string $locale = null): self
    {
        $locale = $locale ?: app()->getLocale();
        $translations = $this->translations ?: [];
        
        if (!isset($translations[$locale])) {
            $translations[$locale] = [];
        }
        
        $translations[$locale][$field] = $value;
        $this->translations = $translations;
        
        return $this;
    }
}