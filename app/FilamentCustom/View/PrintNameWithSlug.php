<?php

namespace App\FilamentCustom\View;

use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\TextEntry;

class PrintNameWithSlug {

    protected bool $showSeo = false;
    protected bool $showUUid = false;

    public static function make(): static {
        return new static();
    }

    public function setSeo(bool $value): static {
        $this->showSeo = $value;
        return $this;
    }

    public function setUUID(bool $value): static {
        $this->showUUid = $value;
        return $this;
    }

    public function getColumns(): array {
        return [
            Group::make()->schema(
                array_values(array_filter([ // ✅ تأكد من إزالة القيم `null`
                    TextEntry::make('id')
                        ->label("Id")
                        ->view('components.custom.text-view-entry'),

                    $this->showUUid ? TextEntry::make('uuid')
                        ->label("UUID")
                        ->view('components.custom.text-view-entry') : null,
                ]))) // ✅ `array_values()` لإعادة ترتيب الفهارس ومنع `null`

            ->columnSpanFull()->columns(2),

            Group::make()->schema(
                array_values(array_filter([ // ✅ تأكد من إزالة القيم `null`

                    TextEntry::make('name')
                        ->label(__('default/lang.columns.name'))
                        ->view('components.custom.text-view-entry'),

                    TextEntry::make('slug')
                        ->label(__('default/lang.columns.slug'))
                        ->view('components.custom.text-view-entry'),

                    // ✅ فقط أضف `g_title` و `g_des` إذا كان `showSeo = true`
                    $this->showSeo ? TextEntry::make('g_title')
                        ->label(__('default/lang.columns.g_title'))
                        ->view('components.custom.text-view-entry') : null,

                    $this->showSeo ? TextEntry::make('g_des')
                        ->label(__('default/lang.columns.g_des'))
                        ->view('components.custom.text-view-entry') : null,

                ]))) // ✅ `array_values()` لإعادة ترتيب الفهارس ومنع `null`
            ->columnSpanFull()->columns(2),
        ];
    }

}
