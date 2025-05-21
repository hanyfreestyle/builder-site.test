<?php
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getTranslatedValue')) {
    function getTranslatedValue(?array $translations): ?string {
        if (!is_array($translations)) {
            return null;
        }

        $locale = app()->getLocale();

        return $translations[$locale]
            ?? collect($translations)->filter()->first() // fallback لأول ترجمة غير null
            ?? null;
    }
}




