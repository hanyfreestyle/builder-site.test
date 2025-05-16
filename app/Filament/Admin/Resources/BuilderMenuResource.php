<?php

namespace App\Filament\Admin\Resources;

use App\Models\Builder\Menu;
use App\Models\Builder\Template;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\BuilderMenuResource\Pages;
use App\Filament\Admin\Resources\BuilderMenuResource\RelationManagers;

class BuilderMenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationGroup = 'Site Builder';

    protected static ?int $navigationSort = 40;

    protected static ?string $navigationLabel = 'Menus';

    protected static ?string $modelLabel = 'Menu';

    protected static ?string $pluralModelLabel = 'Menus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('template_id')
                    ->label('Template')
                    ->options(Template::where('is_active', true)->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(Menu::class, 'slug', fn ($record) => $record)
                    ->alphaDash(),
                
                Forms\Components\Select::make('location')
                    ->label('Location')
                    ->options([
                        'header' => 'Header',
                        'footer' => 'Footer',
                        'sidebar' => 'Sidebar',
                        'mobile' => 'Mobile',
                        'social' => 'Social Media',
                        'other' => 'Other',
                    ])
                    ->default('header')
                    ->required(),
                
                Forms\Components\KeyValue::make('translations')
                    ->label('Translations')
                    ->keyLabel('Locale')
                    ->valueLabel('Name')
                    ->keyPlaceholder('Enter language code (e.g., "ar", "fr")')
                    ->valuePlaceholder('Translated name'),
                
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
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
                
                Tables\Columns\TextColumn::make('template.name')
                    ->label('Template')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
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
                Tables\Filters\SelectFilter::make('template_id')
                    ->label('Template')
                    ->options(Template::pluck('name', 'id')),
                Tables\Filters\SelectFilter::make('location')
                    ->options([
                        'header' => 'Header',
                        'footer' => 'Footer',
                        'sidebar' => 'Sidebar',
                        'mobile' => 'Mobile',
                        'social' => 'Social Media',
                        'other' => 'Other',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn (Builder $query) => $query->update(['is_active' => true])),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn (Builder $query) => $query->update(['is_active' => false])),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\MenuItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBuilderMenus::route('/'),
            'create' => Pages\CreateBuilderMenu::route('/create'),
            'edit' => Pages\EditBuilderMenu::route('/{record}/edit'),
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