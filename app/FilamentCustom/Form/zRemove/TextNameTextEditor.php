<?php

namespace App\FilamentCustom\Form\zRemove;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class TextNameTextEditor {
    protected bool $toggleable = true;

    public static function make(): static {
        return new static();
    }

    public function toggleable(bool $value = true): static {
        $this->toggleable = $value;
        return $this;
    }

    public function getColumns($tab): array {
        return [
            TextInput::make($tab->makeName('name'))
                ->label(__('default/lang.columns.name'))
                ->extraAttributes(fn() => rtlIfArabic($tab->getLocale()))
                ->required(),

            CKEditor4::make($tab->makeName('des'))
                ->required()
                ->reactive()
                ->extraAttributes([
                    'locale' => $tab->getLocale(),
                ]),

            TextInput::make($tab->makeName('g_title'))
                ->label(__('default/lang.columns.g_title'))
                ->extraAttributes(fn() => rtlIfArabic($tab->getLocale()))
                ->nullable(),

            Textarea::make($tab->makeName('g_des'))
                ->label(__('default/lang.columns.g_des'))
                ->rows(6)
                ->extraAttributes(fn() => rtlIfArabic($tab->getLocale()))
                ->nullable(),

        ];
    }

}
