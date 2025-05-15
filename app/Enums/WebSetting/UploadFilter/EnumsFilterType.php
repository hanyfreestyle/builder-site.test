<?php

namespace App\Enums\WebSetting\UploadFilter;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum EnumsFilterType: int {
    use EnumHasLabelOptionsTrait;

    case FilterType_1 = 1;
    case FilterType_2 = 2;
    case FilterType_3 = 3;
    case FilterType_4 = 4;
    case FilterType_5 = 5;


    public function label(): string {
        return match ($this) {
            self::FilterType_1 => __('filament/settings/uploadFilter.filter_type.filter_action_1'),
            self::FilterType_2 => __('filament/settings/uploadFilter.filter_type.filter_action_2'),
            self::FilterType_3 => __('filament/settings/uploadFilter.filter_type.filter_action_3'),
            self::FilterType_4 => __('filament/settings/uploadFilter.filter_type.filter_action_4'),
            self::FilterType_5 => __('filament/settings/uploadFilter.filter_type.filter_action_5'),

        };
    }
}

