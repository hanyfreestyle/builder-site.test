<?php
namespace App\Filament\Admin\Resources\SiteBuilder\TemplateResource\Pages;

use App\Filament\Admin\Resources\SiteBuilder\TemplateResource;
use App\Helpers\FilamentAstrotomic\Resources\Pages\Record\CreateTranslatable;
use App\Traits\Admin\FormAction\WithSaveAndCreateAnother;
use App\Traits\Admin\UploadPhoto\WithGallerySaving;
use Filament\Resources\Pages\CreateRecord;

class CreateTemplate extends CreateRecord{
    use CreateTranslatable;
    use WithSaveAndCreateAnother;
//    use WithGallerySaving;

    protected static string $resource = TemplateResource::class;
    protected static bool $canCreateAnother = false;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
//    public function afterCreate() {
//        $this->setRelation('photos')->afterCreateGallery();
//    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function getRedirectUrl(): string {
        return $this->getResource()::getUrl('index');
    }

}


