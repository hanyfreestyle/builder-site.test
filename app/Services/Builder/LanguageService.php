<?php

namespace App\Services\Builder;

use App\Models\Builder\Template;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LanguageService
{
    /**
     * Get filtered supported languages based on template supported languages
     *
     * @param Template|null $template
     * @return array
     */
    public static function getSupportedLanguages(?Template $template = null): array
    {
        // Get all configured languages from LaravelLocalization
        $configuredLanguages = LaravelLocalization::getSupportedLocales();
        
        // If no template provided or it has no supported_languages, return all configured languages
        if (!$template || empty($template->supported_languages)) {
            return $configuredLanguages;
        }
        
        // Filter configured languages to only include those supported by the template
        return array_filter($configuredLanguages, function($key) use ($template) {
            return in_array($key, $template->supported_languages);
        }, ARRAY_FILTER_USE_KEY);
    }
}
