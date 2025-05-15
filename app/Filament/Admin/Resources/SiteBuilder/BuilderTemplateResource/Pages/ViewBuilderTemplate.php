<?php
namespace App\Filament\Admin\Resources\SiteBuilder\BuilderTemplateResource\Pages;

use App\Filament\Admin\Resources\SiteBuilder\BuilderTemplateResource;
use App\Helpers\FilamentAstrotomic\Resources\Pages\Record\ViewTranslatable;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;

class ViewBuilderTemplate extends ViewRecord {
    use ViewTranslatable;
    protected static string $resource = BuilderTemplateResource::class;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function getHeaderActions(): array{
        return [
            Actions\EditAction::make(),
        ];
    }

}


