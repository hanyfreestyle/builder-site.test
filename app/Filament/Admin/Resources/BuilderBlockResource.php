<?php

namespace App\Filament\Admin\Resources;

use App\FilamentCustom\Table\CreatedDates;
use App\Models\Builder\Block;
use App\Models\Builder\BlockType;
use App\Models\Builder\Page;
use App\Services\Builder\Form\BuilderBlockResourceForm;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\BuilderBlockResource\Pages;
use Illuminate\Support\Collection;

class BuilderBlockResource extends Resource {
    protected static ?string $model = Block::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Site Builder';
    protected static ?int $navigationSort = 25;
    protected static ?string $navigationLabel = 'blocks';
    protected static ?string $modelLabel = 'block';
    protected static ?string $pluralModelLabel = 'blocks';

    public static function getNavigationLabel(): string {
        return __('site-builder/general.blocks');
    }

    public static function getModelLabel(): string {
        return __('site-builder/block.singular');
    }

    public static function getPluralModelLabel(): string {
        return __('site-builder/general.blocks');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function form(Form $form): Form {
        return $form
            ->schema([
                // Basic Information Section
                BuilderBlockResourceForm::BasicInformationSection(),

                // Content Section (Conditional based on blockType)
                BuilderBlockResourceForm::BlocksContentSection(),

                // Translations Section
                BuilderBlockResourceForm::BlocksTranslationsSection(),

            ]);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('blockType.name')
                    ->label(__('site-builder/block.block_type'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pages.title')
                    ->label(__('site-builder/block.pages'))
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('data.title')
                    ->label(__('site-builder/general.title'))
                    ->searchable()
                    ->limit(30),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('site-builder/general.is_active'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('view_version')
                    ->label(__('site-builder/block.view_version'))
                    ->badge(),

                Tables\Columns\IconColumn::make('is_visible')
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
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('duplicate')
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
                ]),
            ])
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->persistSortInSession()
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListBuilderBlocks::route('/'),
            'create' => Pages\CreateBuilderBlock::route('/create'),
            'edit' => Pages\EditBuilderBlock::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
