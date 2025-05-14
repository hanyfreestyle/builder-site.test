<?php

namespace App\Enums\Status;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum EnumsFeatured: int {
    use EnumHasLabelOptionsTrait;

    case Featured  = 1;
    case NOT_Featured  = 0;

    public function label(): string {
        return match ($this) {
            self::Featured => __('default/lang.enum.featured.featured'),
            self::NOT_Featured  => __('default/lang.enum.featured.not_featured'),
        };
    }
}

