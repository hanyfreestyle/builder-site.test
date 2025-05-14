<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;



use App\Filament\Admin\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;


class ListUsers extends ListRecords {
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array {
        return [
            Actions\CreateAction::make(),
        ];
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function getTabs(): array {
        return [

            'Active' => Tab::make()
                ->label(__('default/users.tab.Active'))
                ->icon('heroicon-o-users')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('is_active', '=', 1)->where('is_archived', '=', 0))
                ->badge(static::getModel()::query()->where('is_active', '=', 1)->where('is_archived', '=', 0)->count())
                ->badgeColor('success'),

            'Pending' => Tab::make()
                ->label(__('default/users.tab.Pending'))
                ->icon('heroicon-o-lock-closed')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('is_active', '=', 0)->where('is_archived', '=', 0))
                ->badge(static::getModel()::query()->where('is_active', '=', 0)->where('is_archived', '=', 0)->count())
                ->badgeColor('warning'),

            'Archived' => Tab::make()
                ->label(__('default/users.tab.Archived'))
                ->icon('heroicon-o-archive-box-arrow-down')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('is_archived', '=', true))
                ->badge(static::getModel()::query()->where('is_archived', '=', true)->count())
                ->badgeColor('danger'),


            'All' => Tab::make()
                ->label(__('default/users.tab.all'))
                ->badge(static::getModel()::query()->count()),
        ];
    }
}
