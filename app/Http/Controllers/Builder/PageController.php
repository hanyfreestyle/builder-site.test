<?php

namespace App\Http\Controllers\Builder;

use App\Http\Controllers\Controller;
use App\Models\Builder\Page;
use App\Models\Builder\Template;
use App\Models\Builder\Menu;
use App\Services\Builder\BlockRenderer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * The block renderer service.
     *
     * @var BlockRenderer
     */
    protected $blockRenderer;

    /**
     * Create a new controller instance.
     *
     * @param BlockRenderer $blockRenderer
     */
    public function __construct(BlockRenderer $blockRenderer)
    {
        $this->blockRenderer = $blockRenderer;
        
        // Set locale from session
        if (session()->has('locale')) {
            App::setLocale(session('locale'));
        }
    }

    /**
     * Display the homepage.
     *
     * @return View
     */
    public function home(): View
    {
        // Find the homepage
        $homepage = Page::where('is_homepage', true)
            ->where('is_active', true)
            ->first();

        if (!$homepage) {
            abort(404, 'Homepage not found');
        }

        return $this->showPage($homepage);
    }

    /**
     * Display a specific page by slug.
     *
     * @param string $slug
     * @return View
     */
    public function page(string $slug): View
    {
        // Find the page by slug
        $page = Page::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$page) {
            abort(404, 'Page not found');
        }

        return $this->showPage($page);
    }

    /**
     * Render a page with its blocks.
     *
     * @param Page $page
     * @return View
     */
    protected function showPage(Page $page): View
    {
        $template = $page->template;

        if (!$template || !$template->is_active) {
            abort(404, 'Template not found or inactive');
        }

        // Get page blocks
        $blocks = $page->blocks()
            ->where('is_active', true)
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->get();

        // Render each block
        $renderedBlocks = [];
        foreach ($blocks as $block) {
            $renderedBlocks[] = $this->blockRenderer->render($block);
        }

        // Get menus for this template
        $menus = [];
        $templateMenus = Menu::where('template_id', $template->id)
            ->where('is_active', true)
            ->get();

        foreach ($templateMenus as $menu) {
            // Convert Enum to string to use as array key
            $locationKey = $menu->location->value;
            $menus[$locationKey] = $menu;
        }

        // Prepare SEO meta tags
        $metaTags = $page->meta_tags ?: [];

        // Return view with data
        return view("templates.{$template->slug}.page", [
            'page' => $page,
            'template' => $template,
            'renderedBlocks' => $renderedBlocks,
            'menus' => $menus,
            'metaTags' => $metaTags,
        ]);
    }
}