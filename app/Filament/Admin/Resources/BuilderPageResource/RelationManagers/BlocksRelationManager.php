<?php

namespace App\Filament\Admin\Resources\BuilderPageResource\RelationManagers;

use App\Models\Builder\BlockType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BlocksRelationManager extends RelationManager
{
    protected static string $relationship = 'blocks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('block_type_id')
                    ->label('Block Type')
                    ->options(function () {
                        // Get the page's template
                        $page = $this->getOwnerRecord();
                        $templateId = $page->template_id;
                        
                        // Get all block types enabled for this template
                        return BlockType::whereHas('templates', function ($query) use ($templateId) {
                            $query->where('template_id', $templateId)
                                  ->where('is_enabled', true);
                        })->pluck('name', 'id');
                    })
                    ->reactive()
                    ->required()
                    ->searchable(),
                
                Forms\Components\Select::make('view_version')
                    ->label('View Version')
                    ->options(function (Forms\Get $get) {
                        // Get the page's template
                        $page = $this->getOwnerRecord();
                        $template = $page->template;
                        
                        // Get the selected block type
                        $blockTypeId = $get('block_type_id');
                        if (!$blockTypeId || !$template) {
                            return ['default' => 'Default'];
                        }
                        
                        // Get the relation between the template and block type
                        $relation = $template->blockTypes()->where('block_type_id', $blockTypeId)->first();
                        if (!$relation) {
                            return ['default' => 'Default'];
                        }
                        
                        // Get available view versions
                        $versions = json_decode($relation->pivot->view_versions, true) ?: ['default'];
                        return array_combine($versions, $versions);
                    })
                    ->default('default')
                    ->required(),
                
                Forms\Components\KeyValue::make('data')
                    ->label('Block Data')
                    ->keyLabel('Field')
                    ->valueLabel('Value')
                    ->required(),
                
                Forms\Components\KeyValue::make('translations')
                    ->label('Translations')
                    ->keyLabel('Locale')
                    ->valueLabel('Translations')
                    ->keyPlaceholder('Enter language code (e.g., "ar", "fr")')
                    ->valuePlaceholder('Enter translations as JSON object'),
                
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
                
                Forms\Components\Toggle::make('is_visible')
                    ->label('Visible')
                    ->default(true),
                
                Forms\Components\TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('blockType.name')
                    ->label('Block Type')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('view_version')
                    ->label('View Version'),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                
                Tables\Columns\IconColumn::make('is_visible')
                    ->label('Visible')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('moveUp')
                    ->label('Move Up')
                    ->icon('heroicon-o-arrow-up')
                    ->action(function ($record) {
                        $currentOrder = $record->sort_order;
                        $previousBlock = $this->getOwnerRecord()->blocks()
                            ->where('sort_order', '<', $currentOrder)
                            ->orderBy('sort_order', 'desc')
                            ->first();
                            
                        if ($previousBlock) {
                            $previousOrder = $previousBlock->sort_order;
                            $record->update(['sort_order' => $previousOrder]);
                            $previousBlock->update(['sort_order' => $currentOrder]);
                        }
                    }),
                Tables\Actions\Action::make('moveDown')
                    ->label('Move Down')
                    ->icon('heroicon-o-arrow-down')
                    ->action(function ($record) {
                        $currentOrder = $record->sort_order;
                        $nextBlock = $this->getOwnerRecord()->blocks()
                            ->where('sort_order', '>', $currentOrder)
                            ->orderBy('sort_order', 'asc')
                            ->first();
                            
                        if ($nextBlock) {
                            $nextOrder = $nextBlock->sort_order;
                            $record->update(['sort_order' => $nextOrder]);
                            $nextBlock->update(['sort_order' => $currentOrder]);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }
}