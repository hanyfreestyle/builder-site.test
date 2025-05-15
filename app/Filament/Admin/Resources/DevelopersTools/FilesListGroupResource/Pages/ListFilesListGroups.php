<?php

namespace App\Filament\Admin\Resources\DevelopersTools\FilesListGroupResource\Pages;

use App\Filament\Admin\Resources\DevelopersTools\FilesListGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;


class ListFilesListGroups extends ListRecords{
    protected static string $resource = FilesListGroupResource::class;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function getHeaderActions(): array{
        return [
            Actions\CreateAction::make(),
        ];
    }


}
