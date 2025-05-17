<?php

namespace App\Enums\SiteBuilder;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum FieldWidth: string {
    use EnumHasLabelOptionsTrait;

    case FULL = 'full';
    case HALF = '1/2';
    case THIRD = '1/3';
    case TWO_THIRDS = '2/3';
    case QUARTER = '1/4';
    case THREE_QUARTERS = '3/4';
    case SIXTH = '1/6';
    case FIVE_SIXTHS = '5/6';

    /**
     * Get the translated label for this field width
     *
     * @return string Translated label
     */
    public function label(): string {
        return match ($this) {
            self::FULL => __('site-builder/block-type.field_width.full'),
            self::HALF => __('site-builder/block-type.field_width.half'),
            self::THIRD => __('site-builder/block-type.field_width.third'),
            self::TWO_THIRDS => __('site-builder/block-type.field_width.two_thirds'),
            self::QUARTER => __('site-builder/block-type.field_width.quarter'),
            self::THREE_QUARTERS => __('site-builder/block-type.field_width.three_quarters'),
            self::SIXTH => __('site-builder/block-type.field_width.sixth'),
            self::FIVE_SIXTHS => __('site-builder/block-type.field_width.five_sixths'),
        };
    }

    /**
     * Get the column span value for Filament forms
     *
     * @return string|int Filament column span value
     */
    public function toColumnSpan(): string|int {
        return match ($this) {
            self::FULL => 'full',           // Full width
            self::HALF => 6,                // Half width (6 of 12 columns)
            self::THIRD => 4,               // One third (4 of 12 columns)
            self::TWO_THIRDS => 8,          // Two thirds (8 of 12 columns)
            self::QUARTER => 3,             // One quarter (3 of 12 columns)
            self::THREE_QUARTERS => 9,      // Three quarters (9 of 12 columns)
            self::SIXTH => 2,               // One sixth (2 of 12 columns)
            self::FIVE_SIXTHS => 10,        // Five sixths (10 of 12 columns)
        };
    }

    /**
     * Get percentage width representation
     *
     * @return string Percentage string with % symbol
     */
    public function toPercentage(): string {
        return match ($this) {
            self::FULL => '100%',
            self::HALF => '50%',
            self::THIRD => '33.33%',
            self::TWO_THIRDS => '66.66%',
            self::QUARTER => '25%',
            self::THREE_QUARTERS => '75%',
            self::SIXTH => '16.66%',
            self::FIVE_SIXTHS => '83.33%',
        };
    }

    /**
     * Get all available options as an array suitable for select fields
     *
     * @return array Array of width options with labels
     */
    public static function getSelectOptions(): array {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }
        return $options;
    }

    /**
     * Static method to convert a string width value to column span
     * This is useful when working with values that aren't enum instances
     *
     * @param string $width String width value ('1/2', 'full', etc)
     * @return string|int Column span value
     */
    public static function stringToColumnSpan(string $width): string|int {
        // Try to match with an enum case first
        foreach (self::cases() as $case) {
            if ($case->value === $width) {
                return $case->toColumnSpan();
            }
        }

        // If not found, use similar matching as convertWidthToColumnSpan
        return match(strtolower(trim($width))) {
            '1/1', 'full', '100%' => 'full',
            '1/2', '50%' => 6,
            '1/3', '33%', '33.33%' => 4,
            '2/3', '66%', '66.66%' => 8,
            '1/4', '25%' => 3,
            '3/4', '75%' => 9,
            '1/6', '16%', '16.66%' => 2,
            '5/6', '83%', '83.33%' => 10,
            default => 'full',
        };
    }
}
