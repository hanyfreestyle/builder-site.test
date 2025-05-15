<?php

namespace App\Filament\Admin\Resources\WebSetting\WebPrivacyResource\Pages;

use App\Filament\Admin\Resources\WebSetting\WebPrivacyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebPrivacies extends ListRecords{
    protected static string $resource = WebPrivacyResource::class;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function getHeaderActions(): array{
        return [
            Actions\CreateAction::make(),
        ];
    }
}
