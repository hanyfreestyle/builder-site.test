<?php

namespace App\Filament\Admin\Resources\BuilderBlockTypeResource\Pages;

use App\Filament\Admin\Resources\Builder\BuilderBlockTypeResource;
use App\Traits\Admin\FormAction\WithNextAndPreviousActions;
use App\Traits\Admin\FormAction\WithSaveAndClose;
use App\Traits\SiteBuilder\CleansBlockSchema;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditBuilderBlockType extends EditRecord {
    use WithSaveAndClose;
    use WithNextAndPreviousActions;
    use CleansBlockSchema;

    protected static string $resource = BuilderBlockTypeResource::class;
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

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function mutateFormDataBeforeCreate(array $data): array {
        return $this->applySchemaCleaning($data);
    }

}
