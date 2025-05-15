<?php
namespace App\Filament\Admin\Resources\SiteBuilder\TemplateResource\Pages;

use App\Filament\Admin\Resources\SiteBuilder\TemplateResource;
use App\Helpers\FilamentAstrotomic\Resources\Pages\Record\ViewTranslatable;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;

class ViewTemplate extends ViewRecord {
    use ViewTranslatable;
    protected static string $resource = TemplateResource::class;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function getHeaderActions(): array{
        return [
            Actions\EditAction::make(),
        ];
    }

}


