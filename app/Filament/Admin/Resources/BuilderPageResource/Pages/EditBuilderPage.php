<?php

namespace App\Filament\Admin\Resources\BuilderPageResource\Pages;

use App\Filament\Admin\Resources\BuilderPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBuilderPage extends EditRecord
{
    protected static string $resource = BuilderPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('view')
                ->label('View Page')
                ->url(fn ($record) => route('builder.page', ['slug' => $record->slug]))
                ->openUrlInNewTab(),
        ];
    }
}