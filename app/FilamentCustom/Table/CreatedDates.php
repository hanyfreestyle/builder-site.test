<?php

namespace App\FilamentCustom\Table;

use Filament\Tables\Columns\TextColumn;

class CreatedDates {
    protected bool $toggleable = true;


    public static function make(): static {
        return new static();
    }

    public function toggleable(bool $value = true): static {
        $this->toggleable = $value;
        return $this;
    }

    public function getColumns(): array {
        return [

            TextColumn::make('created_at')
                ->label(__('default/lang.columns.created_at'))
                ->date()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: $this->toggleable),

            TextColumn::make('updated_at')
                ->label(__('default/lang.columns.updated_at'))
                ->date()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: $this->toggleable),

            TextColumn::make('deleted_at')
                ->label(__('default/lang.columns.deleted_at'))
                ->date()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: $this->toggleable),
        ];
    }

}
