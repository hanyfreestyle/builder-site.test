<?php

namespace App\Filament\Admin\Resources;

use App\Models\Builder\Template;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\BuilderTemplateResource\Pages;
use App\Filament\Admin\Resources\BuilderTemplateResource\RelationManagers;

class BuilderTemplateResource extends Resource
{
    protected static ?string $model = Template::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Site Builder';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Templates';

    protected static ?string $modelLabel = 'Template';

    protected static ?string $pluralModelLabel = 'Templates';

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
                                    ->unique(Template::class, 'slug', fn ($record) => $record)
                                    ->alphaDash(),
                                
                                Forms\Components\Textarea::make('description')
                                    ->maxLength(65535)
                                    ->columnSpanFull(),
                                
                                Forms\Components\FileUpload::make('thumbnail')
                                    ->image()
                                    ->directory('templates/thumbnails'),
                                
                                Forms\Components\Toggle::make('is_active')
                                    ->default(true),
                                
                                Forms\Components\Toggle::make('is_default')
                                    ->default(false),
                            ])
                            ->columns(2),
                        
                        Forms\Components\Tabs\Tab::make('Settings')
                            ->schema([
                                Forms\Components\Section::make('Colors')
                                    ->schema([
                                        Forms\Components\ColorPicker::make('settings.colors.primary')
                                            ->label('Primary Color')
                                            ->default('#007bff'),
                                        
                                        Forms\Components\ColorPicker::make('settings.colors.secondary')
                                            ->label('Secondary Color')
                                            ->default('#6c757d'),
                                        
                                        Forms\Components\ColorPicker::make('settings.colors.accent')
                                            ->label('Accent Color')
                                            ->default('#fd7e14'),
                                        
                                        Forms\Components\ColorPicker::make('settings.colors.background')
                                            ->label('Background Color')
                                            ->default('#ffffff'),
                                        
                                        Forms\Components\ColorPicker::make('settings.colors.text')
                                            ->label('Text Color')
                                            ->default('#212529'),
                                    ])
                                    ->columns(2),
                                
                                Forms\Components\Section::make('Fonts')
                                    ->schema([
                                        Forms\Components\TextInput::make('settings.fonts.primary')
                                            ->label('Primary Font')
                                            ->default('Roboto, sans-serif'),
                                        
                                        Forms\Components\TextInput::make('settings.fonts.heading')
                                            ->label('Heading Font')
                                            ->default('Roboto, sans-serif'),
                                        
                                        Forms\Components\TextInput::make('settings.fonts.base_size')
                                            ->label('Base Font Size')
                                            ->default('16px'),
                                    ])
                                    ->columns(2),
                                
                                Forms\Components\Section::make('Spacing')
                                    ->schema([
                                        Forms\Components\TextInput::make('settings.spacing.base')
                                            ->label('Base Spacing')
                                            ->default('1rem'),
                                        
                                        Forms\Components\TextInput::make('settings.spacing.section')
                                            ->label('Section Spacing')
                                            ->default('3rem'),
                                    ])
                                    ->columns(2),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Languages')
                            ->schema([
                                Forms\Components\CheckboxList::make('supported_languages')
                                    ->label('Supported Languages')
                                    ->options([
                                        'en' => 'English',
                                        'ar' => 'Arabic',
                                        'fr' => 'French',
                                        'es' => 'Spanish',
                                        'de' => 'German',
                                    ])
                                    ->default(['en'])
                                    ->required(),
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
                
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->square(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_default')
                    ->boolean()
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
            RelationManagers\BlockTypesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBuilderTemplates::route('/'),
            'create' => Pages\CreateBuilderTemplate::route('/create'),
            'edit' => Pages\EditBuilderTemplate::route('/{record}/edit'),
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