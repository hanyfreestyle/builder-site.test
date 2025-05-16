<?php

namespace App\Enums\SiteBuilder;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum MenuLocation: string {
    use EnumHasLabelOptionsTrait;

    case HEADER = 'header';
    case FOOTER = 'footer';
    case SIDEBAR = 'sidebar';
    case MOBILE = 'mobile';
    case SOCIAL = 'social';
    case OTHER = 'other';

    public function label(): string {
        return match ($this) {
            self::HEADER => __('site-builder/menu.locations.header'),
            self::FOOTER => __('site-builder/menu.locations.footer'),
            self::SIDEBAR => __('site-builder/menu.locations.sidebar'),
            self::MOBILE => __('site-builder/menu.locations.mobile'),
            self::SOCIAL => __('site-builder/menu.locations.social'),
            self::OTHER => __('site-builder/menu.locations.other'),
        };
    }
}
