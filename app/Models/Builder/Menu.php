<?php

namespace App\Models\Builder;

use App\Enums\SiteBuilder\MenuLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'builder_menus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'template_id',
        'name',
        'slug',
        'location',
        'translations',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'translations' => 'array',
        'is_active' => 'boolean',
        'location' => MenuLocation::class,
    ];

    /**
     * Get the template that owns the menu.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    /**
     * Get the menu items for the menu.
     */
    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'menu_id')
            ->whereNull('parent_id')
            ->orderBy('sort_order');
    }

    /**
     * Get all menu items (including nested) for the menu.
     */
    public function allItems(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'menu_id')->orderBy('sort_order');
    }

    /**
     * Get translated value for a field.
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
     * Get translated name.
     */
    public function getTranslatedName(string $locale = null): string
    {
        return $this->getTranslation('name', $locale) ?: $this->name;
    }

    /**
     * Set translation for a field.
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