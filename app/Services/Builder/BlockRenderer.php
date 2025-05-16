<?php

namespace App\Services\Builder;

use App\Models\Builder\Block;
use App\Models\Builder\Template;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;

class BlockRenderer
{
    /**
     * Render a block with appropriate view template.
     *
     * @param Block $block The block to render
     * @param array $additionalData Additional data to pass to the view
     * @return string The rendered block HTML
     */
    public function render(Block $block, array $additionalData = []): string
    {
        // Get the block type
        $blockType = $block->blockType;
        if (!$blockType || !$blockType->is_active) {
            return '<!-- Block type not found or inactive -->';
        }

        // Get the page and template
        $page = $block->page;
        $template = $page->template;
        if (!$template || !$template->is_active) {
            return '<!-- Template not found or inactive -->';
        }

        // Skip inactive or invisible blocks
        if (!$block->is_active || !$block->is_visible) {
            return '<!-- Block inactive or not visible -->';
        }

        // Get block data with translations applied
        $blockData = $block->getTranslatedData();

        // Find the most appropriate view for this block
        $viewPath = $this->findBlockView($template, $blockType->slug, $block->view_version);

        // If no view is found, return a comment
        if (!$viewPath) {
            return '<!-- No view found for block: ' . $blockType->slug . ' -->';
        }

        // Prepare data for the view
        $data = array_merge([
            'block' => $block,
            'blockType' => $blockType,
            'page' => $page,
            'template' => $template,
            'data' => $blockData,
        ], $additionalData);

        // Render the view
        return View::make($viewPath, $data)->render();
    }

    /**
     * Find the most appropriate view for a block.
     *
     * @param Template $template The current template
     * @param string $blockTypeSlug The block type slug
     * @param string $viewVersion The requested view version
     * @return string|null The view path, or null if not found
     */
    protected function findBlockView(Template $template, string $blockTypeSlug, string $viewVersion): ?string
    {
        // Priority order for views:
        // 1. Template-specific view with requested version
        // 2. Template-specific default version
        // 3. Global view with requested version
        // 4. Global default version

        // View paths to check
        $viewsToCheck = [
            // 1. Template-specific with requested version
            "templates.{$template->slug}.blocks.{$blockTypeSlug}.{$viewVersion}",
            // 2. Template-specific default
            "templates.{$template->slug}.blocks.{$blockTypeSlug}.default",
            // 3. Global with requested version
            "blocks.{$blockTypeSlug}.{$viewVersion}",
            // 4. Global default
            "blocks.{$blockTypeSlug}.default",
        ];

        // Check each view path
        foreach ($viewsToCheck as $viewPath) {
            if (View::exists($viewPath)) {
                return $viewPath;
            }
        }

        // No view found
        return null;
    }

    /**
     * Get all available view versions for a block type in a template.
     *
     * @param Template $template The template
     * @param string $blockTypeSlug The block type slug
     * @return array Array of available view versions
     */
    public function getAvailableViewVersions(Template $template, string $blockTypeSlug): array
    {
        $versions = [];

        // Check global block views
        $globalPath = resource_path("views/blocks/{$blockTypeSlug}");
        if (File::isDirectory($globalPath)) {
            $files = File::files($globalPath);
            foreach ($files as $file) {
                $version = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                $versions[$version] = "Global: {$version}";
            }
        }

        // Check template-specific views
        $templatePath = resource_path("views/templates/{$template->slug}/blocks/{$blockTypeSlug}");
        if (File::isDirectory($templatePath)) {
            $files = File::files($templatePath);
            foreach ($files as $file) {
                $version = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                $versions[$version] = "Template: {$version}";
            }
        }

        return $versions;
    }
}