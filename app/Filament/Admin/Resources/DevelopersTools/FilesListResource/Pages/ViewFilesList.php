<?php

namespace App\Filament\Admin\Resources\DevelopersTools\FilesListResource\Pages;


use App\Filament\Admin\Resources\DevelopersTools\FilesListResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFilesList extends ViewRecord {
    protected static string $resource = FilesListResource::class;

    protected function getHeaderActions(): array {
        return [
            Actions\EditAction::make(),
        ];
    }
}
