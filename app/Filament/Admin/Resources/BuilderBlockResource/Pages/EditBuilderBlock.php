<?php

namespace App\Filament\Admin\Resources\BuilderBlockResource\Pages;

use App\Filament\Admin\Resources\BuilderBlockResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;

class EditBuilderBlock extends EditRecord
{
    protected static string $resource = BuilderBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Make sure the data array is set even if empty
        $data['data'] = $data['data'] ?? [];
        
        // Make sure the translations array is set even if empty
        $data['translations'] = $data['translations'] ?? [];
        
        // If data is empty, try to load default values from block type schema
        if (empty($data['data']) && !empty($data['block_type_id'])) {
            $blockType = \App\Models\Builder\BlockType::find($data['block_type_id']);
            if ($blockType) {
                $schema = $blockType->schema ?: [];
                
                foreach ($schema as $field) {
                    $name = $field['name'] ?? '';
                    $defaultValue = $field['default'] ?? null;
                    
                    if (!empty($name) && $defaultValue !== null) {
                        $data['data'][$name] = $defaultValue;
                    }
                }
                
                // Debug
                \Illuminate\Support\Facades\Log::info('Loading default values from schema in Edit: ' . json_encode($data['data']));
            }
        }
        
        // Load the connected pages
        $block = $this->record;
        $data['pages'] = $block->pages->pluck('id')->toArray();
        
        return $data;
    }
    
    protected function afterSave(): void
    {
        // Get the edited record
        $block = $this->record;
        
        // Sync pages with pivot data
        if (isset($this->data['pages']) && is_array($this->data['pages'])) {
            $pages = $this->data['pages'];
            $pivotData = [];
            
            foreach ($pages as $pageId) {
                $pivotData[$pageId] = ['sort_order' => $this->data['sort_order'] ?? 0];
            }
            
            $block->pages()->sync($pivotData);
        }
    }
}
