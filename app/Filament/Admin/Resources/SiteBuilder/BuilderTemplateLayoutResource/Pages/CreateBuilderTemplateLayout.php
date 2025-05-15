<?php
namespace App\Filament\Admin\Resources\SiteBuilder\BuilderTemplateLayoutResource\Pages;

use App\Filament\Admin\Resources\SiteBuilder\BuilderTemplateLayoutResource;
use App\Helpers\FilamentAstrotomic\Resources\Pages\Record\CreateTranslatable;
use App\Traits\Admin\FormAction\WithSaveAndCreateAnother;
use App\Traits\Admin\UploadPhoto\WithGallerySaving;
use Filament\Resources\Pages\CreateRecord;

class CreateBuilderTemplateLayout extends CreateRecord{
    use CreateTranslatable;
    use WithSaveAndCreateAnother;
//    use WithGallerySaving;

    protected static string $resource = BuilderTemplateLayoutResource::class;
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


