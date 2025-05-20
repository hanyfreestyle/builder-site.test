<?php

namespace App\Filament\Admin\Resources\Builder;

use App\Enums\SiteBuilder\BlockCategory;
use App\Enums\SiteBuilder\BlockTypeField;
use App\Enums\SiteBuilder\FieldWidth;
use App\Filament\Admin\Resources\Builder\BuilderBlockTypeResource\TableBuilderBlockType;
use App\FilamentCustom\Form\Inputs\SlugInput;
use App\FilamentCustom\Form\Inputs\SoftTranslatableInput;
use App\FilamentCustom\Form\Inputs\SoftTranslatableTextArea;
use App\Models\Builder\BlockType;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\BuilderBlockTypeResource\Pages;

class BuilderBlockTypeResource extends Resource {
    use TableBuilderBlockType;

    protected static ?string $model = BlockType::class;
    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';
    protected static ?string $navigationGroup = 'Site Builder';
    protected static ?int $navigationSort = 20;
    protected static ?string $navigationLabel = 'block_types';
    protected static ?string $modelLabel = 'block_type';
    protected static ?string $pluralModelLabel = 'block_types';

    public static function getNavigationLabel(): string {
        return __('site-builder/general.block_types');
    }

    public static function getModelLabel(): string {
        return __('site-builder/block-type.singular');
    }

    public static function getPluralModelLabel(): string {
        return __('site-builder/general.block_types');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function form(Form $form): Form {
        return $form
            ->schema([
                // Basic Information Section
                Section::make(__('site-builder/block-type.tabs.basic_info'))
                    ->schema([

                        Group::make()->schema([
                            SlugInput::make('slug')->columnSpan(1),
                            Select::make('category')
                                ->label(__('site-builder/block-type.labels.category'))
                                ->options(BlockCategory::options())
                                ->default(BlockCategory::BASIC)
                                ->searchable()
//                                ->helperText(__('site-builder/block-type.help_text.category')),
                        ])->columns(2),
                        Group::make()->schema([
                            ...SoftTranslatableInput::make()->getColumns(),

                        ])->columns(2),



//                        Group::make()->schema([
//                            TextInput::make('name')
//                                ->label(__('site-builder/general.name'))
//                                ->required()
//                                ->maxLength(255),
//
//                            TextInput::make('slug')
//                                ->label(__('site-builder/general.slug'))
//                                ->required()
//                                ->maxLength(255)
//                                ->unique(BlockType::class, 'slug', fn($record) => $record)
//                                ->alphaDash(),
//
//
//                            Toggle::make('is_active')
//                                ->inline(false)
//                                ->label(__('site-builder/general.is_active'))
//                                ->default(true),
//                        ])->columns(3),
                    ]),


                // Schema Section
                Repeater::make('schema')
                    ->label(__('site-builder/block-type.labels.schema'))
                    ->schema(fn() => self::getSchemaFieldsComponents('schema'))
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible()
                    ->itemLabel(fn(array $state): ?string => $state['label'] ?? null),
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
            Group::make()->schema([
                Select::make('type')
                    ->label(__('site-builder/block-type.labels.field_type'))
                    ->options($isNested
                        ? array_filter(BlockTypeField::options(), fn($key) => $key != 'repeater', ARRAY_FILTER_USE_KEY)
                        : BlockTypeField::options())
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive(),

                Select::make('width')
                    ->label(__('site-builder/block-type.labels.field_width'))
                    ->options(FieldWidth::options())
                    ->default(FieldWidth::FULL)
                    ->searchable()
                    ->preload()
                    ->required(),

                Toggle::make('required')
                    ->label(__('site-builder/block-type.labels.field_required'))
                    ->inline(false)
                    ->default(false),

                Toggle::make('translatable')
                    ->label(__('site-builder/block-type.labels.field_translatable'))
                    ->default(false)
                    ->inline(false)
                    ->helperText(__('site-builder/block-type.help_text.field_translatable'))
                    ->visible(fn(Get $get) => in_array($get('type'), ['text', 'textarea', 'rich_text'])),

            ])->columnSpanFull()->columns(4),

            Group::make()->schema([
                TextInput::make('name')
                    ->label(__('site-builder/block-type.labels.field_name'))
                    ->required()
                    ->maxLength(255)
                    ->helperText(__('site-builder/block-type.help_text.field_name')),

                TextInput::make('label')
                    ->label(__('site-builder/block-type.labels.field_label'))
                    ->required()
                    ->maxLength(255)
                    ->helperText(__('site-builder/block-type.help_text.field_label')),

                TextInput::make('help')
                    ->label(__('site-builder/block-type.labels.field_help'))
                    ->maxLength(255)
                    ->helperText(__('site-builder/block-type.help_text.field_help')),

                // placeholder field appears for specific field types
                TextInput::make('placeholder')
                    ->label(__('site-builder/block-type.labels.field_placeholder'))
                    ->maxLength(255)
                    ->visible(fn(Get $get) => in_array($get('type'), ['text', 'textarea', 'rich_text', 'number', 'select'])),

            ])->columnSpanFull()->columns(4),

            // Image-specific fields
            Group::make([
                Toggle::make('config.with_thumbnail')
                    ->label(__('site-builder/block-type.image_with_thumbnail'))
                    ->default(false)
                    ->reactive()
                    ->inline(),

                Grid::make(12)->schema([
                    TextInput::make('config.width')
                        ->label(__('site-builder/block-type.image_width'))
                        ->numeric()
                        ->default(800)
                        ->minValue(1)
                        ->columnSpan(3),

                    TextInput::make('config.height')
                        ->label(__('site-builder/block-type.image_height'))
                        ->numeric()
                        ->default(600)
                        ->minValue(1)
                        ->columnSpan(3),

                    TextInput::make('config.thumb_width')
                        ->label(__('site-builder/block-type.thumb_width'))
                        ->numeric()
                        ->default(200)
                        ->minValue(1)
                        ->columnSpan(3)
                        ->visible(fn(Get $get) => $get('config.with_thumbnail') === true),

                    TextInput::make('config.thumb_height')
                        ->label(__('site-builder/block-type.thumb_height'))
                        ->numeric()
                        ->default(150)
                        ->minValue(1)
                        ->columnSpan(3)
                        ->visible(fn(Get $get) => $get('config.with_thumbnail') === true),
                ]),
            ])->columnSpanFull()->visible(fn(Get $get) => $get('type') === 'image'),

            // Default text value
            TextInput::make('default')
                ->label(__('site-builder/block-type.labels.field_default'))
                ->maxLength(255)
                ->helperText(__('site-builder/block-type.help_text.field_default'))
                ->visible(fn(Get $get) => in_array($get('type'), ['text', 'textarea', 'rich_text', 'number'])),

            // Default color picker
            ColorPicker::make('default')
                ->label(__('site-builder/block-type.labels.field_default'))
                ->visible(fn(Get $get) => $get('type') === 'color'),

            // Default radio value
            Radio::make('default')
                ->label(__('site-builder/block-type.labels.field_default'))
                ->options([
                    'true' => __('site-builder/block-type.field_radio.true'),
                    'false' => __('site-builder/block-type.field_radio.false'),
                ])
                ->visible(fn(Get $get) => $get('type') === 'radio'),

            // Options for select fields
            KeyValue::make('options')
                ->label(__('site-builder/block-type.labels.field_options'))
                ->helperText(__('site-builder/block-type.help_text.field_options'))
                ->keyLabel(__('site-builder/block-type.option_key'))
                ->valueLabel(__('site-builder/block-type.option_value'))
                ->keyPlaceholder(__('site-builder/block-type.enter_option_key'))
                ->valuePlaceholder(__('site-builder/block-type.enter_option_value'))
                ->addActionLabel(__('site-builder/block-type.add_option'))
                ->required(fn(Get $get) => in_array($get('type'), ['select']))
                ->visible(fn(Get $get) => in_array($get('type'), ['select']))
                ->afterStateHydrated(function (KeyValue $component, $state) {
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
            Hidden::make('default')
                ->visible(fn(Get $get) => in_array($get('type'), ['select', 'checkbox', 'repeater', 'file', 'image'])),
        ];

        // Add nested repeater field configuration for repeater type
        if (!$isNested) {
            $baseComponents[] = Repeater::make('config.fields')
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

    public static function getPages(): array {
        return [
            'index' => Pages\ListBuilderBlockTypes::route('/'),
            'create' => Pages\CreateBuilderBlockType::route('/create'),
            'edit' => Pages\EditBuilderBlockType::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
