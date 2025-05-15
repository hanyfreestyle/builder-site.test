<?php
namespace App\Filament\Admin\Resources\SiteBuilder\TemplateResource\Pages;

use App\Filament\Admin\Resources\SiteBuilder\TemplateResource;
use Filament\Resources\Pages\EditRecord;
use App\Traits\Admin\FormAction\WithNextAndPreviousActions;
use App\Traits\Admin\FormAction\WithSaveAndClose;
use Filament\Actions;
use App\Traits\Admin\UploadPhoto\WithGallerySaving;
use App\Helpers\FilamentAstrotomic\Resources\Pages\Record\EditTranslatable;

class EditTemplate extends EditRecord {
    use EditTranslatable;
    use WithSaveAndClose;
    use WithNextAndPreviousActions;
    // use WithGallerySaving;

    protected static string $resource = TemplateResource::class;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function getRecordTitle(): string {
        return $this->record->name[app()->getLocale()] ?? '';
    }

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



