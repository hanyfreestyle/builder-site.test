<?php

namespace App\Filament\Admin\Resources\BuilderBlockTypeResource\Pages;

use App\Filament\Admin\Resources\Builder\BuilderBlockTypeResource;
use App\Traits\Admin\FormAction\WithNextAndPreviousActions;
use App\Traits\Admin\FormAction\WithSaveAndClose;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBuilderBlockType extends EditRecord {
    use WithSaveAndClose;
    use WithNextAndPreviousActions;

    protected static string $resource = BuilderBlockTypeResource::class;

    protected function getHeaderActions(): array {
        return [
            ...$this->getNextAndPreviousActions(),
            Actions\DeleteAction::make(),
        ];
    }
}
