<?php

namespace App\Services\Builder;

use App\FilamentCustom\UploadFile\WebpUploadFixedSizeBuilder;
use Filament\Forms;
use Guava\FilamentIconPicker\Forms\IconPicker;

class FormFieldsService {

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function createFormFieldsFromSchema(array $schema): array {
        $formFields = [];

        foreach ($schema as $field) {
            $formField = null;
            $name = $field['name'] ?? '';
            $label = $field['label'] ?? $name;
            $type = $field['type'] ?? 'text';
            $required = $field['required'] ?? false;
            $placeholder = $field['placeholder'] ?? null;
            $help = $field['help'] ?? null;
            $defaultValue = $field['default'] ?? null;
            $width = $field['width'] ?? 'full';
            $with_thumbnail = $field['config']['with_thumbnail'] ?? false;
            $img_width = $field['config']['width'] ?? 100;
            $img_height = $field['config']['height'] ?? 100;
            $thumb_width = $field['config']['thumb_width'] ?? 100;
            $thumb_height = $field['config']['thumb_height'] ?? 100;

            // Convert width to Filament column span value
            $fieldWidth = self::convertWidthToColumnSpan($width);

            // Create field based on type
            switch ($type) {
                case 'text':
                    $formField = Forms\Components\TextInput::make("data.{$name}")
                        ->label($label)
                        ->required($required)
                        ->placeholder($placeholder)
                        ->helperText($help)
                        ->default($defaultValue);
                    break;

                case 'textarea':
                    $formField = Forms\Components\Textarea::make("data.{$name}")
                        ->label($label)
                        ->required($required)
                        ->placeholder($placeholder)
                        ->helperText($help)
                        ->rows(6)
                        ->default($defaultValue);
                    break;

                case 'rich_text':
                    $formField = Forms\Components\RichEditor::make("data.{$name}")
                        ->label($label)
                        ->required($required)
                        ->placeholder($placeholder)
                        ->helperText($help)
                        ->default($defaultValue);
                    break;

                case 'select':
                    $options = $field['options'] ?? [];
                    $formField = Forms\Components\Select::make("data.{$name}")
                        ->label($label)
                        ->options($options)
                        ->required($required)
                        ->placeholder($placeholder)
                        ->searchable()
                        ->preload()
                        ->helperText($help)
                        ->default($defaultValue);
                    break;

                case 'radio':
                    $formField = Forms\Components\Toggle::make("data.{$name}")
                        ->label($label)
                        ->default($defaultValue)
                        ->inline(false)
                        ->helperText($help)
                        ->default($defaultValue);
                    break;

                case 'image':
                    // إنشاء حقل الصورة
                    $formField = WebpUploadFixedSizeBuilder::make("data.{$name}")
                        ->label($label)
                        ->setThumbnail($with_thumbnail)
                        ->helperText($help);

                    // إضافة إعدادات إضافية إذا كانت متوفرة
                    if ($img_width && $img_height) {
                        $formField->setResize($img_width, $img_height, 90);
                        $formField->imageCropAspectRatio(calcRatio($img_width,$img_height));
                    }

                    if ($with_thumbnail && $thumb_width && $thumb_height) {
                        $formField->setThumbnailSize($thumb_width, $thumb_height);
                    }

                    // إضافة حقل مخفي للصورة المصغرة
                    if ($with_thumbnail) {
                        $thumbnailFieldName = $name . '_thumbnail';
                        $formFields[] = Forms\Components\Hidden::make("data.{$thumbnailFieldName}");
                    }
                    break;


                case 'date':
                    $formField = Forms\Components\DatePicker::make("data.{$name}")
                        ->label($label)
                        ->required($required)
                        ->placeholder($placeholder)
                        ->helperText($help)
                        ->default($defaultValue);
                    break;

                case 'time':
                    $formField = Forms\Components\TimePicker::make("data.{$name}")
                        ->label($label)
                        ->required($required)
                        ->placeholder($placeholder)
                        ->helperText($help)
                        ->default($defaultValue);
                    break;

                case 'color':
                    $formField = Forms\Components\ColorPicker::make("data.{$name}")
                        ->label($label)
                        ->required($required)
                        ->helperText($help)
                        ->default($defaultValue);
                    break;

                case 'icon':
                    $formField = IconPicker::make("data.{$name}")
                        ->label($label)
                        ->required($required)
                        ->searchLabels()
                        ->preload()
                        ->helperText($help ?: __('site-builder/block.icon_help'))
                        ->columns([
                            'default' => 2,
                            'lg' => 6,
                            '2xl' => 8,
                        ])
                        ->sets(['fas', 'fab', "fontawesome-solid", "fontawesome-brands"]);
                    break;

                case 'link':
                    $formField = Forms\Components\Group::make([
                        Forms\Components\TextInput::make("data.{$name}.text")
                            ->label(__('site-builder/block.link_text'))
                            ->required($required)
                            ->placeholder(__('site-builder/block.link_text_placeholder'))
                            ->default($defaultValue['text'] ?? null),

                        Forms\Components\TextInput::make("data.{$name}.url")
                            ->label(__('site-builder/block.link_url'))
                            ->required($required)
                            ->placeholder(__('site-builder/block.link_url_placeholder'))
                            ->default($defaultValue['url'] ?? null),
                    ])->columns(2)->label($label);
                    break;

                case 'number':
                    $formField = Forms\Components\TextInput::make("data.{$name}")
                        ->label($label)
                        ->numeric()
                        ->required($required)
                        ->placeholder($placeholder)
                        ->helperText($help)
                        ->default($defaultValue);
                    break;

                case 'repeater':
                    $subSchema = [];

                    // If we have nested fields, create them
                    if (isset($field['config']['fields']) && is_array($field['config']['fields'])) {
                        foreach ($field['config']['fields'] as $subField) {
                            $subName = $subField['name'] ?? '';
                            $subLabel = $subField['label'] ?? $subName;
                            $subType = $subField['type'] ?? 'text';
                            $subRequired = $subField['required'] ?? false;
                            $subPlaceholder = $subField['placeholder'] ?? null;
                            $subHelp = $subField['help'] ?? null;
                            $subDefault = $subField['default'] ?? null;
                            $subWidth = $subField['width'] ?? 'full';
                            $subFieldWidth = self::convertWidthToColumnSpan($subWidth);
                            
                            // Config for image fields
                            $subConfig = $subField['config'] ?? [];
                            $with_thumbnail = $subConfig['with_thumbnail'] ?? false;
                            $img_width = $subConfig['width'] ?? null;
                            $img_height = $subConfig['height'] ?? null;
                            $thumb_width = $subConfig['thumb_width'] ?? null;
                            $thumb_height = $subConfig['thumb_height'] ?? null;

                            // Create field based on type (similar to the main switch)
                            switch ($subType) {
                                case 'text':
                                    $subFormField = Forms\Components\TextInput::make($subName)
                                        ->label($subLabel)
                                        ->required($subRequired)
                                        ->placeholder($subPlaceholder)
                                        ->helperText($subHelp)
                                        ->default($subDefault);
                                    break;
                                
                                case 'textarea':
                                    $subFormField = Forms\Components\Textarea::make($subName)
                                        ->label($subLabel)
                                        ->required($subRequired)
                                        ->placeholder($subPlaceholder)
                                        ->helperText($subHelp)
                                        ->rows(6)
                                        ->default($subDefault);
                                    break;

                                case 'rich_text':
                                    $subFormField = Forms\Components\RichEditor::make($subName)
                                        ->label($subLabel)
                                        ->required($subRequired)
                                        ->placeholder($subPlaceholder)
                                        ->helperText($subHelp)
                                        ->default($subDefault);
                                    break;

                                case 'select':
                                    $options = $subField['options'] ?? [];
                                    $subFormField = Forms\Components\Select::make($subName)
                                        ->label($subLabel)
                                        ->options($options)
                                        ->required($subRequired)
                                        ->placeholder($subPlaceholder)
                                        ->searchable()
                                        ->preload()
                                        ->helperText($subHelp)
                                        ->default($subDefault);
                                    break;

                                case 'radio':
                                    $subFormField = Forms\Components\Toggle::make($subName)
                                        ->label($subLabel)
                                        ->default($subDefault)
                                        ->inline(false)
                                        ->helperText($subHelp);
                                    break;

                                case 'image':
                                    // إنشاء حقل الصورة باستخدام نفس WebpUploadFixedSizeBuilder
                                    $subFormField = WebpUploadFixedSizeBuilder::make($subName)
                                        ->label($subLabel)
                                        ->setRequiredUpload($subRequired)
                                        ->helperText($subHelp);

                                    // إضافة إعدادات إضافية إذا كانت متوفرة
                                    if ($img_width && $img_height) {
                                        $subFormField->setResize((int)$img_width, (int)$img_height, 90);
                                        $subFormField->imageCropAspectRatio(calcRatio((int)$img_width, (int)$img_height));
                                    }

                                    if ($with_thumbnail) {
                                        $subFormField->setThumbnail($with_thumbnail);
                                        if ($thumb_width && $thumb_height) {
                                            $subFormField->setThumbnailSize((int)$thumb_width, (int)$thumb_height);
                                        }
                                        
                                        // إضافة حقل مخفي للصورة المصغرة - مهم وضع المسار الكامل
                                        $thumbnailFieldName = $subName . '_thumbnail'; // تحديد لاحقة محددة للصورة المصغرة
                                        $subSchema[] = Forms\Components\Hidden::make($thumbnailFieldName)
                                                      ->reactive()
                                                      ->dehydrated(true)
                                                      ->afterStateHydrated(function ($state) use ($thumbnailFieldName) {
                                                          // للتأكد من أن الحقل جاهز للاستقبال
                                                          file_put_contents(storage_path('logs/thumbnail_debug.log'), "\n[" . date('Y-m-d H:i:s') . "] Hidden field hydrated: {$thumbnailFieldName} = {$state}", FILE_APPEND);
                                                      });
                                    }
                                    break;

                                case 'date':
                                    $subFormField = Forms\Components\DatePicker::make($subName)
                                        ->label($subLabel)
                                        ->required($subRequired)
                                        ->placeholder($subPlaceholder)
                                        ->helperText($subHelp)
                                        ->default($subDefault);
                                    break;

                                case 'time':
                                    $subFormField = Forms\Components\TimePicker::make($subName)
                                        ->label($subLabel)
                                        ->required($subRequired)
                                        ->placeholder($subPlaceholder)
                                        ->helperText($subHelp)
                                        ->default($subDefault);
                                    break;

                                case 'color':
                                    $subFormField = Forms\Components\ColorPicker::make($subName)
                                        ->label($subLabel)
                                        ->required($subRequired)
                                        ->helperText($subHelp)
                                        ->default($subDefault);
                                    break;

                                case 'icon':
                                    $subFormField = IconPicker::make($subName)
                                        ->label($subLabel)
                                        ->required($subRequired)
                                        ->searchLabels()
                                        ->preload()
                                        ->helperText($subHelp ?: __('site-builder/block.icon_help'))
                                        ->columns([
                                            'default' => 2,
                                            'lg' => 3,
                                            '2xl' => 4,
                                        ])
                                        ->sets(['fas', 'fab', "fontawesome-solid", "fontawesome-brands"]);
                                    break;

                                case 'link':
                                    $subFormField = Forms\Components\Group::make([
                                        Forms\Components\TextInput::make("{$subName}.text")
                                            ->label(__('site-builder/block.link_text'))
                                            ->required($subRequired)
                                            ->placeholder(__('site-builder/block.link_text_placeholder'))
                                            ->default($subDefault['text'] ?? null),

                                        Forms\Components\TextInput::make("{$subName}.url")
                                            ->label(__('site-builder/block.link_url'))
                                            ->required($subRequired)
                                            ->placeholder(__('site-builder/block.link_url_placeholder'))
                                            ->default($subDefault['url'] ?? null),
                                    ])->columns(2)->label($subLabel);
                                    break;

                                case 'number':
                                    $subFormField = Forms\Components\TextInput::make($subName)
                                        ->label($subLabel)
                                        ->numeric()
                                        ->required($subRequired)
                                        ->placeholder($subPlaceholder)
                                        ->helperText($subHelp)
                                        ->default($subDefault);
                                    break;

                                default:
                                    // Default to text input
                                    $subFormField = Forms\Components\TextInput::make($subName)
                                        ->label($subLabel)
                                        ->required($subRequired)
                                        ->placeholder($subPlaceholder)
                                        ->helperText($subHelp)
                                        ->default($subDefault);
                                    break;
                            }

                            // Set column span based on field width
                            if ($subFormField) {
                                $subFormField->columnSpan($subFieldWidth);
                                $subSchema[] = $subFormField;
                            }
                        }
                    } else {
                        // Default fields for a repeater
                        $subSchema = [
                            Forms\Components\TextInput::make('title')
                                ->label(__('site-builder/general.title'))
                                ->required(),
                            Forms\Components\Textarea::make('description')
                                ->label(__('site-builder/general.description')),
                        ];
                    }

                    $formField = Forms\Components\Repeater::make("data.{$name}")
                        ->label($label)
                        ->schema($subSchema)
                        ->columns(12) // Use a 12-column grid for nested fields
                        ->required($required)
                        ->helperText($help)
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['h1'] ?? $state['title'] ?? null)
                        ->defaultItems(1);
                    break;

                default:
                    // Default to text input
                    $formField = Forms\Components\TextInput::make("data.{$name}")
                        ->label($label)
                        ->required($required)
                        ->placeholder($placeholder)
                        ->helperText($help)
                        ->default($defaultValue);
                    break;
            }

            // Set column span based on field width
            if ($formField) {
                $formField->columnSpan($fieldWidth);
                $formFields[] = $formField;
            }
        }
        return $formFields;
    }



    /**
     * Convert width string to Filament columnSpan value
     *
     * @param string $width Width string ('1/2', '1/3', '2/3', '1/4', '3/4', 'full', etc.)
     * @return int Column span value for Filament 3 (1-12)
     */
    public static function convertWidthToColumnSpan(string $width): int {
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

    /**
     * Create translation fields based on block type schema
     *
     * @param array $schema Schema array of fields
     * @param string $locale Current locale code
     * @return array Array of form fields for translations
     */
    public static function createTranslationFieldsFromSchema(array $schema, string $locale): array {
        $fields = [];

        foreach ($schema as $field) {
            $name = $field['name'] ?? '';
            $label = $field['label'] ?? $name;
            $translatable = $field['translatable'] ?? true;
            $type = $field['type'] ?? 'text';
            $required = $field['required'] ?? false;
            $width = $field['width'] ?? 'full';

            // Skip non-translatable fields
            if (!$translatable) {
                continue;
            }

            // Create field based on type
            $translationField = null;

            // Handle different field types for translation
            if ($type === 'textarea') {
                $translationField = Forms\Components\Textarea::make("translations.{$locale}.{$name}")
                    ->label($label)
                    ->placeholder($field['placeholder'] ?? null)
                    ->helperText($field['help'] ?? null)
                    ->rows(6)
                    ->required($required); // Make required if the original field is required
            } elseif ($type === 'rich_text') {
                $translationField = Forms\Components\RichEditor::make("translations.{$locale}.{$name}")
                    ->label($label)
                    ->placeholder($field['placeholder'] ?? null)
                    ->helperText($field['help'] ?? null)
                    ->required($required); // Make required if the original field is required

            } elseif ($type === 'link') {
                // For link type, we need to handle the text part
                $translationField = Forms\Components\Group::make([
                    Forms\Components\TextInput::make("translations.{$locale}.{$name}.text")
                        ->label(__('site-builder/block.link_text'))
                        ->placeholder(__('site-builder/block.link_text_placeholder'))
                        ->helperText($field['help'] ?? null)
                        ->required($required),

                    Forms\Components\TextInput::make("translations.{$locale}.{$name}.url")
                        ->label(__('site-builder/block.link_url'))
                        ->placeholder(__('site-builder/block.link_url_placeholder'))
                        ->required($required),
                ])->columns(2)->label($label);
            } elseif ($type === 'repeater') {
                // Create a repeater translator using KeyValue for simplicity
                $translationField = Forms\Components\KeyValue::make("translations.{$locale}.{$name}")
                    ->label($label)
                    ->keyLabel(__('site-builder/block.repeater_item_field'))
                    ->valueLabel(__('site-builder/block.translation'))
                    ->helperText(__('site-builder/block.repeater_translation_help'))
                    ->required($required)
                    ->afterStateHydrated(function (Forms\Components\KeyValue $component, $state) {
                        // Convert complex values to JSON strings
                        if (is_array($state)) {
                            foreach ($state as $key => $value) {
                                if (is_array($value) || is_object($value)) {
                                    $state[$key] = json_encode($value, JSON_UNESCAPED_UNICODE);
                                }
                            }
                            $component->state($state);
                        }
                    })
                    ->dehydrateStateUsing(function ($state) {
                        // Try to convert JSON strings back to arrays/objects before saving
                        foreach ($state as $key => $value) {
                            if (is_string($value) && (str_starts_with(trim($value), '[') || str_starts_with(trim($value), '{'))) {
                                try {
                                    $decoded = json_decode($value, true);
                                    if (json_last_error() === JSON_ERROR_NONE) {
                                        $state[$key] = $decoded;
                                    }
                                } catch (\Exception $e) {
                                    // Keep the value as is if conversion fails
                                }
                            }
                        }
                        return $state;
                    });
            } else {
                $translationField = Forms\Components\TextInput::make("translations.{$locale}.{$name}")
                    ->label($label)
                    ->placeholder($field['placeholder'] ?? null)
                    ->helperText($field['help'] ?? null)
                    ->required($required);
            }

            // Apply the column span to the translation field
            if ($translationField) {
                $fieldWidth = self::convertWidthToColumnSpan($width);
                $translationField->columnSpan($fieldWidth);
                $fields[] = $translationField;
            }
        }

        return $fields;
    }
}
