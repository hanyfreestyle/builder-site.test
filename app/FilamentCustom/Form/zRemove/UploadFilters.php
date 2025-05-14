<?php

namespace App\FilamentCustom\Form\zRemove;

use App\Models\WebSetting\UploadFilter;
use Filament\Forms\Components\Select;
use Filament\Forms\Set;


class UploadFilters extends Select {

    protected function setUp(): void {
        parent::setUp();

        $this
            ->label('فلتر الرفع')
            ->options(fn() => UploadFilter::getUploadFilterCashList()->pluck('name', 'id')->toArray())
            ->afterStateHydrated(function (Set $set, $state) {
                if (is_null($state)) {
                    $set('upload_filter', 1);
                }
            })
            ->afterStateUpdated(function (Set $set, $state) {
                $set('filter_id', $state); // حفظ الفلتر في حقل منفصل
            })
            ->default(1)
            ->live()
            ->required()
            ->searchable()
            ->preload()
            ->dehydrated(false);

    }
}
