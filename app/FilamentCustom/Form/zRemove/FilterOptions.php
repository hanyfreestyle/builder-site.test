<?php

namespace App\FilamentCustom\Form\zRemove;

use Filament\Tables\Filters\SelectFilter;


class FilterOptions extends SelectFilter {

    protected function setUp(): void {
        parent::setUp();

        $this
            ->preload()
            ->multiple()
            ->searchable();

    }
}
