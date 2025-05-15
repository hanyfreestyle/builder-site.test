<?php
namespace App\Filament\Admin\Resources\SiteBuilder\BuilderTemplateResource\Pages;

use App\Filament\Admin\Resources\SiteBuilder\BuilderTemplateResource;
use Filament\Resources\Pages\EditRecord;
use App\Traits\Admin\FormAction\WithNextAndPreviousActions;
use App\Traits\Admin\FormAction\WithSaveAndClose;
use Filament\Actions;

class EditBuilderTemplate extends EditRecord {
    use WithSaveAndClose;
    use WithNextAndPreviousActions;


    protected static string $resource = BuilderTemplateResource::class;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function getRecordTitle(): string {
        return $this->record->name[app()->getLocale()] ?? '';
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



