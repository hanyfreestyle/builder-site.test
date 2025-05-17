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

class BuilderBlockTypeResource extends Resource
{
    protected static ?string $model = BlockType::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationGroup = 'Site Builder';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'block_types';

    protected static ?string $modelLabel = 'block_type';

    protected static ?string $pluralModelLabel = 'block_types';

    public static function getNavigationLabel(): string
    {
        return __('site-builder/general.block_types');
    }

    public static function getModelLabel(): string
    {
        return __('site-builder/block-type.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('site-builder/general.block_types');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('site-builder/block-type.tabs.basic_info'))
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('site-builder/general.name'))
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('slug')
                                    ->label(__('site-builder/general.slug'))
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(BlockType::class, 'slug', fn ($record) => $record)
                                    ->alphaDash(),

                                Forms\Components\Textarea::make('description')
                                    ->label(__('site-builder/general.description'))
                                    ->maxLength(65535)
                                    ->columnSpanFull(),

                                Forms\Components\TextInput::make('icon')
                                    ->label(__('site-builder/general.icon'))
                                    ->maxLength(255)
                                    ->helperText(__('site-builder/block-type.help_text.icon')),

                                Forms\Components\Select::make('category')
                                    ->label(__('site-builder/block-type.labels.category'))
                                    ->options(BlockCategory::options())
                                    ->default(BlockCategory::BASIC)
                                    ->helperText(__('site-builder/block-type.help_text.category')),

                                Forms\Components\Toggle::make('is_active')
                                    ->label(__('site-builder/general.is_active'))
                                    ->default(true),

                                Forms\Components\TextInput::make('sort_order')
                                    ->label(__('site-builder/general.sort_order'))
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->columns(2),

                        Forms\Components\Tabs\Tab::make(__('site-builder/block-type.tabs.schema'))
                            ->schema([
                                Forms\Components\Repeater::make('schema')
                                    ->label(__('site-builder/block-type.labels.schema'))
                                    ->schema([
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
                                            ->required(),

                                        Forms\Components\Toggle::make('required')
                                            ->label(__('site-builder/block-type.labels.field_required'))
                                            ->default(false),

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
                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['select', 'radio', 'checkbox'])),

                                        Forms\Components\Toggle::make('translatable')
                                            ->label(__('site-builder/block-type.labels.field_translatable'))
                                            ->default(true)
                                            ->helperText(__('site-builder/block-type.help_text.field_translatable')),

                                        Forms\Components\TextInput::make('help')
                                            ->label(__('site-builder/block-type.labels.field_help'))
                                            ->maxLength(255)
                                            ->helperText(__('site-builder/block-type.help_text.field_help')),

                                        Forms\Components\Select::make('width')
                                            ->label(__('site-builder/block-type.labels.field_width'))
                                            ->options(FieldWidth::options())
                                            ->default(FieldWidth::FULL)
                                            ->required(),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                            ]),

                        Forms\Components\Tabs\Tab::make(__('site-builder/block-type.tabs.default_data'))
                            ->schema([
//                                Forms\Components\KeyValue::make('default_data')
//                                    ->label(__('site-builder/block-type.default_values'))
//                                    ->helperText(__('site-builder/block-type.help_text.default_values')),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
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

                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('site-builder/general.sort_order'))
                    ->numeric()
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBuilderBlockTypes::route('/'),
            'create' => Pages\CreateBuilderBlockType::route('/create'),
            'edit' => Pages\EditBuilderBlockType::route('/{record}/edit'),
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
