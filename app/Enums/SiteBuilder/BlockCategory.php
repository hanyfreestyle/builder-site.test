<?php

namespace App\Enums\SiteBuilder;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum BlockCategory: string {
    use EnumHasLabelOptionsTrait;

    case BASIC = 'basic';
    case MEDIA = 'media';
    case ADVANCED = 'advanced';

    public function label(): string {
        return match ($this) {
            self::BASIC => __('site-builder/block-type.labels.categories.basic'),
            self::MEDIA => __('site-builder/block-type.labels.categories.media'),
            self::ADVANCED => __('site-builder/block-type.labels.categories.advanced'),
        };
    }
}
