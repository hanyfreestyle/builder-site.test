<?php

namespace App\Filament\Admin\Resources\BuilderBlockResource\Pages;

use App\Filament\Admin\Resources\Builder\BuilderBlockResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListBuilderBlocks extends ListRecords {
    protected static string $resource = BuilderBlockResource::class;

    protected function getHeaderActions(): array {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
