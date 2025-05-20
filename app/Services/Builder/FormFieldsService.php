<?php

namespace App\Services\Builder;

use App\Services\Builder\Form\FormInputHelper;
use Filament\Forms;

class FormFieldsService extends FormInputHelper {

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function createFormFieldsFromSchema(array $schema): array {
        $formFields = [];

        foreach ($schema as $field) {
            $formField = null;
            $type = $field['type'] ?? 'text';
            $width = $field['width'] ?? 'full';
            $name = $field['name'] ?? '';
            $label = $field['label'] ?? $name;

            // Convert width to Filament column span value
            $fieldWidth = self::convertWidthToColumnSpan($width);

            if ($type == 'image') {
                $formField = self::SingleImageHelper($field);
                if ($field['config']['with_thumbnail'] ?? false) {
                    $thumbnailFieldName = $name . '_thumbnail';
                    $formFields[] = Forms\Components\Hidden::make("data.{$thumbnailFieldName}");
                }

            } elseif ($type == 'repeater') {
                $subSchema = [];
                // If we have nested fields, create them
                if (isset($field['config']['fields']) && is_array($field['config']['fields'])) {
                    foreach ($field['config']['fields'] as $subField) {
                        $subType = $subField['type'] ?? 'text';
                        $subWidth = $subField['width'] ?? 'full';
                        $subFieldWidth = self::convertWidthToColumnSpan($subWidth);
                        $subName = $subField['name'] ?? '';
                        if ($subType == 'image') {
                            $subFormField = self::RepeaterImageHelper($subField);
                            if ($field['config']['with_thumbnail'] ?? false) {
                                // عند إضافة الحقل المخفي داخل المكرر
                                $thumbnailFieldName = $subName . '_thumbnail';
                                $subSchema[] = Forms\Components\Hidden::make($thumbnailFieldName)
                                    ->reactive()
                                    ->dehydrated(true)
                                    ->statePath("{$subName}_thumbnail"); // تحديد المسار بدون UUID (سيتم إدارته تلقائيًا)
                            }
                        } else {
                            $subFormField = self::buildFieldSwitch($type, $subField);
                        }
                        if ($subFormField) {
                            $subFormField->columnSpan($subFieldWidth);
                            $subSchema[] = $subFormField;
                        }
                    }
                } else {
                    $subSchema = self::DefaultRepeaterFields();
                }

                $formField = Forms\Components\Repeater::make("data.{$name}")
                    ->label($label)
                    ->schema($subSchema)
                    ->columns(12) // Use a 12-column grid for nested fields
                    ->collapsible()
                    ->itemLabel(fn(array $state): ?string => $state['h1'] ?? $state['title'] ?? null)
                    ->defaultItems(1);
            } else {
                $formField = self::buildFieldSwitch($type, $field);
            }

            // Set column span based on field width
            if ($formField) {
                $formField->columnSpan($fieldWidth);
                $formFields[] = $formField;
            }
        }
        return $formFields;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||

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
