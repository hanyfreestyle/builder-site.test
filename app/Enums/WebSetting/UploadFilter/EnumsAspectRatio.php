<?php

namespace App\Enums\WebSetting\UploadFilter;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum EnumsAspectRatio: string {
    use EnumHasLabelOptionsTrait;

    case AspectRatio_1 = "1:1";
    case AspectRatio_2 = "16:9";
    case AspectRatio_3 = "9:16";
    case AspectRatio_4 = "4:5";
    case AspectRatio_5 = "5:7";


    public function label(): string {
        return match ($this) {
            self::AspectRatio_1 => __('filament/settings/uploadFilter.crop_aspect_ratio.ratio_1'),
            self::AspectRatio_2 => __('filament/settings/uploadFilter.crop_aspect_ratio.ratio_2'),
            self::AspectRatio_3 => __('filament/settings/uploadFilter.crop_aspect_ratio.ratio_3'),
            self::AspectRatio_4 => __('filament/settings/uploadFilter.crop_aspect_ratio.ratio_4'),
            self::AspectRatio_5 => __('filament/settings/uploadFilter.crop_aspect_ratio.ratio_5'),

        };
    }
}

