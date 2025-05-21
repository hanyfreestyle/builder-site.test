<?php

namespace App\Enums\Fonts;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum GoogleFontsEnglish: string {
    use EnumHasLabelOptionsTrait;

    case Roboto = 'Roboto, sans-serif';
    case OpenSans = 'Open Sans, sans-serif';
    case Lato = 'Lato, sans-serif';
    case Poppins = 'Poppins, sans-serif';

    public function label(): string {
        return match ($this) {
            self::Roboto => 'Roboto',
            self::OpenSans => 'Open Sans',
            self::Lato => 'Lato',
            self::Poppins => 'Poppins',
        };
    }
}
