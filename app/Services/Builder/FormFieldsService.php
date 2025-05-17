<?php

namespace App\Services\Builder;

use Filament\Forms;
use App\Enums\SiteBuilder\FieldWidth;

class FormFieldsService
{
    /**
     * Create form fields based on block type schema
     */
    public static function createFormFieldsFromSchema(array $schema): array
    {
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

            // Debug schema field values
            \Illuminate\Support\Facades\Log::info('Field schema: ' . json_encode([
                'name' => $name,
                'placeholder' => $placeholder,
                'help' => $help,
                'default' => $defaultValue,
            ]));

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

                case 'checkbox':
                    $formField = Forms\Components\Toggle::make("data.{$name}")
                        ->label($label)
                        ->required($required)
                        ->helperText($help)
                        ->default($defaultValue ?: false);
                    break;

                case 'radio':
                    $options = $field['options'] ?? [];
                    $formField = Forms\Components\Radio::make("data.{$name}")
                        ->label($label)
                        ->options($options)
                        ->required($required)
                        ->helperText($help)
                        ->default($defaultValue);
                    break;

                case 'image':
                    $formField = Forms\Components\FileUpload::make("data.{$name}")
                        ->label($label)
                        ->image()
                        ->directory('images')
                        ->required($required)
                        ->helperText($help);
                    break;

                case 'file':
                    $formField = Forms\Components\FileUpload::make("data.{$name}")
                        ->label($label)
                        ->directory('files')
                        ->required($required)
                        ->helperText($help);
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
                    $formField = Forms\Components\TextInput::make("data.{$name}")
                        ->label($label)
                        ->required($required)
                        ->placeholder($placeholder)
                        ->helperText($help ?: __('site-builder/block.icon_help'))
                        ->default($defaultValue);
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
                    ])
                    ->label($label);
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
                    if (isset($field['fields']) && is_array($field['fields'])) {
                        foreach ($field['fields'] as $subField) {
                            $subName = $subField['name'] ?? '';
                            $subLabel = $subField['label'] ?? $subName;
                            $subType = $subField['type'] ?? 'text';

                            // Basic subfields for now, can be expanded
                            if ($subType === 'text') {
                                $subSchema[] = Forms\Components\TextInput::make($subName)
                                    ->label($subLabel)
                                    ->required($subField['required'] ?? false);
                            } elseif ($subType === 'textarea') {
                                $subSchema[] = Forms\Components\Textarea::make($subName)
                                    ->label($subLabel)
                                    ->required($subField['required'] ?? false);
                            } elseif ($subType === 'image') {
                                $subSchema[] = Forms\Components\FileUpload::make($subName)
                                    ->label($subLabel)
                                    ->image()
                                    ->required($subField['required'] ?? false);
                            } elseif ($subType === 'icon') {
                                $subSchema[] = Forms\Components\TextInput::make($subName)
                                    ->label($subLabel)
                                    ->required($subField['required'] ?? false);
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
                        ->required($required)
                        ->helperText($help)
                        ->collapsible()
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

            // Set width and add to form fields
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
     * @return string|int Column span value for Filament 3
     */
    public static function convertWidthToColumnSpan(string $width): string|int
    {
        // Use the FieldWidth enum to convert the width to column span
        return FieldWidth::stringToColumnSpan($width);
    }

    /**
     * Create translation fields based on block type schema
     *
     * @param array $schema Schema array of fields
     * @param string $locale Current locale code
     * @return array Array of form fields for translations
     */
    public static function createTranslationFieldsFromSchema(array $schema, string $locale): array
    {
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
            
            // Convert width to Filament column span value
            $fieldWidth = self::convertWidthToColumnSpan($width);
            
            // Create field based on type
            $translationField = null;
            
            // Handle different field types for translation
            if ($type === 'textarea' || $type === 'rich_text') {
                $translationField = Forms\Components\Textarea::make("translations.{$locale}.{$name}")
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
                ])->label($label);
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
            
            // Apply the width to the translation field
            if ($translationField) {
                $translationField->columnSpan($fieldWidth);
                $fields[] = $translationField;
            }
        }
        
        return $fields;
    }
}
