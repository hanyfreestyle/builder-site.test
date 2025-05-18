<?php

namespace App\Filament\Admin\Resources;

use App\Enums\SiteBuilder\BlockCategory;
use App\Enums\SiteBuilder\BlockTypeField;
use App\Enums\SiteBuilder\FieldWidth;
use App\Models\Builder\BlockType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\BuilderBlockTypeResource\Pages;
use Guava\FilamentIconPicker\Forms\IconPicker;

class BuilderBlockTypeResource extends Resource {
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

    public static function form(Form $form): Form {
        return $form
            ->schema([
                // Basic Information Section
                Forms\Components\Section::make(__('site-builder/block-type.tabs.basic_info'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('site-builder/general.name'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('slug')
                            ->label(__('site-builder/general.slug'))
                            ->required()
                            ->maxLength(255)
                            ->unique(BlockType::class, 'slug', fn($record) => $record)
                            ->alphaDash(),

                        Forms\Components\Select::make('category')
                            ->label(__('site-builder/block-type.labels.category'))
                            ->options(BlockCategory::options())
                            ->default(BlockCategory::BASIC)
                            ->searchable()
                            ->helperText(__('site-builder/block-type.help_text.category')),

                        Forms\Components\Toggle::make('is_active')
                            ->label(__('site-builder/general.is_active'))
                            ->default(true),
                    ])
                    ->columns(2),

                // Schema Section
                Forms\Components\Repeater::make('schema')
                    ->label(__('site-builder/block-type.labels.schema'))
                    ->schema([
                        Forms\Components\Group::make()->schema([
                            Forms\Components\Toggle::make('required')
                                ->label(__('site-builder/block-type.labels.field_required'))
                                ->inline(false)
                                ->default(false),

                            Forms\Components\Toggle::make('translatable')
                                ->label(__('site-builder/block-type.labels.field_translatable'))
                                ->default(true)
                                ->inline(false)
                                ->helperText(__('site-builder/block-type.help_text.field_translatable')),

                            Forms\Components\Select::make('width')
                                ->label(__('site-builder/block-type.labels.field_width'))
                                ->options(FieldWidth::options())
                                ->default(FieldWidth::FULL)
                                ->searchable()
                                ->preload()
                                ->required(),

                        ])->columnSpanFull()->columns(4),

                        Forms\Components\TextInput::make('name')
                            ->label(__('site-builder/block-type.labels.field_name'))
                            ->required()
                            ->maxLength(255)
                            ->helperText(__('site-builder/block-type.help_text.field_name')),

                        Forms\Components\TextInput::make('label')
                            ->label(__('site-builder/block-type.labels.field_label'))
                            ->required()
                            ->maxLength(255)
                            ->helperText(__('site-builder/block-type.help_text.field_label')),

                        Forms\Components\Select::make('type')
                            ->label(__('site-builder/block-type.labels.field_type'))
                            ->options(BlockTypeField::options())
                            ->default(BlockTypeField::TEXT)
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive(),

                        // حقول خاصة بنوع الصورة
                        Forms\Components\Group::make([
                            Forms\Components\Toggle::make('config.with_thumbnail')
                                ->label(__('site-builder/block-type.image_with_thumbnail'))
                                ->default(false)
                                ->inline(),

                            Forms\Components\Grid::make(12)->schema([
                                Forms\Components\TextInput::make('config.width')
                                    ->label(__('site-builder/block-type.image_width'))
                                    ->numeric()
                                    ->default(800)
                                    ->minValue(1)
                                    ->columnSpan(6),

                                Forms\Components\TextInput::make('config.height')
                                    ->label(__('site-builder/block-type.image_height'))
                                    ->numeric()
                                    ->default(600)
                                    ->minValue(1)
                                    ->columnSpan(6),
                            ]),

                            Forms\Components\Grid::make(12)->schema([
                                Forms\Components\TextInput::make('config.thumb_width')
                                    ->label(__('site-builder/block-type.thumb_width'))
                                    ->numeric()
                                    ->default(200)
                                    ->minValue(1)
                                    ->columnSpan(6),

                                Forms\Components\TextInput::make('config.thumb_height')
                                    ->label(__('site-builder/block-type.thumb_height'))
                                    ->numeric()
                                    ->default(150)
                                    ->minValue(1)
                                    ->columnSpan(6),
                            ])->visible(fn (Forms\Get $get) => $get('config.with_thumbnail') === true),
                        ])->visible(fn (Forms\Get $get) => $get('type') === 'image')
                            ->columnSpanFull(),

                        // حقول خاصة بنوع الأيقون
                        Forms\Components\Group::make([
                            IconPicker::make('config.default_icon')
                                ->label(__('site-builder/block-type.default_icon'))
                                ->searchLabels()
                                ->preload()
                                ->columns([
                                    'default' => 2,
                                    'lg' => 6,
                                    '2xl' => 8,
                                ])
                                ->sets(['fas', 'fab', "fontawesome-solid", "fontawesome-brands"])
                        ])->visible(fn (Forms\Get $get) => $get('type') === 'icon')
                            ->columnSpanFull(),

                        // حقول خاصة بنوع repeater
                        Forms\Components\Repeater::make('config.fields')
                            ->label(__('site-builder/block-type.repeater_fields'))
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('site-builder/block-type.labels.field_name'))
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('label')
                                    ->label(__('site-builder/block-type.labels.field_label'))
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\Select::make('type')
                                    ->label(__('site-builder/block-type.labels.field_type'))
                                    ->options(array_filter(BlockTypeField::options(), fn($key) => $key != 'repeater', ARRAY_FILTER_USE_KEY))
                                    ->required()
                                    ->searchable(),

                                Forms\Components\Toggle::make('required')
                                    ->label(__('site-builder/block-type.labels.field_required'))
                                    ->inline(false)
                                    ->default(false),
                            ])
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => $state['label'] ?? null)
                            ->visible(fn (Forms\Get $get) => $get('type') === 'repeater')
                            ->columnSpanFull(),

                        // حقل placeholder يظهر لأنواع محددة من الحقول
                        Forms\Components\TextInput::make('placeholder')
                            ->label(__('site-builder/block-type.labels.field_placeholder'))
                            ->maxLength(255)
                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['text', 'textarea', 'rich_text', 'number', 'email', 'password', 'date', 'time', 'select'])),

                        // حقل القيم الافتراضية للنصوص والأرقام
                        Forms\Components\TextInput::make('default')
                            ->label(__('site-builder/block-type.labels.field_default'))
                            ->maxLength(255)
                            ->helperText(__('site-builder/block-type.help_text.field_default'))
                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['text', 'textarea', 'number'])),

                        // حقل اللون الافتراضي
                        Forms\Components\ColorPicker::make('default')
                            ->label(__('site-builder/block-type.labels.field_default'))
                            ->visible(fn (Forms\Get $get) => $get('type') === 'color'),

                        // حقل التاريخ الافتراضي
                        Forms\Components\DatePicker::make('default')
                            ->label(__('site-builder/block-type.labels.field_default'))
                            ->visible(fn (Forms\Get $get) => $get('type') === 'date'),

                        // حقل الوقت الافتراضي
                        Forms\Components\TimePicker::make('default')
                            ->label(__('site-builder/block-type.labels.field_default'))
                            ->visible(fn (Forms\Get $get) => $get('type') === 'time'),

                        // حقل القيمة الافتراضية للراديو
                        Forms\Components\Radio::make('default')
                            ->label(__('site-builder/block-type.labels.field_default'))
                            ->options([
                                'true' => __('site-builder/block-type.field_radio.true'),
                                'false' => __('site-builder/block-type.field_radio.false'),
                            ])
                            ->visible(fn (Forms\Get $get) => $get('type') === 'radio'),

                        Forms\Components\KeyValue::make('options')
                            ->label(__('site-builder/block-type.labels.field_options'))
                            ->helperText(__('site-builder/block-type.help_text.field_options'))
                            ->keyLabel(__('site-builder/block-type.option_key'))
                            ->valueLabel(__('site-builder/block-type.option_value'))
                            ->keyPlaceholder(__('site-builder/block-type.enter_option_key'))
                            ->valuePlaceholder(__('site-builder/block-type.enter_option_value'))
                            ->addActionLabel(__('site-builder/block-type.add_option'))
                            ->required(fn (Forms\Get $get) => in_array($get('type'), ['select', 'checkbox', 'radio']))
                            ->visible(fn(Forms\Get $get) => in_array($get('type'), ['select', 'checkbox', 'radio']))
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

                        Forms\Components\TextInput::make('help')
                            ->label(__('site-builder/block-type.labels.field_help'))
                            ->maxLength(255)
                            ->helperText(__('site-builder/block-type.help_text.field_help')),

                        // احفاء حقل default للأنواع التي لا تحتاجه
                        Forms\Components\Hidden::make('default')
                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['select', 'checkbox', 'repeater', 'file', 'image'])),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible()
                    ->itemLabel(fn(array $state): ?string => $state['label'] ?? null),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('site-builder/general.name'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label(__('site-builder/general.slug'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('category')
                    ->label(__('site-builder/block-type.labels.category'))
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('site-builder/general.is_active'))
                    ->boolean()
                    ->sortable(),

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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

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
