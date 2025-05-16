<?php

namespace App\Enums\SiteBuilder;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum MenuItemType: string {
    use EnumHasLabelOptionsTrait;

    case URL = 'url';
    case PAGE = 'page';
    case ROUTE = 'route';
    case SECTION = 'section';

    public function label(): string {
        return match ($this) {
            self::URL => __('site-builder/menu-item.types.url'),
            self::PAGE => __('site-builder/menu-item.types.page'),
            self::ROUTE => __('site-builder/menu-item.types.route'),
            self::SECTION => __('site-builder/menu-item.types.section'),
        };
    }
}
