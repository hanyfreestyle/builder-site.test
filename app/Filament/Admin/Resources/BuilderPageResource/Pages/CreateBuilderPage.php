<?php

namespace App\Filament\Admin\Resources\BuilderPageResource\Pages;

use App\Filament\Admin\Resources\BuilderPageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBuilderPage extends CreateRecord
{
    protected static string $resource = BuilderPageResource::class;
}