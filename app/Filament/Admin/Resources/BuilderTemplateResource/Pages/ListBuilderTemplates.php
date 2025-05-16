<?php

namespace App\Filament\Admin\Resources\BuilderTemplateResource\Pages;

use App\Filament\Admin\Resources\BuilderTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBuilderTemplates extends ListRecords
{
    protected static string $resource = BuilderTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}