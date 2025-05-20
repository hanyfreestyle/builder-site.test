<?php

namespace App\Filament\Admin\Resources\BuilderBlockTypeResource\Pages;


use App\Filament\Admin\Resources\Builder\BuilderBlockTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBuilderBlockType extends CreateRecord {
    protected static string $resource = BuilderBlockTypeResource::class;
}
