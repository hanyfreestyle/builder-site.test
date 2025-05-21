<?php

namespace App\Filament\Admin\Resources\BuilderBlockTypeResource\Pages;

use App\Filament\Admin\Resources\Builder\BuilderBlockTypeResource;
use App\Traits\SiteBuilder\CleansBlockSchema;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBuilderBlockType extends CreateRecord {
    use CleansBlockSchema;

    protected static string $resource = BuilderBlockTypeResource::class;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function mutateFormDataBeforeCreate(array $data): array {
        return $this->applySchemaCleaning($data);
    }

}
