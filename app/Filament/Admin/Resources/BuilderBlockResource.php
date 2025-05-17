<?php

namespace App\Filament\Admin\Resources;

use App\Models\Builder\Block;
use App\Models\Builder\BlockType;
use App\Models\Builder\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\BuilderBlockResource\Pages;

class BuilderBlockResource extends Resource
{
    protected static ?string $model = Block::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Site Builder';

    protected static ?int $navigationSort = 25;

    protected static ?string $navigationLabel = 'blocks';

    protected static ?string $modelLabel = 'block';

    protected static ?string $pluralModelLabel = 'blocks';

    public static function getNavigationLabel(): string
    {
        return __('site-builder/general.blocks');
    }

    public static function getModelLabel(): string
    {
        return __('site-builder/block.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('site-builder/general.blocks');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Block')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('site-builder/block.tabs.basic_info'))
                            ->schema([
                                Forms\Components\Select::make('block_type_id')
                                    ->label(__('site-builder/block.block_type'))
                                    ->options(BlockType::where('is_active', true)->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        // Clear previous data when block type changes
                                        $set('data', null);
                                        $set('view_version', 'default');
                                    }),

                                Forms\Components\Select::make('view_version')
                                    ->label(__('site-builder/block.view_version'))
                                    ->options(function (Forms\Get $get) {
                                        $blockTypeId = $get('block_type_id');
                                        if (!$blockTypeId) {
                                            return ['default' => 'Default'];
                                        }

                                        $blockType = BlockType::find($blockTypeId);
                                        if (!$blockType) {
                                            return ['default' => 'Default'];
                                        }

                                        // Get templates that use this block type
                                        $templates = $blockType->templates;

                                        // Collect all view versions from all templates
                                        $allVersions = collect(['default']);
                                        foreach ($templates as $template) {
                                            $versions = $blockType->getAvailableViewVersionsForTemplate($template->id);
                                            $allVersions = $allVersions->merge($versions);
                                        }

                                        $uniqueVersions = $allVersions->unique()->values()->toArray();
                                        return array_combine($uniqueVersions, $uniqueVersions);
                                    })
                                    ->default('default')
                                    ->required(),

                                Forms\Components\Select::make('pages')
                                    ->label(__('site-builder/block.pages'))
                                    ->relationship('pages', 'title')
                                    ->multiple()
                                    ->preload()
                                    ->required()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('title')
                                            ->label(__('site-builder/general.title'))
                                            ->required(),
                                        Forms\Components\TextInput::make('slug')
                                            ->label(__('site-builder/general.slug'))
                                            ->required(),
                                        Forms\Components\Select::make('template_id')
                                            ->label(__('site-builder/page.template'))
                                            ->relationship('template', 'name')
                                            ->required(),
                                    ]),

                                Forms\Components\Toggle::make('is_active')
                                    ->label(__('site-builder/general.is_active'))
                                    ->default(true),

                                Forms\Components\Toggle::make('is_visible')
                                    ->label(__('site-builder/block.is_visible'))
                                    ->default(true),

                                Forms\Components\TextInput::make('sort_order')
                                    ->label(__('site-builder/general.sort_order'))
                                    ->numeric()
                                    ->default(0),
                            ]),

                        Forms\Components\Tabs\Tab::make(__('site-builder/block.tabs.content'))
                            ->schema(function (Forms\Get $get) {
                                $blockTypeId = $get('block_type_id');

                                if (!$blockTypeId) {
                                    return [
                                        Forms\Components\Placeholder::make('no_block_type')
                                            ->label(__('site-builder/block.no_block_type_selected'))
                                            ->content(__('site-builder/block.please_select_block_type'))
                                    ];
                                }

                                $blockType = BlockType::find($blockTypeId);

                                if (!$blockType) {
                                    return [
                                        Forms\Components\Placeholder::make('invalid_block_type')
                                            ->label(__('site-builder/block.invalid_block_type'))
                                            ->content(__('site-builder/block.block_type_not_found'))
                                    ];
                                }

                                $schema = $blockType->schema ?: [];

                                // Check if schema is empty
                                if (empty($schema)) {
                                    return [
                                        Forms\Components\Placeholder::make('empty_schema')
                                            ->label(__('site-builder/block.empty_schema'))
                                            ->content(__('site-builder/block.no_fields_defined'))
                                    ];
                                }

                                // Create form fields based on schema
                                return [
                                    Forms\Components\Section::make(__('site-builder/block.default_language_content'))
                                        ->description(__('site-builder/block.default_language_description'))
                                        ->schema(self::createFormFieldsFromSchema($schema))
                                        ->columns(2),

                                    // You can add translations section here as well
                                    Forms\Components\Section::make(__('site-builder/block.translations_heading'))
                                        ->description(__('site-builder/block.translations_description'))
                                        ->schema(function () use ($schema) {
                                            // Get all supported languages in the system
                                            $supportedLanguages = config('app.supported_locales', ['ar', 'en']);

                                            // Remove default language (first language is considered default)
                                            $defaultLanguage = $supportedLanguages[0] ?? 'ar';
                                            $translationLanguages = array_filter($supportedLanguages, function ($lang) use ($defaultLanguage) {
                                                return $lang !== $defaultLanguage;
                                            });

                                            if (empty($translationLanguages)) {
                                                return [
                                                    Forms\Components\Placeholder::make('no_translations')
                                                        ->content(__('site-builder/block.no_translations_needed'))
                                                ];
                                            }

                                            // Create a section for each language
                                            $languageSections = [];

                                            foreach ($translationLanguages as $locale) {
                                                $fields = self::createTranslationFieldsFromSchema($schema, $locale);

                                                if (!empty($fields)) {
                                                    $languageName = match($locale) {
                                                        'ar' => __('site-builder/translation.locale_ar'),
                                                        'en' => __('site-builder/translation.locale_en'),
                                                        'fr' => __('site-builder/translation.locale_fr'),
                                                        'es' => __('site-builder/translation.locale_es'),
                                                        'de' => __('site-builder/translation.locale_de'),
                                                        default => $locale,
                                                    };

                                                    $languageSections[] = Forms\Components\Section::make(__('site-builder/block.language_content', ['language' => $languageName]))
                                                        ->schema($fields)
                                                        ->columns(2)
                                                        ->collapsed();
                                                }
                                            }

                                            return $languageSections;
                                        })
                                ];
                            }),
                    ])
                    ->columnSpanFull(),
            ]);
    }

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
     * Create translation fields based on block type schema
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

            // Skip non-translatable fields
            if (!$translatable) {
                continue;
            }

            // Handle different field types for translation
            if ($type === 'textarea' || $type === 'rich_text') {
                $fields[] = Forms\Components\Textarea::make("translations.{$locale}.{$name}")
                    ->label($label)
                    ->required(false); // Translations are optional
            } elseif ($type === 'link') {
                // For link type, we need to handle the text part
                $fields[] = Forms\Components\TextInput::make("translations.{$locale}.{$name}.text")
                    ->label("{$label} (" . __('site-builder/block.link_text') . ")")
                    ->required(false);

                $fields[] = Forms\Components\TextInput::make("translations.{$locale}.{$name}.url")
                    ->label("{$label} (" . __('site-builder/block.link_url') . ")")
                    ->required(false);
            } elseif ($type === 'repeater') {
                // Create a repeater translator using KeyValue for simplicity
                $fields[] = Forms\Components\KeyValue::make("translations.{$locale}.{$name}")
                    ->label($label)
                    ->keyLabel(__('site-builder/block.repeater_item_field'))
                    ->valueLabel(__('site-builder/block.translation'))
                    ->helperText(__('site-builder/block.repeater_translation_help'));
            } else {
                $fields[] = Forms\Components\TextInput::make("translations.{$locale}.{$name}")
                    ->label($label)
                    ->required(false);
            }
        }

        return $fields;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('blockType.name')
                    ->label(__('site-builder/block.block_type'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pages.title')
                    ->label(__('site-builder/block.pages'))
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('data.title')
                    ->label(__('site-builder/general.title'))
                    ->searchable()
                    ->limit(30),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('site-builder/general.is_active'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('view_version')
                    ->label(__('site-builder/block.view_version'))
                    ->badge(),

                Tables\Columns\IconColumn::make('is_visible')
                    ->label(__('site-builder/block.is_visible'))
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
                    ->label(__('site-builder/block.block_type'))
                    ->options(BlockType::pluck('name', 'id'))
                    ->searchable(),

                Tables\Filters\SelectFilter::make('pages')
                    ->label(__('site-builder/block.pages'))
                    ->options(Page::pluck('title', 'id'))
                    ->relationship('pages', 'id')
                    ->searchable()
                    ->multiple(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('site-builder/general.is_active')),

                Tables\Filters\TernaryFilter::make('is_visible')
                    ->label(__('site-builder/block.is_visible')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('duplicate')
                    ->label(__('site-builder/block.duplicate'))
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function ($record) {
                        // Create a copy of the block with the same data
                        $newBlock = $record->replicate();
                        $newBlock->push();

                        // Copy all the relationships to pages
                        foreach ($record->pages as $page) {
                            $newBlock->pages()->attach($page->id, ['sort_order' => $page->pivot->sort_order]);
                        }

                        return redirect()->route('filament.admin.resources.builder-blocks.edit', ['record' => $newBlock->id]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activateBulk')
                        ->label(__('site-builder/general.activate'))
                        ->icon('heroicon-o-check')
                        ->action(fn (Builder $query) => $query->update(['is_active' => true])),
                    Tables\Actions\BulkAction::make('deactivateBulk')
                        ->label(__('site-builder/general.deactivate'))
                        ->icon('heroicon-o-x-mark')
                        ->action(fn (Builder $query) => $query->update(['is_active' => false])),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBuilderBlocks::route('/'),
            'create' => Pages\CreateBuilderBlock::route('/create'),
            'edit' => Pages\EditBuilderBlock::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
