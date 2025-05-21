<?php

namespace App\Enums\Layouts;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum FontSizeRem: string {
    use EnumHasLabelOptionsTrait;

    case Rem075 = '0.75rem';
    case Rem1 = '1rem';
    case Rem125 = '1.25rem';
    case Rem15 = '1.5rem';
    case Rem2 = '2rem';

    public function label(): string {
        return $this->value;
    }
}
