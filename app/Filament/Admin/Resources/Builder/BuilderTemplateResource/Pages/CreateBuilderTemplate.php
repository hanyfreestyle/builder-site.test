<?php

namespace App\Filament\Admin\Resources\BuilderTemplateResource\Pages;

use App\Filament\Admin\Resources\Builder\BuilderTemplateResource;
use App\Traits\Admin\FormAction\WithSaveAndCreateAnother;
use App\Traits\Admin\UploadPhoto\WithGallerySaving;
use Filament\Resources\Pages\CreateRecord;

class CreateBuilderTemplate extends CreateRecord {
    use WithSaveAndCreateAnother;
    use WithGallerySaving;

    protected static string $resource = BuilderTemplateResource::class;
    protected static bool $canCreateAnother = false;


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function afterCreate() {
        $this->afterCreateGallery();
    }

    public function getRedirectUrl(): string{
        return $this->getResource()::getUrl('index');
    }
}
