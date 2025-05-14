<?php

namespace App\Enums\Status;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum EnumsStock: int {
    use EnumHasLabelOptionsTrait;

    case IN_STOCK = 1;
    case OUT_OF_STOCK = 0;

    public function label(): string {
        return match ($this) {
            self::IN_STOCK => __('default/lang.enum.stocks.in_stock'),
            self::OUT_OF_STOCK => __('default/lang.enum.stocks.out_of_stock'),
        };
    }
}

