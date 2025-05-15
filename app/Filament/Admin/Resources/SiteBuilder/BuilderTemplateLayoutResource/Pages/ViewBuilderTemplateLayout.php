<?php
namespace App\Filament\Admin\Resources\SiteBuilder\BuilderTemplateLayoutResource\Pages;

use App\Filament\Admin\Resources\SiteBuilder\BuilderTemplateLayoutResource;
use App\Helpers\FilamentAstrotomic\Resources\Pages\Record\ViewTranslatable;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;

class ViewBuilderTemplateLayout extends ViewRecord {
    use ViewTranslatable;
    protected static string $resource = BuilderTemplateLayoutResource::class;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function getHeaderActions(): array{
        return [
            Actions\EditAction::make(),
        ];
    }

}


