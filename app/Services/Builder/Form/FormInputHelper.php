<?php

namespace App\Services\Builder\Form;

use App\FilamentCustom\UploadFile\WebpUploadFixedSizeBuilder;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Guava\FilamentIconPicker\Forms\IconPicker;
use Filament\Forms\Components\Component;

class FormInputHelper {

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function buildFieldSwitch($type, $field): ?Component {
        switch ($type) {
            case 'text':
                $formField = self::TextInputHelper($field);
                break;
            case 'textarea':
                $formField = self::TextareaHelper($field);
                break;
            case 'rich_text':
                $formField = self::RichEditorHelper($field);
                break;
            case 'select':
                $formField = self::SelectHelper($field);
                break;
            case 'radio':
                $formField = self::RadioHelper($field);
                break;
            case 'date':
                $formField = self::DatePickerHelper($field);
                break;
            case 'time':
                $formField = self::TimePickerHelper($field);
                break;
            case 'color':
                $formField = self::ColorPickerHelper($field);
                break;
            case 'icon':
                $formField = self::IconPickerHelper($field);
                break;
            case 'number':
                $formField = self::NumberInputHelper($field);
                break;
            case 'link':
                $formField = self::LinkInputHelper($field);
                break;
            default:
                $formField = self::TextInputHelper($field);
                break;
        }
        return $formField;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function DefaultRepeaterFields(): array {
        return [
            TextInput::make('title')
                ->label(__('site-builder/general.title'))
                ->required(),
            Textarea::make('description')
                ->label(__('site-builder/general.description')),
        ];
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function LoadFormConfig($field): array {
        $config = [];
        $config['name'] = $field['name'] ?? '';
        $config['label'] = $field['label'] ?? $config['name'];
        $config['required'] = $field['required'] ?? false;
        $config['placeholder'] = $field['placeholder'] ?? null;
        $config['help'] = $field['help'] ?? null;
        $config['defaultValue'] = $field['default'] ?? null;

        $config['with_thumbnail'] = $field['config']['with_thumbnail'] ?? false;
        $config['img_width'] = $field['config']['width'] ?? 100;
        $config['img_height'] = $field['config']['height'] ?? 100;
        $config['thumb_width'] = $field['config']['thumb_width'] ?? 100;
        $config['thumb_height'] = $field['config']['thumb_height'] ?? 100;

        return $config;
    }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public
    static function TextInputHelper($field): TextInput {
        $config = self::LoadFormConfig($field);
        return TextInput::make("data.{$config['name']}")
            ->label($config['label'])
            ->required($config['required'])
            ->placeholder($config['placeholder'])
            ->helperText($config['help'])
            ->default($config['defaultValue']);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public
    static function TextareaHelper($field): Textarea {
        $config = self::LoadFormConfig($field);
        return Textarea::make("data.{$config['name']}")
            ->label($config['label'])
            ->required($config['required'])
            ->placeholder($config['placeholder'])
            ->helperText($config['help'])
            ->default($config['defaultValue'])
            ->rows(6);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public
    static function SelectHelper($field): Select {
        $config = self::LoadFormConfig($field);
        $options = $field['options'] ?? [];

        return Select::make("data.{$config['name']}")
            ->label($config['label'])
            ->options($options)
            ->required($config['required'])
            ->placeholder($config['placeholder'])
            ->searchable()
            ->preload()
            ->helperText($config['help'])
            ->default($config['defaultValue']);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public
    static function RadioHelper($field): Toggle {
        $config = self::LoadFormConfig($field);
        return Toggle::make("data.{$config['name']}")
            ->label($config['label'])
            ->default($config['defaultValue'])
            ->inline(false)
            ->helperText($config['help']);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public
    static function DatePickerHelper($field): DatePicker {
        $config = self::LoadFormConfig($field);

        return DatePicker::make("data.{$config['name']}")
            ->label($config['label'])
            ->required($config['required'])
            ->placeholder($config['placeholder'])
            ->helperText($config['help']);

    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public
    static function RichEditorHelper($field): RichEditor {
        $config = self::LoadFormConfig($field);
        return RichEditor::make("data.{$config['name']}")
            ->label($config['label'])
            ->required($config['required'])
            ->placeholder($config['placeholder'])
            ->helperText($config['help'])
            ->default($config['defaultValue']);

    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public
    static function TimePickerHelper($field): TimePicker {
        $config = self::LoadFormConfig($field);
        return TimePicker::make("data.{$config['name']}")
            ->label($config['label'])
            ->required($config['required'])
            ->placeholder($config['placeholder'])
            ->helperText($config['help'])
            ->default($config['defaultValue']);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public
    static function ColorPickerHelper($field): ColorPicker {
        $config = self::LoadFormConfig($field);
        return ColorPicker::make("data.{$config['name']}")
            ->label($config['label'])
            ->required($config['required'])
            ->helperText($config['help'])
            ->default($config['defaultValue']);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public
    static function IconPickerHelper($field): IconPicker {
        $config = self::LoadFormConfig($field);
        return IconPicker::make("data.{$config['name']}")
            ->label($config['label'])
            ->required($config['required'])
            ->searchLabels()
            ->preload()
            ->helperText($config['help'] ?: __('site-builder/block.icon_help'))
            ->columns([
                'default' => 2,
                'lg' => 6,
                '2xl' => 8,
            ])
            ->sets(['fas', 'fab', "fontawesome-solid", "fontawesome-brands"]);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public
    static function NumberInputHelper($field): TextInput {
        $config = self::LoadFormConfig($field);
        return TextInput::make("data.{$config['name']}")
            ->label($config['label'])
            ->numeric()
            ->required($config['required'])
            ->placeholder($config['placeholder'])
            ->helperText($config['help'])
            ->default($config['defaultValue']);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public
    static function LinkInputHelper($field): Group {
        $config = self::LoadFormConfig($field);
        return Group::make([
            TextInput::make("data.{$config['name']}.text")
                ->label(__('site-builder/block.link_text'))
                ->required($config['required'])
                ->placeholder(__('site-builder/block.link_text_placeholder'))
                ->default($config['defaultValue']['text'] ?? null),

            TextInput::make("data.{$config['name']}.url")
                ->label(__('site-builder/block.link_url'))
                ->required($config['required'])
                ->placeholder(__('site-builder/block.link_url_placeholder'))
                ->default($config['defaultValue']['url'] ?? null),
        ])->columns(2)->label($config['label']);

    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public
    static function SingleImageHelper($field): WebpUploadFixedSizeBuilder {
        $config = self::LoadFormConfig($field);

        $formField = WebpUploadFixedSizeBuilder::make("data.{$config['name']}")
            ->label($config['label'])
            ->setThumbnail($config['with_thumbnail'])
            ->required($config['required'])
            ->helperText($config['help']);

        // إضافة إعدادات إضافية إذا كانت متوفرة
        if ($config['img_width'] && $config['img_height']) {
            $formField->setResize($config['img_width'], $config['img_height'], 90);
            $formField->imageCropAspectRatio(calcRatio($config['img_width'], $config['img_height']));
        }

        if ($config['with_thumbnail'] && $config['thumb_width'] && $config['thumb_height']) {
            $formField->setThumbnailSize($config['thumb_width'], $config['thumb_height']);
        }
        return $formField;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public
    static function RepeaterImageHelper($field): WebpUploadFixedSizeBuilder {
        $config = self::LoadFormConfig($field);

        $formField = WebpUploadFixedSizeBuilder::make("{$config['name']}")
            ->label($config['label'])
            ->required($config['required'])
            ->helperText($config['help']);

        // إضافة إعدادات إضافية إذا كانت متوفرة
        if ($config['img_width'] && $config['img_height']) {
            $formField->setResize((int)$config['img_width'], (int)$config['img_height'], 90);
            $formField->imageCropAspectRatio(calcRatio((int)$config['img_width'], (int)$config['img_height']));
        }

        if ($config['with_thumbnail']) {
            $formField->setThumbnail($config['with_thumbnail']);
            if ($config['thumb_width'] && $config['thumb_height']) {
                $formField->setThumbnailSize((int)$config['thumb_width'], (int)$config['thumb_height']);
            }
        }

        return $formField;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    /**
     * Convert width string to Filament columnSpan value
     *
     * @param string $width Width string ('1/2', '1/3', '2/3', '1/4', '3/4', 'full', etc.)
     * @return int Column span value for Filament 3 (1-12)
     */
    public
    static function convertWidthToColumnSpan(string $width): int {
        // Map width values to column spans for a 12-column grid
        $result = match (strtolower(trim($width))) {
            '1/1', 'full', '100%' => 12,  // Full width (12 of 12 columns)
            '1/2', '50%' => 6,            // Half width (6 of 12 columns)
            '1/3', '33%', '33.33%' => 4,  // One third (4 of 12 columns)
            '2/3', '66%', '66.66%' => 8,  // Two thirds (8 of 12 columns)
            '1/4', '25%' => 3,            // One quarter (3 of 12 columns)
            '3/4', '75%' => 9,            // Three quarters (9 of 12 columns)
            '1/6', '16%', '16.66%' => 2,  // One sixth (2 of 12 columns)
            '5/6', '83%', '83.33%' => 10, // Five sixths (10 of 12 columns)
            default => 12,                // Default to full width
        };
        return $result;
    }
}
