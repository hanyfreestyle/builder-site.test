<?php

namespace App\Filament\Admin\Resources\BuilderMenuResource\Pages;

use App\Filament\Admin\Resources\BuilderMenuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBuilderMenu extends EditRecord
{
    protected static string $resource = BuilderMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}