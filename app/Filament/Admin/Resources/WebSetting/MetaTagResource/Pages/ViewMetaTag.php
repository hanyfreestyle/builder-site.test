<?php

namespace App\Filament\Admin\Resources\WebSetting\MetaTagResource\Pages;

use App\Filament\Admin\Resources\WebSetting\MetaTagResource;
use Filament\Actions;
use App\Helpers\FilamentAstrotomic\Resources\Pages\Record\ViewTranslatable;
use Filament\Resources\Pages\ViewRecord;

class ViewMetaTag extends ViewRecord{
    use ViewTranslatable;
    protected static string $resource = MetaTagResource::class;

    protected function getHeaderActions(): array{
        return [
            Actions\EditAction::make(),
        ];
    }
}
