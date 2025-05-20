<?php

namespace App\Filament\Admin\Resources\Builder\BuilderBlockTypeResource;

use App\Enums\SiteBuilder\BlockCategory;
use App\FilamentCustom\Table\CreatedDates;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

trait TableBuilderBlockType {
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function table(Table $table): Table {
        $thisLang = app()->getLocale();
        return $table
            ->columns([
                TextColumn::make('name.'.$thisLang)
                    ->label(__('site-builder/general.name'))
                    ->searchable(),

                TextColumn::make('slug')
                    ->label(__('site-builder/general.slug'))
                    ->searchable(),

                TextColumn::make('category')
                    ->label(__('site-builder/block-type.labels.category'))
                    ->searchable(),

                IconColumn::make('is_active')
                    ->label(__('site-builder/general.is_active'))
                    ->boolean()
                    ->sortable(),

                ...CreatedDates::make()->toggleable(true)->getColumns(),

            ])
            ->filters([
                SelectFilter::make('category')
                    ->label(__('site-builder/block-type.labels.category'))
                    ->options(BlockCategory::options())
                    ->searchable()
                    ->preload()
                    ->multiple(),

                TernaryFilter::make('is_active')
                    ->label(__('site-builder/general.is_active'))
                    ->searchable()
                    ->preload(),

                TrashedFilter::make()->searchable()->preload(),
            ])
            ->actions([
                EditAction::make()->hidden(fn($record) => $record->trashed()),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('activateBulk')
                        ->label(__('site-builder/general.activate'))
                        ->icon('heroicon-o-check')
                        ->action(fn(Collection $records) => $records->each->update(['is_active' => true])),
                    BulkAction::make('deactivateBulk')
                        ->label(__('site-builder/general.deactivate'))
                        ->icon('heroicon-o-x-mark')
                        ->action(fn(Collection $records) => $records->each->update(['is_active' => false])),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->persistSortInSession();
    }
}

