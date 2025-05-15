<?php

namespace App\Services;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;

class BuilderBlockFormService {
    public static function generateFormFields(array $schema): array {
        $fields = [];

        foreach ($schema['fields'] as $field) {
            $label = $field['label'][app()->getLocale()] ?? $field['label']['ar'] ?? $field['name'];
            $key = "data.{$field['name']}";

            switch ($field['type']) {
                case 'text':
                    $fields[] = TextInput::make($key)->label($label);
                    break;

                case 'textarea':
                    $fields[] = Textarea::make($key)->label($label);
                    break;

                case 'image':
                    $fields[] = FileUpload::make($key)
                        ->label($label)
                        ->image()
                        ->directory('uploads');
                    break;

                case 'url':
                    $fields[] = TextInput::make($key)->label($label)->url();
                    break;

                case 'number':
                    $fields[] = TextInput::make($key)->label($label)->numeric();
                    break;

                case 'repeater':
                    $subFields = self::generateFormFields([
                        'fields' => $field['fields']
                    ]);
                    $fields[] = Repeater::make($key)
                        ->label($label)
                        ->schema($subFields);
                    break;

                default:
                    // fallback to text
                    $fields[] = TextInput::make($key)->label($label);
                    break;
            }
        }

        return $fields;
    }
}
