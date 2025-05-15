<?php

namespace App\Filament\Admin\Resources\WebSetting\DefPhotoResource\Pages;

use App\Filament\Admin\Resources\WebSetting\DefPhotoResource;
use Filament\Actions;
use App\Helpers\FilamentAstrotomic\Resources\Pages\Record\ViewTranslatable;
use Filament\Resources\Pages\ViewRecord;

class ViewDefPhoto extends ViewRecord{
    use ViewTranslatable;
    protected static string $resource = DefPhotoResource::class;

    protected function getHeaderActions(): array{
        return [
            Actions\EditAction::make(),
        ];
    }
}
