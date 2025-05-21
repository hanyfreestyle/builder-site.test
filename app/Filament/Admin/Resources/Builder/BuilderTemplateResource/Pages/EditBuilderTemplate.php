<?php

namespace App\Filament\Admin\Resources\BuilderTemplateResource\Pages;

use App\Filament\Admin\Resources\Builder\BuilderTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBuilderTemplate extends EditRecord
{
    protected static string $resource = BuilderTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
