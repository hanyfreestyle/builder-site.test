<?php

namespace App\FilamentCustom\View;


use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;

class PrintDatesWithIaActive {
    protected bool $toggleable = true;

    public static function make(): static {
        return new static();
    }


    public function getColumns(): array {
        return [
            Group::make()->schema([
                TextEntry::make('created_at')
                    ->label(__('default/lang.columns.created_at'))
                    ->view('components.custom.text-view-entry', ['viewType' => 'dateOnly']),

                TextEntry::make('updated_at')
                    ->label(__('default/lang.columns.updated_at'))
                    ->view('components.custom.text-view-entry', ['viewType' => 'dateOnly']),

                IconEntry::make('is_active')
                    ->label(__('default/lang.columns.is_active'))
                    ->boolean(),

            ])->columnSpanFull()->columns(3)
        ];
    }

}
