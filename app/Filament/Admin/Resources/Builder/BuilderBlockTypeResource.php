<?php

namespace App\Filament\Admin\Resources\Builder;

use App\Enums\SiteBuilder\BlockCategory;
use App\Enums\SiteBuilder\BlockTypeField;
use App\Enums\SiteBuilder\FieldWidth;
use App\Filament\Admin\Resources\Builder\BuilderBlockTypeResource\TableBuilderBlockType;
use App\FilamentCustom\Form\Inputs\SlugInput;
use App\FilamentCustom\Form\Inputs\SoftFinedTranslations;
use App\FilamentCustom\Form\Inputs\SoftTranslatableInput;
use App\Models\Builder\BlockType;
use App\Traits\Admin\Helper\SmartResourceTrait;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\BuilderBlockTypeResource\Pages;
use Filament\Forms;

class BuilderBlockTypeResource extends Resource {
    use SmartResourceTrait;
    use TableBuilderBlockType;
    protected static ?string $model = BlockType::class;
    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getPages(): array {
        return [
            'index' => Pages\ListBuilderBlockTypes::route('/'),
            'create' => Pages\CreateBuilderBlockType::route('/create'),
            'edit' => Pages\EditBuilderBlockType::route('/{record}/edit'),
        ];
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function form(Form $form): Form {
        return $form
            ->schema([
                // Basic Information Section
                Forms\Components\Section::make(__('site-builder/block-type.tabs.basic_info'))
                    ->schema([
                        Forms\Components\Group::make()->schema([
                            SlugInput::make('slug')->columnSpan(1),
                            Forms\Components\Select::make('category')
                                ->label(__('site-builder/block-type.labels.category'))
                                ->options(BlockCategory::options())
                                ->default(BlockCategory::BASIC)
                                ->searchable()
                        ])->columns(2),
                        Forms\Components\Group::make()->schema([
                            ...SoftTranslatableInput::make()->getColumns(),

                        ])->columns(2),
                    ]),


                // Schema Section
                Forms\Components\Repeater::make('schema')
                    ->label(__('site-builder/block-type.labels.schema'))
                    ->schema(fn() => self::getSchemaFieldsComponents('schema'))
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible()
//                    ->collapsed()
                    ->itemLabel(fn(array $state): ?string => getTranslatedValue($state['label'] ?? null))
                ,
            ]);
    }

    /**
     * Generate schema field components that can be reused in main schema and nested repeaters
     *
     * @param string $statePath The base state path for the fields
     * @param bool $isNested Whether this is being used in a nested context
     * @return array Form components array
     */
    public static function getSchemaFieldsComponents(string $statePath = 'schema', bool $isNested = false): array {
        // Helper function to build state path
        $path = function ($field) use ($statePath) {
            return "{$statePath}.{$field}";
        };

        // Base field components for every field type
        $baseComponents = [
            Forms\Components\Group::make()->schema([
                Forms\Components\Select::make('type')
                    ->label(__('site-builder/block-type.labels.field_type'))
                    ->options($isNested
                        ? array_filter(BlockTypeField::options(), fn($key) => $key != 'repeater', ARRAY_FILTER_USE_KEY)
                        : BlockTypeField::options())
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpan(2)
                    ->reactive(),

                Forms\Components\TextInput::make('name')
                    ->label(__('site-builder/block-type.labels.field_name'))
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),

                Forms\Components\Select::make('width')
                    ->label(__('site-builder/block-type.labels.field_width'))
                    ->options(FieldWidth::options())
                    ->default(FieldWidth::FULL)
                    ->searchable()
                    ->preload()
                    ->columnSpan(2)
                    ->required(),

                Forms\Components\Toggle::make('required')
                    ->label(__('site-builder/block-type.labels.field_required'))
                    ->inline(false)
                    ->default(false),

                Forms\Components\Toggle::make('translatable')
                    ->label(__('site-builder/block-type.labels.field_translatable'))
                    ->default(false)
                    ->inline(false)
                    ->visible(fn(Get $get) => in_array($get('type'), ['text', 'textarea', 'rich_text'])),

            ])->columnSpanFull()->columns(8),

            Forms\Components\Group::make()->schema([
                ...SoftFinedTranslations::make()
                    ->forKey('label')
                    ->setLabel(__('site-builder/block-type.labels.field_label'))
                    ->getColumns(),
                ...SoftFinedTranslations::make()
                    ->forKey('help')
                    ->setLabel(__('site-builder/block-type.labels.field_help'))
                    ->setDataRequired(false)
                    ->getColumns(),

            ])->columnSpanFull()->columns(4),

            // Image-specific fields
            Forms\Components\Group::make([
                Forms\Components\Toggle::make('config.with_thumbnail')
                    ->label(__('site-builder/block-type.image_with_thumbnail'))
                    ->default(false)
                    ->reactive()
                    ->inline(),

                Forms\Components\Grid::make(12)->schema([
                    Forms\Components\TextInput::make('config.width')
                        ->label(__('site-builder/block-type.image_width'))
                        ->numeric()
                        ->default(800)
                        ->minValue(1)
                        ->columnSpan(3),

                    Forms\Components\TextInput::make('config.height')
                        ->label(__('site-builder/block-type.image_height'))
                        ->numeric()
                        ->default(600)
                        ->minValue(1)
                        ->columnSpan(3),

                    Forms\Components\TextInput::make('config.thumb_width')
                        ->label(__('site-builder/block-type.thumb_width'))
                        ->numeric()
                        ->default(200)
                        ->minValue(1)
                        ->columnSpan(3)
                        ->visible(fn(Get $get) => $get('config.with_thumbnail') === true),

                    Forms\Components\TextInput::make('config.thumb_height')
                        ->label(__('site-builder/block-type.thumb_height'))
                        ->numeric()
                        ->default(150)
                        ->minValue(1)
                        ->columnSpan(3)
                        ->visible(fn(Get $get) => $get('config.with_thumbnail') === true),
                ]),
            ])
                ->columnSpanFull()
                ->visible(fn(Get $get) => $get('type') === 'image'),

            // Default text value
            Forms\Components\TextInput::make('default')
                ->label(__('site-builder/block-type.labels.field_default'))
                ->maxLength(255)
                ->helperText(__('site-builder/block-type.help_text.field_default'))
                ->visible(fn(Get $get) => in_array($get('type'), ['text', 'textarea', 'rich_text', 'number'])),

            // Default color picker
            Forms\Components\ColorPicker::make('default')
                ->label(__('site-builder/block-type.labels.field_default'))
                ->visible(fn(Get $get) => $get('type') === 'color'),

            // Default radio value
            Forms\Components\Radio::make('default')
                ->label(__('site-builder/block-type.labels.field_default'))
                ->options([
                    'true' => __('site-builder/block-type.field_radio.true'),
                    'false' => __('site-builder/block-type.field_radio.false'),
                ])
                ->visible(fn(Get $get) => $get('type') === 'radio'),

            // Options for select fields
            Forms\Components\KeyValue::make('options')
                ->label(__('site-builder/block-type.labels.field_options'))
                ->helperText(__('site-builder/block-type.help_text.field_options'))
                ->keyLabel(__('site-builder/block-type.option_key'))
                ->valueLabel(__('site-builder/block-type.option_value'))
                ->keyPlaceholder(__('site-builder/block-type.enter_option_key'))
                ->valuePlaceholder(__('site-builder/block-type.enter_option_value'))
                ->addActionLabel(__('site-builder/block-type.add_option'))
                ->required(fn(Get $get) => in_array($get('type'), ['select']))
                ->visible(fn(Get $get) => in_array($get('type'), ['select']))
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
                }),

            // Hide default field for types that don't need it
            Forms\Components\Hidden::make('default')
                ->visible(fn(Get $get) => in_array($get('type'), ['select', 'checkbox', 'repeater', 'file', 'image'])),
        ];

        // Add nested repeater field configuration for repeater type
        if (!$isNested) {
            $baseComponents[] = Forms\Components\Repeater::make('config.fields')
                ->label(__('site-builder/block-type.repeater_fields'))
                ->schema(fn() => self::getSchemaFieldsComponents('config.fields', true))
                ->collapsible()
                ->itemLabel(fn(array $state): ?string => $state['label'] ?? null)
                ->visible(fn(Get $get) => $get('type') === 'repeater')
                ->columnSpanFull();
        }

        return $baseComponents;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getRelations(): array {
        return [
            //
        ];
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getEloquentQuery(): Builder {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getRecordTitle(?Model $record): Htmlable|string|null {
        return getTranslatedValue($record->name) ?? null;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getNavigationGroup(): ?string {
        return __('site-builder/general.navigation_group');
    }

    public static function getNavigationLabel(): string {
        return __('site-builder/general.block_types');
    }

    public static function getModelLabel(): string {
        return __('site-builder/block-type.singular');
    }

    public static function getPluralModelLabel(): string {
        return __('site-builder/general.block_types');
    }



}
