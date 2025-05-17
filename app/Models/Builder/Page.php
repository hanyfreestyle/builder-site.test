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
        'use_default_template',
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
        'use_default_template' => 'boolean',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'use_default_template' => false,
    ];

    /**
     * Get the template that owns the page.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    /**
     * Get all blocks for the page (including those linked via pivot table).
     */
    public function allBlocks()
    {
        return $this->belongsToMany(Block::class, 'builder_block_page')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderBy('pivot_sort_order');
    }
    
    /**
     * Get the blocks directly attached to the page (legacy support).
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
     * Get the template for this page, considering the use_default_template flag.
     * 
     * @return Template|null
     */
    public function getEffectiveTemplate(): ?Template
    {
        // If page is set to use default template or has no template assigned
        if ($this->use_default_template || $this->template_id === null) {
            return Template::getDefault();
        }
        
        // If the associated template is inactive, use the default
        if (!$this->template || !$this->template->is_active) {
            return Template::getDefault();
        }
        
        // Otherwise use the assigned template
        return $this->template;
    }

    /**
     * Update the template of this page to use the default template.
     * 
     * @return $this
     */
    public function useDefaultTemplate(): self
    {
        $this->use_default_template = true;
        return $this;
    }

    /**
     * Set a specific template for this page.
     * 
     * @param Template|int $template
     * @return $this
     */
    public function setSpecificTemplate($template): self
    {
        $templateId = $template instanceof Template ? $template->id : $template;
        $this->template_id = $templateId;
        $this->use_default_template = false;
        return $this;
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