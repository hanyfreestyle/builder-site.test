<?php

namespace App\Filament\Admin\Resources;

use App\Enums\SiteBuilder\MenuLocation;
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

    protected static ?string $navigationLabel = 'menus';

    protected static ?string $modelLabel = 'menu';

    protected static ?string $pluralModelLabel = 'menus';
    
    public static function getNavigationLabel(): string
    {
        return __('site-builder/general.menus');
    }

    public static function getModelLabel(): string
    {
        return __('site-builder/menu.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('site-builder/general.menus');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('template_id')
                    ->label(__('site-builder/menu.labels.template'))
                    ->options(Template::where('is_active', true)->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                
                Forms\Components\TextInput::make('name')
                    ->label(__('site-builder/general.name'))
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\TextInput::make('slug')
                    ->label(__('site-builder/general.slug'))
                    ->required()
                    ->maxLength(255)
                    ->unique(Menu::class, 'slug', fn ($record) => $record)
                    ->alphaDash(),
                
                Forms\Components\Select::make('location')
                    ->label(__('site-builder/menu.labels.location'))
                    ->options(MenuLocation::options())
                    ->default(MenuLocation::HEADER)
                    ->required(),
                
                Forms\Components\KeyValue::make('translations')
                    ->label(__('site-builder/general.translations'))
                    ->keyLabel(__('site-builder/translation.locale'))
                    ->valueLabel(__('site-builder/general.name'))
                    ->keyPlaceholder(__('site-builder/translation.key_placeholder'))
                    ->valuePlaceholder(__('site-builder/translation.value_placeholder')),
                
                Forms\Components\Toggle::make('is_active')
                    ->label(__('site-builder/general.is_active'))
                    ->default(true),
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
                
                Tables\Columns\TextColumn::make('template.name')
                    ->label(__('site-builder/menu.labels.template'))
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('location')
                    ->label(__('site-builder/menu.labels.location'))
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
                Tables\Filters\SelectFilter::make('template_id')
                    ->label(__('site-builder/menu.labels.template'))
                    ->options(Template::pluck('name', 'id')),
                Tables\Filters\SelectFilter::make('location')
                    ->options(MenuLocation::options()),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('site-builder/general.is_active')),
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
                        ->label(__('site-builder/general.activate'))
                        ->icon('heroicon-o-check-circle')
                        ->action(fn (Builder $query) => $query->update(['is_active' => true])),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label(__('site-builder/general.deactivate'))
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