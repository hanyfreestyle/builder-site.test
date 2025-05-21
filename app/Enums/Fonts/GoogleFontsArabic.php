<?php

namespace App\Enums\Fonts;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum GoogleFontsArabic: string {

    use EnumHasLabelOptionsTrait;

    case Cairo = 'Cairo, sans-serif';
    case Tajawal = 'Tajawal, sans-serif';
    case Almarai = 'Almarai, sans-serif';
    case Amiri = 'Amiri, serif';

    public function label(): string {
        return match ($this) {
            self::Cairo => 'Cairo',
            self::Tajawal => 'Tajawal',
            self::Almarai => 'Almarai',
            self::Amiri => 'Amiri',
        };
    }
}
