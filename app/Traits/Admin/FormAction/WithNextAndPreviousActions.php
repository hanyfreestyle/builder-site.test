<?php

namespace App\Traits\Admin\FormAction;

use Filament\Actions\Action;

trait WithNextAndPreviousActions {

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function getNextAndPreviousActions(): array {
        $modelClass = static::getModel();
        $resourceClass = static::getResource();
        return [
            Action::make('next')
                ->hiddenLabel()
                ->icon('heroicon-s-chevron-double-right')
                ->url(function () use ($modelClass, $resourceClass) {
                    $nextId = $modelClass::where('id', '>', $this->record->id)->min('id');

                    return $nextId
                        ? $resourceClass::getUrl('edit', ['record' => $nextId])
                        : null;
                })
                ->disabled(function () use ($modelClass) {
                    return !$modelClass::where('id', '>', $this->record->id)->exists();
                }),

            Action::make('previous')
                ->hiddenLabel()
                ->icon('heroicon-s-chevron-double-left')
                ->iconPosition('after')
                ->url(function () use ($modelClass, $resourceClass) {
                    $prevId = $modelClass::where('id', '<', $this->record->id)->max('id');

                    return $prevId
                        ? $resourceClass::getUrl('edit', ['record' => $prevId])
                        : null;
                })
                ->disabled(function () use ($modelClass) {
                    return !$modelClass::where('id', '<', $this->record->id)->exists();
                }),

        ];
    }
}
