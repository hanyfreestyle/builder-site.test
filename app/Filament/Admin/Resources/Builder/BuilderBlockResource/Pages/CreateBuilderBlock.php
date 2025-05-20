<?php

namespace App\Filament\Admin\Resources\BuilderBlockResource\Pages;

use App\Filament\Admin\Resources\Builder\BuilderBlockResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBuilderBlock extends CreateRecord {
    protected static string $resource = BuilderBlockResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array {
        // Get the block type to load schema
        if (!empty($data['block_type_id'])) {
            $blockType = \App\Models\Builder\BlockType::find($data['block_type_id']);
            if ($blockType) {
                // Initialize data array if empty
                if (empty($data['data'])) {
                    $data['data'] = [];

                    // Load default values from schema
                    $schema = $blockType->schema ?: [];
                    foreach ($schema as $field) {
                        $name = $field['name'] ?? '';
                        $defaultValue = $field['default'] ?? null;

                        if (!empty($name) && $defaultValue !== null) {
                            $data['data'][$name] = $defaultValue;
                        }
                    }

                    // Debug the loaded default values
                    \Illuminate\Support\Facades\Log::info('Created default data from schema: ' . json_encode($data['data']));
                }
            }
        }

        // Make sure the data array is set even if empty
        $data['data'] = $data['data'] ?? [];

        // Make sure the translations array is set even if empty
        $data['translations'] = $data['translations'] ?? [];

        return $data;
    }

    protected function afterCreate(): void {
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
