<?php

namespace App\Enums\Layouts;
use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum FontSizePx: string {
    use EnumHasLabelOptionsTrait;

    case Size12 = '12px';
    case Size14 = '14px';
    case Size16 = '16px';
    case Size18 = '18px';
    case Size20 = '20px';

    public function label(): string {
        return str_replace('px', '', $this->value) . ' px';
    }
}
