<?php

namespace App\FilamentCustom\Table;

use Filament\Tables\Columns\TextColumn;

class TranslationTextColumn extends TextColumn {

    protected string $columnName = 'name'; // القيمة الافتراضية

    public static function make(string $name = 'name'): static {
        $instance = parent::make($name);
        $instance->columnName = $name;
        return $instance;
    }

    protected function setUp(): void {
        parent::setUp();
        $this
            ->label(__('default/lang.columns.name'))
            ->sortable(query: fn($query, $direction) => $query->orderByCurrentLocaleTranslation($this->columnName, $direction))
            ->searchable(query: fn($query, $search) => $query->whereCurrentLocaleTranslationLike($this->columnName, "%{$search}%"))
        ;
    }
}
