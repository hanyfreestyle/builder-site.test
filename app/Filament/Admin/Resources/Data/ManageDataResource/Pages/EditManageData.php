<?php

namespace App\Filament\Admin\Resources\Data\ManageDataResource\Pages;

use App\Filament\Admin\Resources\Data\ManageDataResource;
use Filament\Actions;
use Filament\Actions\Action;
use App\Traits\Admin\FormAction\WithSaveAndClose;
use App\Traits\Admin\UploadPhoto\WithGallerySaving;
use Filament\Resources\Pages\EditRecord;
use App\Helpers\FilamentAstrotomic\Resources\Pages\Record\EditTranslatable;
use App\Traits\Admin\FormAction\WithNextAndPreviousActions;

class EditManageData extends EditRecord{
    use EditTranslatable;
    use WithSaveAndClose;
    use WithNextAndPreviousActions;
//    use WithGallerySaving;
    protected static string $resource = ManageDataResource::class;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
//    public function getRecordTitle(): string {
//        return $this->record->translate(app()->getLocale())->name ?? "";
//    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
//    public function afterSave(): void {
//        $this->setRelation('photos')->afterSaveGallery();
//    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function getHeaderActions(): array {
        return [
            ...$this->getNextAndPreviousActions(),
            Actions\DeleteAction::make(),
        ];
    }

}
