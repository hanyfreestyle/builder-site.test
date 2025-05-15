<?php

use Filament\Forms\Components\Section;

return [
    'NavigationLabel' => 'Upload Filters',
    'ModelLabel' => 'Filter',
    'PluralModelLabel' => 'Upload Filters',

    'section' => [
        'basic_settings' => 'Basic Settings',
        'additional_settings' => 'Additional Settings',
        'watermark_settings' => 'Watermark Settings',
        'image_text' => 'Add Text to Image',
        'upload_notes' => 'Add Notes for Image Upload',
    ],

    'filter_type' => [
        'filter_action_1' => 'Do not modify while uploading',
        'filter_action_2' => 'Resize according to width',
        'filter_action_3' => 'Resize according to height',
        'filter_action_4' => 'Crop according to height and width',
        'filter_action_5' => 'Adjust the dimensions with background',
        'btn' => 'Filter Sizes',
    ],

    'crop_aspect_ratio' => [
        'ratio_1' => "1:1 | Square",
        'ratio_2' => "16:9 | Panorama Filter",
        'ratio_3' => "9:16 | Stories Filter",
        'ratio_4' => "4:5 | Portrait Ratio",
        'ratio_5' => "5:7 | Custom Ratio",
        'btn' => 'Aspect Ratio',
    ],

    'err' => [
        'missing_filter_mass' => "The filter was not selected correctly.",
        'missing_filter_btn' => "Update Now",
        'missing_filter_btn_change' => "Change Now",
        'filter_name' => "Filter",
        'filter_or' => "Or",
    ],

    'columns' => [
        'name' => 'Name',
        'type' => 'Filter Type',
        'sizes' => 'Filter Sizes',
        'convert_state' => 'WEBP',
        'quality_val' => 'Quality Value',
        'width' => 'Photo Width',
        'height' => 'Photo Height',
        'canvas_back' => 'Canvas Background',
        'greyscale' => 'Greyscale',
        'flip_state' => 'Flip Horizontally',
        'flip_v' => 'Flip Vertically',
        'blur' => 'Blur',
        'blur_size' => 'Blur Size',
        'pixelate' => 'Pixelate',
        'pixelate_size' => 'Pixelate Size',
        'text_state' => 'Text State',
        'text_print' => 'Text Print',
        'font_size' => 'Font Size',
        'font_path' => 'Font Path',
        'font_color' => 'Font Color',
        'font_opacity' => 'Font Opacity',
        'text_position' => 'Text Position',
        'watermark_state' => 'Watermark State',
        'watermark_img' => 'Watermark Image',
        'watermark_position' => 'Watermark Position',
        'state' => 'State',
        'notes' => 'Notes',
        'is_notes' => 'View Notes',
        'filter_id' => 'Filter',
        'get_more_option' => 'Get More Option',
        'get_add_text' => 'Get Add Text',
        'get_watermark' => 'Get Watermark',
        'crop_aspect_ratio' => 'Aspect Ratio',
    ]

];
