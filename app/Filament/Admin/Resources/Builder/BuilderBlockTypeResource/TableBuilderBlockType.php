<?php

namespace App\Filament\Admin\Resources\Builder\BuilderBlockTypeResource;

use App\Enums\SiteBuilder\BlockCategory;
use App\FilamentCustom\Table\CreatedDates;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Support\Collection;

trait TableBuilderBlockType {
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function table(Table $table): Table {
        $thisLang = app()->getLocale();
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name.' . $thisLang)
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

                ...CreatedDates::make()->toggleable(true)->getColumns(),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label(__('site-builder/block-type.labels.category'))
                    ->options(BlockCategory::options())
                    ->searchable()
                    ->preload()
                    ->multiple(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('site-builder/general.is_active'))
                    ->searchable()
                    ->preload(),

                Tables\Filters\TrashedFilter::make()->searchable()->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hidden(fn($record) => $record->trashed()),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activateBulk')
                        ->label(__('site-builder/general.activate'))
                        ->icon('heroicon-o-check')
                        ->action(fn(Collection $records) => $records->each->update(['is_active' => true])),
                    Tables\Actions\BulkAction::make('deactivateBulk')
                        ->label(__('site-builder/general.deactivate'))
                        ->icon('heroicon-o-x-mark')
                        ->action(fn(Collection $records) => $records->each->update(['is_active' => false])),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->persistSortInSession();
    }
}

