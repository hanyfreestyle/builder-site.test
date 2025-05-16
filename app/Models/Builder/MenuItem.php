<?php

namespace App\Models\Builder;

use App\Enums\SiteBuilder\MenuItemType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'builder_menu_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'type',
        'url',
        'page_id',
        'route',
        'icon',
        'translations',
        'target_blank',
        'is_active',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'translations' => 'array',
        'target_blank' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'type' => MenuItemType::class,
    ];

    /**
     * Get the menu that owns the menu item.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    /**
     * Get the parent menu item.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * Get the child menu items.
     */
    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Get the page associated with the menu item.
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    /**
     * Get the URL for the menu item.
     */
    public function getUrl(): string
    {
        switch ($this->type) {
            case MenuItemType::PAGE:
                if ($this->page) {
                    return url($this->page->slug);
                }
                break;
            
            case MenuItemType::ROUTE:
                if ($this->route) {
                    try {
                        return route($this->route);
                    } catch (\Exception $e) {
                        // Route not found, return empty string
                    }
                }
                break;
            
            case MenuItemType::URL:
            default:
                if ($this->url) {
                    return $this->url;
                }
                break;
        }

        return '#';
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