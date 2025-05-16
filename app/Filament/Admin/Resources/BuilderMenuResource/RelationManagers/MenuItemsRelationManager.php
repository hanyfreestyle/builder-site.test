<?php

namespace App\Filament\Admin\Resources\BuilderMenuResource\RelationManagers;

use App\Enums\SiteBuilder\MenuItemType;
use App\Models\Builder\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MenuItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('site-builder/general.title'))
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\Select::make('parent_id')
                    ->label(__('site-builder/menu-item.labels.parent'))
                    ->options(function () {
                        $menuId = $this->getOwnerRecord()->id;
                        return \App\Models\Builder\MenuItem::where('menu_id', $menuId)
                            ->whereNull('parent_id')
                            ->pluck('title', 'id');
                    })
                    ->placeholder(__('site-builder/menu-item.labels.no_parent')),
                
                Forms\Components\Select::make('type')
                    ->label(__('site-builder/menu-item.labels.type'))
                    ->options(MenuItemType::options())
                    ->default(MenuItemType::URL)
                    ->reactive()
                    ->required(),
                
                Forms\Components\TextInput::make('url')
                    ->label(__('site-builder/menu-item.labels.url'))
                    ->maxLength(255)
                    ->visible(fn (Forms\Get $get) => $get('type') === MenuItemType::URL),
                
                Forms\Components\Select::make('page_id')
                    ->label(__('site-builder/menu-item.labels.page'))
                    ->options(Page::where('is_active', true)->pluck('title', 'id'))
                    ->searchable()
                    ->visible(fn (Forms\Get $get) => $get('type') === MenuItemType::PAGE),
                
                Forms\Components\TextInput::make('route')
                    ->label(__('site-builder/menu-item.labels.route'))
                    ->maxLength(255)
                    ->visible(fn (Forms\Get $get) => $get('type') === MenuItemType::ROUTE),
                
                Forms\Components\TextInput::make('icon')
                    ->label(__('site-builder/menu-item.labels.icon'))
                    ->maxLength(255)
                    ->helperText(__('site-builder/menu-item.help_text.icon')),
                
                Forms\Components\KeyValue::make('translations')
                    ->label(__('site-builder/general.translations'))
                    ->keyLabel(__('site-builder/translation.locale'))
                    ->valueLabel(__('site-builder/general.title'))
                    ->keyPlaceholder(__('site-builder/translation.key_placeholder'))
                    ->valuePlaceholder(__('site-builder/translation.value_placeholder')),
                
                Forms\Components\Toggle::make('target_blank')
                    ->label(__('site-builder/menu-item.labels.target_blank'))
                    ->default(false),
                
                Forms\Components\Toggle::make('is_active')
                    ->label(__('site-builder/general.is_active'))
                    ->default(true),
                
                Forms\Components\TextInput::make('sort_order')
                    ->label(__('site-builder/general.sort_order'))
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('site-builder/general.title'))
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('parent.title')
                    ->label(__('site-builder/menu-item.labels.parent'))
                    ->placeholder(__('site-builder/menu-item.labels.no_parent')),
                
                Tables\Columns\TextColumn::make('type')
                    ->label(__('site-builder/menu-item.labels.type'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'url' => 'gray',
                        'page' => 'success',
                        'route' => 'warning',
                        'section' => 'info',
                        default => 'gray',
                    }),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('site-builder/general.is_active'))
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('site-builder/general.sort_order'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('site-builder/menu-item.labels.type'))
                    ->options(MenuItemType::options()),
                Tables\Filters\SelectFilter::make('parent_id')
                    ->label(__('site-builder/menu-item.labels.parent'))
                    ->options(function () {
                        $menuId = $this->getOwnerRecord()->id;
                        return \App\Models\Builder\MenuItem::where('menu_id', $menuId)
                            ->whereNull('parent_id')
                            ->pluck('title', 'id')
                            ->toArray() + ['' => __('site-builder/menu-item.labels.no_parent')];
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('moveUp')
                    ->label(__('site-builder/menu-item.labels.move_up'))
                    ->icon('heroicon-o-arrow-up')
                    ->action(function ($record) {
                        $currentOrder = $record->sort_order;
                        $previousItem = $this->getOwnerRecord()->items()
                            ->where('parent_id', $record->parent_id) // Same level
                            ->where('sort_order', '<', $currentOrder)
                            ->orderBy('sort_order', 'desc')
                            ->first();
                            
                        if ($previousItem) {
                            $previousOrder = $previousItem->sort_order;
                            $record->update(['sort_order' => $previousOrder]);
                            $previousItem->update(['sort_order' => $currentOrder]);
                        }
                    }),
                Tables\Actions\Action::make('moveDown')
                    ->label(__('site-builder/menu-item.labels.move_down'))
                    ->icon('heroicon-o-arrow-down')
                    ->action(function ($record) {
                        $currentOrder = $record->sort_order;
                        $nextItem = $this->getOwnerRecord()->items()
                            ->where('parent_id', $record->parent_id) // Same level
                            ->where('sort_order', '>', $currentOrder)
                            ->orderBy('sort_order', 'asc')
                            ->first();
                            
                        if ($nextItem) {
                            $nextOrder = $nextItem->sort_order;
                            $record->update(['sort_order' => $nextOrder]);
                            $nextItem->update(['sort_order' => $currentOrder]);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label(__('site-builder/general.activate'))
                        ->icon('heroicon-o-check-circle')
                        ->action(fn (Builder $query) => $query->update(['is_active' => true])),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label(__('site-builder/general.deactivate'))
                        ->icon('heroicon-o-x-circle')
                        ->action(fn (Builder $query) => $query->update(['is_active' => false])),
                ]),
            ])
            ->defaultSort('sort_order');
    }
}