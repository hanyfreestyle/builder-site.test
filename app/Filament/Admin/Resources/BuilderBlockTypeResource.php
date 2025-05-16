<?php

namespace App\Filament\Admin\Resources;

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

    protected static ?string $navigationLabel = 'Block Types';

    protected static ?string $modelLabel = 'Block Type';

    protected static ?string $pluralModelLabel = 'Block Types';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Basic Information')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(BlockType::class, 'slug', fn ($record) => $record)
                                    ->alphaDash(),
                                
                                Forms\Components\Textarea::make('description')
                                    ->maxLength(65535)
                                    ->columnSpanFull(),
                                
                                Forms\Components\TextInput::make('icon')
                                    ->maxLength(255)
                                    ->helperText('FontAwesome or other icon class, e.g., "fas fa-home"'),
                                
                                Forms\Components\TextInput::make('category')
                                    ->maxLength(255)
                                    ->helperText('Category for grouping blocks, e.g., "Basic", "Media", "Advanced"'),
                                
                                Forms\Components\Toggle::make('is_active')
                                    ->default(true),
                                
                                Forms\Components\TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->columns(2),
                        
                        Forms\Components\Tabs\Tab::make('Schema')
                            ->schema([
                                Forms\Components\Repeater::make('schema')
                                    ->label('Block Schema')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(255)
                                            ->helperText('Field name, used as the key in data storage'),
                                        
                                        Forms\Components\TextInput::make('label')
                                            ->required()
                                            ->maxLength(255)
                                            ->helperText('User-friendly label for the field'),
                                        
                                        Forms\Components\Select::make('type')
                                            ->options([
                                                'text' => 'Text',
                                                'textarea' => 'Text Area',
                                                'rich_text' => 'Rich Text Editor',
                                                'select' => 'Select Dropdown',
                                                'checkbox' => 'Checkbox',
                                                'radio' => 'Radio Buttons',
                                                'image' => 'Image Upload',
                                                'file' => 'File Upload',
                                                'date' => 'Date Picker',
                                                'time' => 'Time Picker',
                                                'color' => 'Color Picker',
                                                'icon' => 'Icon Picker',
                                                'link' => 'Link (URL + Text)',
                                                'number' => 'Number',
                                                'repeater' => 'Repeatable Item List',
                                            ])
                                            ->required(),
                                        
                                        Forms\Components\Toggle::make('required')
                                            ->default(false),
                                        
                                        Forms\Components\TextInput::make('placeholder')
                                            ->maxLength(255),
                                        
                                        Forms\Components\TextInput::make('default')
                                            ->maxLength(255)
                                            ->helperText('Default value for the field (if applicable)'),
                                        
                                        Forms\Components\KeyValue::make('options')
                                            ->helperText('Key-value pairs for select, radio, and checkbox options')
                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['select', 'radio', 'checkbox'])),
                                        
                                        Forms\Components\Toggle::make('translatable')
                                            ->default(true)
                                            ->helperText('Whether this field should be translatable'),
                                        
                                        Forms\Components\TextInput::make('help')
                                            ->maxLength(255)
                                            ->helperText('Help text to display with the field'),
                                        
                                        Forms\Components\Select::make('width')
                                            ->options([
                                                '1/2' => 'Half Width',
                                                '1/3' => 'One Third',
                                                '2/3' => 'Two Thirds',
                                                'full' => 'Full Width',
                                            ])
                                            ->default('full')
                                            ->required(),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Default Data')
                            ->schema([
                                Forms\Components\KeyValue::make('default_data')
                                    ->label('Default Field Values')
                                    ->helperText('Set default values for the block fields'),
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
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('category')
                    ->searchable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('updated_at')
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