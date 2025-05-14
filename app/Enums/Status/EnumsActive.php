<?php

namespace App\Enums\Status;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum EnumsActive: int {
    use EnumHasLabelOptionsTrait;

    case ACTIVE = 1;
    case INACTIVE = 0;

    public function label(): string {
        return match ($this) {
            self::ACTIVE => __('default/lang.enum.active.is_active'),
            self::INACTIVE => __('default/lang.enum.active.in_active'),
        };
    }
}

