<?php

namespace App\Filament\Admin\Resources\WebSetting\UploadFilterResource\Pages;

use App\Filament\Admin\Resources\WebSetting\UploadFilterResource;
use Filament\Actions;
use App\Helpers\FilamentAstrotomic\Resources\Pages\Record\ViewTranslatable;
use Filament\Resources\Pages\ViewRecord;

class ViewUploadFilter extends ViewRecord{
    use ViewTranslatable;
    protected static string $resource = UploadFilterResource::class;

    protected function getHeaderActions(): array{
        return [
            Actions\EditAction::make(),
        ];
    }
}
