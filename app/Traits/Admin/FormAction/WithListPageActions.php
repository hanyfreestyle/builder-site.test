<?php

namespace App\Traits\Admin\FormAction;

use Filament\Actions\Action;
use Illuminate\Support\Facades\Gate;

class WithListPageActions {

    protected ?string $langList = '';
    protected ?string $langAdd = '';
    protected string $viewCategoryPermission = 'viewAnyCategory';
    protected string $createCategoryPermission = 'addCategory';

    public function __construct() {
        $this->langList = __('default/lang.but.category_list');
        $this->langAdd = __('default/lang.but.category_add');
    }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function make(): static {
        return new static();
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function setLangList(string $langList): static {
        $this->langList = $langList;
        return $this;
    }

    public function setLangAdd(string $langAdd): static {
        $this->langAdd = $langAdd;
        return $this;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function getCategoryActionBut($resource): array {
        $actions = [];
        // تحقّق لو الـ Resource يفعّل أزرار المجموعات
        if (property_exists($resource, 'showCategoryActions') && $resource::$showCategoryActions) {
            $relatedResource = $resource::$relatedResourceClass;
            $model = $resource::$modelPolicy;

            $actions[] = Action::make('viewGroups')
                ->label($this->langList)
                ->icon('heroicon-s-rectangle-group')
                ->url(fn() => $relatedResource::getUrl())
                ->visible(fn() => Gate::forUser(auth()->user())->allows($this->viewCategoryPermission, $model))
                ->color('warning');

            $actions[] = Action::make('createGroup')
                ->label($this->langAdd)
                ->icon('heroicon-o-plus-circle')
                ->url(fn() => $relatedResource::getUrl(name: 'create'))
                ->visible(fn() => Gate::forUser(auth()->user())->allows($this->createCategoryPermission, $model))
                ->color('success');
        }

        return $actions;
    }


}
