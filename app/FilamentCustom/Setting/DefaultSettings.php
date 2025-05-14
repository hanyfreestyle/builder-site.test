<?php

namespace App\FilamentCustom\Setting;

use App\Models\WebSetting\UploadFilter;
use App\Traits\Admin\Helper\FilterLabelHelperTrait;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

class DefaultSettings {
    use FilterLabelHelperTrait;

    protected string $modelName = '';
    protected bool $gallery = false;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function __construct() {
        $this->uploadFilter = UploadFilter::getUploadFilterCashList()->pluck('name', 'id');
    }

    public static function make(): static {
        return new static();
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function getColumns($sections): array {
        $sectionForm = [];
        $collapsed = false;

        foreach ($sections as $section) {
            $settings = [];

            if (!isset($section['SettingList']) || !is_array($section['SettingList'])) {
                continue;
            }

            foreach ($section['SettingList'] as $key => $value) {
                $lang = isset($value['lang']) ? " ({$value['lang']})" : '';

                if ($value['view'] ?? false) {
                    $settings[] = $this->buildSelectUploadFilter(
                        $key . '_filter_photo',
                        __('default/model-setting.input.photo_filter') . $lang
                    );
                }

                if (!empty($value['gallery'])) {
                    $settings[] = $this->buildSelectUploadFilter(
                        $key . '_filter_gallery',
                        __('default/model-setting.input.gallery_filter') . $lang
                    );
                }
            }

            if (count($settings) > 0) {
                $sectionForm[] = Section::make($section['title'] ?? null)
                    ->schema($settings)->columnSpan(2)
                    ->collapsible()
                    ->collapsed($collapsed)
                    ->columns(2);
            }
        }
        return $sectionForm;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    private function buildSelectUploadFilter(string $name, string $label): Select {
        return Select::make($name)
            ->label($label)
            ->options($this->uploadFilter)
            ->searchable()
            ->preload()
            ->required();
    }

}
