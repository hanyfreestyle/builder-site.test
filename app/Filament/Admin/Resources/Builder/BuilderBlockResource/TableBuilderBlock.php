<?php

namespace App\Filament\Admin\Resources\Builder\BuilderBlockResource;

use App\FilamentCustom\Table\CreatedDates;
use App\Models\Builder\BlockType;
use App\Models\Builder\Page;

use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

trait TableBuilderBlock {
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function table(Table $table): Table {
        return $table
            ->columns([
                TextColumn::make('blockType.name')
                    ->label(__('site-builder/block.block_type'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pages.title')
                    ->label(__('site-builder/block.pages'))
                    ->badge()
                    ->searchable(),

                TextColumn::make('data.title')
                    ->label(__('site-builder/general.title'))
                    ->searchable()
                    ->limit(30),

                IconColumn::make('is_active')
                    ->label(__('site-builder/general.is_active'))
                    ->boolean(),

                TextColumn::make('view_version')
                    ->label(__('site-builder/block.view_version'))
                    ->badge(),

                IconColumn::make('is_visible')
                    ->label(__('site-builder/block.is_visible'))
                    ->boolean(),

                ...CreatedDates::make()->toggleable(true)->getColumns(),
            ])
            ->filters([
                SelectFilter::make('block_type_id')
                    ->label(__('site-builder/block.block_type'))
                    ->options(BlockType::pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('pages')
                    ->label(__('site-builder/block.pages'))
                    ->options(Page::pluck('title', 'id'))
                    ->relationship('pages', 'title')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                TernaryFilter::make('is_active')
                    ->label(__('site-builder/general.is_active'))
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_visible')
                    ->label(__('site-builder/block.is_visible'))
                    ->searchable()
                    ->preload(),

                TrashedFilter::make()->searchable()->preload(),
            ])
            ->actions([
                EditAction::make()->hidden(fn($record) => $record->trashed()),
                DeleteAction::make(),
                Action::make('duplicate')
                    ->label(__('site-builder/block.duplicate'))
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function ($record) {
                        // Create a copy of the block with the same data
                        $newBlock = $record->replicate();
                        $newBlock->push();
                        // Copy all the relationships to pages
                        foreach ($record->pages as $page) {
                            $newBlock->pages()->attach($page->id, ['sort_order' => $page->pivot->sort_order]);
                        }
                        return redirect()->route('filament.admin.resources.builder-blocks.edit', ['record' => $newBlock->id]);
                    })->hidden(fn($record) => $record->trashed()),
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
                ]),
            ])
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->persistSortInSession()
            ->defaultSort('created_at', 'desc');
    }
}

