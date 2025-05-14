<?php

namespace App\FilamentCustom\Table;

use Filament\Tables\Columns\ImageColumn;


class IconColumnDef extends ImageColumn {

    protected function setUp(): void {
        parent::setUp();
        $this
            ->label('')
            ->disk('root_folder')
            ->width(50)
            ->height(50)
            ->circular()
            ->defaultImageUrl(asset('assets/client/_def/no-photo.png'));
    }
}
