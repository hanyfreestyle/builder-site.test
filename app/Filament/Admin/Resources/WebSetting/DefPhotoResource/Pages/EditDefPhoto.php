<?php

namespace App\Filament\Admin\Resources\WebSetting\DefPhotoResource\Pages;

use App\Filament\Admin\Resources\WebSetting\DefPhotoResource;
use App\Traits\Admin\FormAction\WithNextAndPreviousActions;
use Filament\Actions;
use App\Traits\Admin\FormAction\WithSaveAndClose;
use Filament\Resources\Pages\EditRecord;
use App\Helpers\FilamentAstrotomic\Resources\Pages\Record\EditTranslatable;

class EditDefPhoto extends EditRecord{
    use EditTranslatable;
    use WithSaveAndClose;
    use WithNextAndPreviousActions;
    protected static string $resource = DefPhotoResource::class;

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
