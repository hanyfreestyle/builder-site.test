<?php

namespace App\Traits\Admin\Helper;

trait EnumHasLabelOptionsTrait {
    public static function options(): array {
        return collect(static::cases())
            ->mapWithKeys(fn($case) => [
                $case->value => $case->label()
            ])
            ->toArray();
    }
}
