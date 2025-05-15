<?php

namespace App\Filament\Admin\Resources\WebSetting\WebSiteSettingsResource\Pages;

use App\Filament\Admin\Resources\WebSetting\WebSiteSettingsResource;
use Filament\Actions;
use App\Helpers\FilamentAstrotomic\Resources\Pages\Record\CreateTranslatable;
use App\Traits\Admin\FormAction\WithSaveAndCreateAnother;
use App\Traits\Admin\UploadPhoto\WithGallerySaving;
use Filament\Resources\Pages\CreateRecord;

class CreateWebSiteSettings extends CreateRecord{
    use CreateTranslatable;
    //    use WithSaveAndCreateAnother;
    //    use WithGallerySaving;
    protected static string $resource = WebSiteSettingsResource::class;
//    protected static bool $canCreateAnother = false;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
//    public function afterCreate() {
//        $this->setRelation('photos')->afterCreateGallery();
//    }

}
