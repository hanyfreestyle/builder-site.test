<?php

namespace App\Filament\Admin\Resources\WebSetting\DefPhotoResource\Pages;

use App\Filament\Admin\Resources\WebSetting\DefPhotoResource;
use Filament\Actions;
use App\Helpers\FilamentAstrotomic\Resources\Pages\Record\CreateTranslatable;
use App\Traits\Admin\FormAction\WithSaveAndCreateAnother;
use App\Traits\Admin\UploadPhoto\WithGallerySaving;
use Filament\Resources\Pages\CreateRecord;

class CreateDefPhoto extends CreateRecord{
    use CreateTranslatable;
    //    use WithSaveAndCreateAnother;
    //    use WithGallerySaving;
    protected static string $resource = DefPhotoResource::class;
//    protected static bool $canCreateAnother = false;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
//    public function afterCreate() {
//        $this->setRelation('photos')->afterCreateGallery();
//    }

}
