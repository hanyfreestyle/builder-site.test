<?php

namespace App\FilamentCustom\View;

use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\TextEntry;

class TextEntryWithView extends TextEntry {

    protected function setUp(): void {
        parent::setUp();
        $this
            ->view('components.custom.text-view-entry');
    }

}
