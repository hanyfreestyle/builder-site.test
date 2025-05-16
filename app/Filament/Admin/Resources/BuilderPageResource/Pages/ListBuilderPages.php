<?php

namespace App\Filament\Admin\Resources\BuilderPageResource\Pages;

use App\Filament\Admin\Resources\BuilderPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBuilderPages extends ListRecords
{
    protected static string $resource = BuilderPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}