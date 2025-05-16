<?php

namespace App\Filament\Admin\Resources\BuilderMenuResource\Pages;

use App\Filament\Admin\Resources\BuilderMenuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBuilderMenus extends ListRecords
{
    protected static string $resource = BuilderMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}