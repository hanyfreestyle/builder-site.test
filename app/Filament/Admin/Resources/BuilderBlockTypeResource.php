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
                            ->required(),

                        Forms\Components\TextInput::make('placeholder')
                            ->label(__('site-builder/block-type.labels.field_placeholder'))
                            ->maxLength(255),

                        Forms\Components\TextInput::make('default')
                            ->label(__('site-builder/block-type.labels.field_default'))
                            ->maxLength(255)
                            ->helperText(__('site-builder/block-type.help_text.field_default')),

                        Forms\Components\KeyValue::make('options')
                            ->label(__('site-builder/block-type.labels.field_options'))
                            ->helperText(__('site-builder/block-type.help_text.field_options'))
                            ->visible(fn(Forms\Get $get) => in_array($get('type'), ['select', 'radio', 'checkbox']))
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
