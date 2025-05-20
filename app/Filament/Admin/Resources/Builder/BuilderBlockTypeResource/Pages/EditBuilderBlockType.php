<?php

namespace App\Filament\Admin\Resources\BuilderBlockTypeResource\Pages;

use App\Filament\Admin\Resources\Builder\BuilderBlockTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBuilderBlockType extends EditRecord {
    protected static string $resource = BuilderBlockTypeResource::class;

    protected function getHeaderActions(): array {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
