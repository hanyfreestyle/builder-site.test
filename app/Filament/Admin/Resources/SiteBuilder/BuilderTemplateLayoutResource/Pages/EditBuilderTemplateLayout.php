<?php

namespace App\Filament\Admin\Resources\SiteBuilder\BuilderTemplateLayoutResource\Pages;

use App\Filament\Admin\Resources\SiteBuilder\BuilderTemplateLayoutResource;
use Filament\Resources\Pages\EditRecord;
use App\Traits\Admin\FormAction\WithNextAndPreviousActions;
use App\Traits\Admin\FormAction\WithSaveAndClose;
use Filament\Actions;
use App\Helpers\FilamentAstrotomic\Resources\Pages\Record\EditTranslatable;

class EditBuilderTemplateLayout extends EditRecord {
    use EditTranslatable;
    use WithSaveAndClose;
    use WithNextAndPreviousActions;


    protected static string $resource = BuilderTemplateLayoutResource::class;

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



