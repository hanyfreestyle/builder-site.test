<?php

namespace App\Filament\Admin\Resources\BuilderBlockResource\Pages;

use App\Filament\Admin\Resources\BuilderBlockResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBuilderBlock extends CreateRecord
{
    protected static string $resource = BuilderBlockResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Make sure the data array is set even if empty
        $data['data'] = $data['data'] ?? [];
        
        // Make sure the translations array is set even if empty
        $data['translations'] = $data['translations'] ?? [];
        
        return $data;
    }
    
    protected function afterCreate(): void
    {
        // Get the created record
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
