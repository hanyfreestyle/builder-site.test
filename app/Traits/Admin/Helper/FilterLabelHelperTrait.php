<?php

namespace App\Traits\Admin\Helper;

trait FilterLabelHelperTrait {
    public static function applyLabelOrPlaceholder($filter, string $labelKey, bool $printLabel = true) {
        if ($printLabel) {
            return $filter->label(__($labelKey));
        } else {
            return $filter
                ->label('')
                ->placeholder(__($labelKey));
        }
    }

    public static function applyLabelOrPlaceholderForText($filter, string $labelKey, bool $printLabel = true) {
        if ($printLabel) {
            return $filter->label(__($labelKey));
        } else {
            return $filter
                ->hiddenLabel()
                ->placeholder(__($labelKey));
        }
    }

}
