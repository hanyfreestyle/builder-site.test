<?php

namespace App\Enums\SiteBuilder;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum EnumsTemplateLayouts: string {
    use EnumHasLabelOptionsTrait;

    case Header = "header";
    case Footer = "footer";

    public function label(): string {
        return match ($this) {
            self::Header => __('site-builder/builder-template.enum_TemplateLayouts.Header'),
            self::Footer =>  __('site-builder/builder-template.enum_TemplateLayouts.Footer'),
        };
    }
}

