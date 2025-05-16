<?php

namespace App\Enums\SiteBuilder;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum FieldWidth: string {
    use EnumHasLabelOptionsTrait;

    case HALF = '1/2';
    case THIRD = '1/3';
    case TWO_THIRDS = '2/3';
    case FULL = 'full';

    public function label(): string {
        return match ($this) {
            self::HALF => __('site-builder/block-type.field_width.half'),
            self::THIRD => __('site-builder/block-type.field_width.third'),
            self::TWO_THIRDS => __('site-builder/block-type.field_width.two_thirds'),
            self::FULL => __('site-builder/block-type.field_width.full'),
        };
    }
}
