<?php

namespace App\Enums\SiteBuilder;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum BlockTypeField: string {
    use EnumHasLabelOptionsTrait;

    case TEXT = 'text';
    case TEXTAREA = 'textarea';
    case RICH_TEXT = 'rich_text';
    case SELECT = 'select';
    case CHECKBOX = 'checkbox';
    case RADIO = 'radio';
    case IMAGE = 'image';
    case FILE = 'file';
    case DATE = 'date';
    case TIME = 'time';
    case COLOR = 'color';
    case ICON = 'icon';
    case LINK = 'link';
    case NUMBER = 'number';
    case REPEATER = 'repeater';

    public function label(): string {
        return match ($this) {
            self::TEXT => __('site-builder/block-type.field_types.text'),
            self::TEXTAREA => __('site-builder/block-type.field_types.textarea'),
            self::RICH_TEXT => __('site-builder/block-type.field_types.rich_text'),
            self::SELECT => __('site-builder/block-type.field_types.select'),
            self::CHECKBOX => __('site-builder/block-type.field_types.checkbox'),
            self::RADIO => __('site-builder/block-type.field_types.radio'),
            self::IMAGE => __('site-builder/block-type.field_types.image'),
            self::FILE => __('site-builder/block-type.field_types.file'),
            self::DATE => __('site-builder/block-type.field_types.date'),
            self::TIME => __('site-builder/block-type.field_types.time'),
            self::COLOR => __('site-builder/block-type.field_types.color'),
            self::ICON => __('site-builder/block-type.field_types.icon'),
            self::LINK => __('site-builder/block-type.field_types.link'),
            self::NUMBER => __('site-builder/block-type.field_types.number'),
            self::REPEATER => __('site-builder/block-type.field_types.repeater'),
        };
    }
}
