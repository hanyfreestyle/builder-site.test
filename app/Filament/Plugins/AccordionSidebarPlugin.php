<?php

namespace App\Filament\Plugins;

use Filament\Panel;
use Filament\Contracts\Plugin;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;

class AccordionSidebarPlugin implements Plugin {
    public function getId(): string {
        return 'accordion-sidebar';
    }

    public function register(Panel $panel): void {
        //
    }

    public function boot(Panel $panel): void {
        FilamentAsset::register([
            Js::make('accordion-sidebar', asset('assets/admin/plugin/accordion-sidebar/accordion-sidebar.js') ),
        ]);
    }
}
