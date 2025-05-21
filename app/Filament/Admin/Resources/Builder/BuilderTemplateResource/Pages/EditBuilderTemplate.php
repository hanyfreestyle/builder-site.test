<?php

namespace App\Filament\Admin\Resources\BuilderTemplateResource\Pages;

use App\Filament\Admin\Resources\Builder\BuilderTemplateResource;
use App\Traits\Admin\FormAction\WithNextAndPreviousActions;
use App\Traits\Admin\FormAction\WithSaveAndClose;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditBuilderTemplate extends EditRecord {
    use WithSaveAndClose;
    use WithNextAndPreviousActions;

    protected static string $resource = BuilderTemplateResource::class;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function getRecordTitle(): Htmlable|string {
        return getTranslatedValue($this->record->name) ?? "";
    }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function getHeaderActions(): array {
        return [
            ...$this->getNextAndPreviousActions(),
            Actions\DeleteAction::make(),
        ];
    }
}
