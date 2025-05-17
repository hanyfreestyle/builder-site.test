<?php

namespace App\Filament\Admin\Resources\BuilderPageResource\RelationManagers;

use App\Models\Builder\BlockType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BlocksRelationManager extends RelationManager
{
    protected static string $relationship = 'blocks';

    /**
     * After creating a block, also create a relation in the pivot table
     */
    protected function afterCreate(): void
    {
        $page = $this->getOwnerRecord();
        $block = $this->record;
        
        // Check if we need to create a relation in the pivot table
        if (method_exists($block, 'pages')) {
            $block->pages()->attach($page->id, ['sort_order' => $block->sort_order]);
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('block_type_id')
                    ->label(__('site-builder/page.blocks.block_type'))
                    ->options(function () {
                        // Get the page's template
                        $page = $this->getOwnerRecord();
                        $templateId = $page->template_id;

                        // Get all block types enabled for this template
                        return BlockType::whereHas('templates', function ($query) use ($templateId) {
                            $query->where('template_id', $templateId)
                                  ->where('is_enabled', true);
                        })->pluck('name', 'id');
                    })
                    ->reactive()
                    ->required()
                    ->searchable()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        // Clear previous data when block type changes
                        $set('data', null);
                        $set('view_version', 'default');
                    }),

                Forms\Components\Select::make('view_version')
                    ->label(__('site-builder/page.blocks.view_version'))
                    ->options(function (Forms\Get $get) {
                        // Get the page's template
                        $page = $this->getOwnerRecord();
                        $template = $page->template;

                        // Get the selected block type
                        $blockTypeId = $get('block_type_id');
                        if (!$blockTypeId || !$template) {
                            return ['default' => 'Default'];
                        }

                        // Get the relation between the template and block type
                        $relation = $template->blockTypes()->where('block_type_id', $blockTypeId)->first();
                        if (!$relation) {
                            return ['default' => 'Default'];
                        }

                        // Get available view versions
                        $versions = json_decode($relation->pivot->view_versions, true) ?: ['default'];
                        return array_combine($versions, $versions);
                    })
                    ->default('default')
                    ->required(),

                // Dynamic Block Fields based on Schema for Default Language
                Forms\Components\Section::make(__('site-builder/page.blocks.default_language_content'))
                    ->description(__('site-builder/page.blocks.default_language_description'))
                    ->schema(function (Forms\Get $get) {
                        $blockTypeId = $get('block_type_id');

                        if (!$blockTypeId) {
                            return [];
                        }

                        $blockType = BlockType::find($blockTypeId);

                        if (!$blockType) {
                            return [];
                        }

                        $schema = $blockType->schema ?: [];

                        // Create form fields based on schema
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

                            // Convert width to Filament width
                            $fieldWidth = match($width) {
                                '1/2' => 'md:col-span-1',
                                '1/3' => 'md:col-span-1',
                                '2/3' => 'md:col-span-2',
                                default => 'col-span-2',
                            };

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
                                        ->helperText($help ?: __('site-builder/page.blocks.icon_help'))
                                        ->default($defaultValue);
                                    break;

                                case 'link':
                                    $formField = Forms\Components\Group::make([
                                        Forms\Components\TextInput::make("data.{$name}.text")
                                            ->label(__('site-builder/page.blocks.link_text'))
                                            ->required($required)
                                            ->placeholder(__('site-builder/page.blocks.link_text_placeholder'))
                                            ->default($defaultValue['text'] ?? null),

                                        Forms\Components\TextInput::make("data.{$name}.url")
                                            ->label(__('site-builder/page.blocks.link_url'))
                                            ->required($required)
                                            ->placeholder(__('site-builder/page.blocks.link_url_placeholder'))
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
                    })
                    ->columns(2),

                // Dynamic translations based on supported languages
                Forms\Components\Section::make(function () {
                    return __('site-builder/page.blocks.translations_heading');
                })
                ->description(function () {
                    // Get the page's template
                    $page = $this->getOwnerRecord();
                    $template = $page->template;
                    
                    if (!$template || !$template->supported_languages || count($template->supported_languages) <= 1) {
                        return __('site-builder/page.blocks.no_translations_needed');
                    }
                    
                    return __('site-builder/page.blocks.translations_description');
                })
                ->schema(function (Forms\Get $get) {
                    // Get the page's template and block type
                    $page = $this->getOwnerRecord();
                    $template = $page->template;
                    $blockTypeId = $get('block_type_id');
                    
                    if (!$template || !$blockTypeId) {
                        return [];
                    }
                    
                    // Get supported languages
                    $supportedLanguages = $template->supported_languages ?? ['en'];
                    
                    // Remove default language (first language is considered default)
                    $defaultLanguage = $supportedLanguages[0] ?? 'en';
                    $translationLanguages = array_filter($supportedLanguages, function ($lang) use ($defaultLanguage) {
                        return $lang !== $defaultLanguage;
                    });
                    
                    if (empty($translationLanguages)) {
                        return [];
                    }
                    
                    $blockType = BlockType::find($blockTypeId);
                    if (!$blockType) {
                        return [];
                    }
                    
                    $schema = $blockType->schema ?: [];
                    
                    // Create a section for each language
                    $languageSections = [];
                    
                    foreach ($translationLanguages as $locale) {
                        $fields = [];
                        
                        foreach ($schema as $field) {
                            $name = $field['name'] ?? '';
                            $label = $field['label'] ?? $name;
                            $translatable = $field['translatable'] ?? true;
                            $type = $field['type'] ?? 'text';
                            $required = $field['required'] ?? false;
                            
                            // Skip non-translatable fields
                            if (!$translatable) {
                                continue;
                            }
                            
                            // Handle different field types for translation
                            if ($type === 'textarea' || $type === 'rich_text') {
                                $fields[] = Forms\Components\Textarea::make("translations.{$locale}.{$name}")
                                    ->label($label)
                                    ->required($required);
                            } elseif ($type === 'link') {
                                // For link type, we need to handle the text part
                                $fields[] = Forms\Components\TextInput::make("translations.{$locale}.{$name}.text")
                                    ->label("{$label} (" . __('site-builder/page.blocks.link_text') . ")")
                                    ->required($required);
                                    
                                $fields[] = Forms\Components\TextInput::make("translations.{$locale}.{$name}.url")
                                    ->label("{$label} (" . __('site-builder/page.blocks.link_url') . ")")
                                    ->required($required);
                            } elseif ($type === 'repeater') {
                                // Create a repeater translator using KeyValue for simplicity
                                $fields[] = Forms\Components\KeyValue::make("translations.{$locale}.{$name}")
                                    ->label($label)
                                    ->keyLabel(__('site-builder/page.blocks.repeater_item_field'))
                                    ->valueLabel(__('site-builder/page.blocks.translation'))
                                    ->helperText(__('site-builder/page.blocks.repeater_translation_help'))
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
                                $fields[] = Forms\Components\TextInput::make("translations.{$locale}.{$name}")
                                    ->label($label)
                                    ->required($required);
                            }
                        }
                        
                        // Create a section for this language
                        $languageName = match($locale) {
                            'ar' => __('site-builder/translation.locale_ar'),
                            'en' => __('site-builder/translation.locale_en'),
                            'fr' => __('site-builder/translation.locale_fr'),
                            'es' => __('site-builder/translation.locale_es'),
                            'de' => __('site-builder/translation.locale_de'),
                            default => $locale,
                        };
                        
                        if (!empty($fields)) {
                            $languageSections[] = Forms\Components\Section::make(__('site-builder/page.blocks.language_content', ['language' => $languageName]))
                                ->schema($fields)
                                ->columns(2);
                        }
                    }
                    
                    return $languageSections;
                }),

                Forms\Components\Group::make([
                    Forms\Components\Toggle::make('is_active')
                        ->label(__('site-builder/general.is_active'))
                        ->default(true),

                    Forms\Components\Toggle::make('is_visible')
                        ->label(__('site-builder/page.blocks.is_visible'))
                        ->default(true),
                ])
                ->columns(2),

                Forms\Components\TextInput::make('sort_order')
                    ->label(__('site-builder/general.sort_order'))
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('blockType.name')
                    ->label(__('site-builder/page.blocks.block_type'))
                    ->description(fn ($record) => $record->blockType?->category?->label() ?? '')
                    ->searchable(),

                Tables\Columns\TextColumn::make('data.title')
                    ->label(__('site-builder/general.title'))
                    ->searchable()
                    ->limit(30),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('site-builder/general.is_active'))
                    ->boolean(),
                    
                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('site-builder/general.sort_order'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('view_version')
                    ->label(__('site-builder/page.blocks.view_version'))
                    ->badge(),

                Tables\Columns\IconColumn::make('is_visible')
                    ->label(__('site-builder/page.blocks.is_visible'))
                    ->boolean(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('site-builder/general.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('site-builder/general.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('block_type_id')
                    ->label(__('site-builder/page.blocks.block_type'))
                    ->options(function () {
                        return BlockType::pluck('name', 'id');
                    })
                    ->searchable(),
                    
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('site-builder/general.is_active')),
                    
                Tables\Filters\TernaryFilter::make('is_visible')
                    ->label(__('site-builder/page.blocks.is_visible')),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('moveUp')
                    ->label(__('site-builder/general.move_up'))
                    ->icon('heroicon-o-arrow-up')
                    ->action(function ($record) {
                        $currentOrder = $record->sort_order;
                        $previousBlock = $this->getOwnerRecord()->blocks()
                            ->where('sort_order', '<', $currentOrder)
                            ->orderBy('sort_order', 'desc')
                            ->first();

                        if ($previousBlock) {
                            $previousOrder = $previousBlock->sort_order;
                            $record->update(['sort_order' => $previousOrder]);
                            $previousBlock->update(['sort_order' => $currentOrder]);
                        }
                    }),
                Tables\Actions\Action::make('moveDown')
                    ->label(__('site-builder/general.move_down'))
                    ->icon('heroicon-o-arrow-down')
                    ->action(function ($record) {
                        $currentOrder = $record->sort_order;
                        $nextBlock = $this->getOwnerRecord()->blocks()
                            ->where('sort_order', '>', $currentOrder)
                            ->orderBy('sort_order', 'asc')
                            ->first();

                        if ($nextBlock) {
                            $nextOrder = $nextBlock->sort_order;
                            $record->update(['sort_order' => $nextOrder]);
                            $nextBlock->update(['sort_order' => $currentOrder]);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }
}
