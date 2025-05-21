<?php

namespace App\Filament\Admin\Resources\BuilderBlockTypeResource\Pages;

use App\Filament\Admin\Resources\Builder\BuilderBlockTypeResource;
use App\Traits\Admin\FormAction\WithSaveAndCreateAnother;
use App\Traits\SiteBuilder\CleansBlockSchema;
use Filament\Resources\Pages\CreateRecord;

class CreateBuilderBlockType extends CreateRecord {
    use WithSaveAndCreateAnother;
    use CleansBlockSchema;

    protected static string $resource = BuilderBlockTypeResource::class;
    protected static bool $canCreateAnother = false;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function mutateFormDataBeforeCreate(array $data): array {
        return $this->applySchemaCleaning($data);
    }

    public function getRedirectUrl(): string{
        return $this->getResource()::getUrl('index');
    }
}
