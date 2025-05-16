<?php

namespace App\Filament\Admin\Resources\BuilderBlockTypeResource\Pages;

use App\Filament\Admin\Resources\BuilderBlockTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBuilderBlockTypes extends ListRecords
{
    protected static string $resource = BuilderBlockTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}