<?php

namespace App\Filament\Admin\Resources\WebSetting\DefPhotoResource\Pages;

use App\Filament\Admin\Resources\WebSetting\DefPhotoResource;
use App\Models\WebSetting\DefPhoto;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;


class ListDefPhotos extends ListRecords{
    protected static string $resource = DefPhotoResource::class;
    protected static string $view = 'filament.resources.DefPhoto.custom-list';

    public function getViewData(): array {
        $modelClass = static::getModel();
        $resourceClass = static::getResource();

        return [
            'records' => DefPhoto::orderBy('position')->get(),
            'modelClass' => $modelClass,
            'resourceClass' => $resourceClass,
        ];
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function getHeaderActions(): array{
        return [
            Actions\CreateAction::make(),
        ];
    }

}
