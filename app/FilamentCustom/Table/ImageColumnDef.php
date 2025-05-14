<?php

namespace App\FilamentCustom\Table;

use Filament\Tables\Columns\ImageColumn;


class ImageColumnDef extends ImageColumn {

    protected function setUp(): void {
        parent::setUp();
        $this
            ->label('')
            ->disk('root_folder')
            ->getStateUsing(function ($record) {
                return $record->photo_thumbnail ?: $record->photo;
            })
            ->width(50)
            ->height(50)
            ->defaultImageUrl(asset('assets/client/_def/no-photo.png'));
    }
}
