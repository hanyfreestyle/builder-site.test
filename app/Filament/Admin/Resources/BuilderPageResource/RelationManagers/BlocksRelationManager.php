<?php

namespace App\Filament\Admin\Resources\BuilderPageResource\RelationManagers;

use App\Models\Builder\Block;

use App\Models\Builder\BlockType;
use App\Services\Builder\FormFieldsService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BlocksRelationManager extends RelationManager
{
    protected static string $relationship = 'blocks';

    /**
     * After creating a block, also create a relation in the pivot table
     */
    protected function afterCreate(): void
    {
        $page = $this->getOwnerRecord();
        $block = $this->record;
        
        // Check if we need to create a relation in the pivot table
        if (method_exists($block, 'pages')) {
            $block->pages()->attach($page->id, ['sort_order' => $block->sort_order]);
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('blockType.name')
            ->columns([
                Tables\Columns\TextColumn::make('blockType.name')
                    ->label(__('site-builder/page.blocks.block_type'))
                    ->description(fn ($record) => $record->blockType?->category?->label() ?? '')
                    ->searchable(),

                Tables\Columns\TextColumn::make('data.title')
                    ->label(__('site-builder/general.title'))
                    ->searchable()
                    ->limit(30),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('site-builder/general.is_active'))
                    ->boolean(),
                    
                Tables\Columns\TextColumn::make('view_version')
                    ->label(__('site-builder/page.blocks.view_version'))
                    ->badge(),

                Tables\Columns\IconColumn::make('is_visible')
                    ->label(__('site-builder/page.blocks.is_visible'))
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('block_type_id')
                    ->label(__('site-builder/page.blocks.block_type'))
                    ->options(function () {
                        return BlockType::pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload(),
                    
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('site-builder/general.is_active')),
                    
                Tables\Filters\TernaryFilter::make('is_visible')
                    ->label(__('site-builder/page.blocks.is_visible')),
            ])
            ->headerActions([
                Tables\Actions\Action::make('add_block')
                    ->label(__('site-builder/page.blocks.add_block'))
                    ->url(fn () => route('filament.admin.resources.builder-blocks.create'))
                    ->icon('heroicon-o-plus')
                    ->color('primary'),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label(__('site-builder/general.edit'))
                    ->url(fn (Block $record) => route('filament.admin.resources.builder-blocks.edit', ['record' => $record->id]))
                    ->icon('heroicon-o-pencil')
                    ->color('warning'),
                
                Tables\Actions\Action::make('moveUp')
                    ->label(__('site-builder/general.move_up'))
                    ->icon('heroicon-o-arrow-up')
                    ->action(function ($record) {
                        $page = $this->getOwnerRecord();
                        $currentOrder = $record->sort_order;
                        $previousBlock = $page->blocks()
                            ->where('sort_order', '<', $currentOrder)
                            ->orderBy('sort_order', 'desc')
                            ->first();

                        if ($previousBlock) {
                            $previousOrder = $previousBlock->sort_order;
                            $record->update(['sort_order' => $previousOrder]);
                            $previousBlock->update(['sort_order' => $currentOrder]);
                            
                            // Update the pivot table if using the new relation
                            if (method_exists($record, 'pages')) {
                                $record->pages()->updateExistingPivot($page->id, ['sort_order' => $previousOrder]);
                                if ($previousBlock->pages) {
                                    $previousBlock->pages()->updateExistingPivot($page->id, ['sort_order' => $currentOrder]);
                                }
                            }
                        }
                    }),
                    
                Tables\Actions\Action::make('moveDown')
                    ->label(__('site-builder/general.move_down'))
                    ->icon('heroicon-o-arrow-down')
                    ->action(function ($record) {
                        $page = $this->getOwnerRecord();
                        $currentOrder = $record->sort_order;
                        $nextBlock = $page->blocks()
                            ->where('sort_order', '>', $currentOrder)
                            ->orderBy('sort_order', 'asc')
                            ->first();

                        if ($nextBlock) {
                            $nextOrder = $nextBlock->sort_order;
                            $record->update(['sort_order' => $nextOrder]);
                            $nextBlock->update(['sort_order' => $currentOrder]);
                            
                            // Update the pivot table if using the new relation
                            if (method_exists($record, 'pages')) {
                                $record->pages()->updateExistingPivot($page->id, ['sort_order' => $nextOrder]);
                                if ($nextBlock->pages) {
                                    $nextBlock->pages()->updateExistingPivot($page->id, ['sort_order' => $currentOrder]);
                                }
                            }
                        }
                    }),
                    
                Tables\Actions\DetachAction::make()
                    ->label(__('site-builder/page.blocks.detach'))
                    ->modalHeading(__('site-builder/page.blocks.detach_block')),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make()
                    ->label(__('site-builder/page.blocks.detach_selected')),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order');
    }
    
    public function form(Form $form): Form
    {
        // Intentionally empty form since we're redirecting to the BlockResource for editing
        return $form->schema([]);
    }
    
    public function canCreate(): bool
    {
        return false; // Disable the default create action
    }
}
