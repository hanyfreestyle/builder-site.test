<?php

namespace App\FilamentCustom\Form\Translation;

use App\FilamentCustom\Form\Editors\CKEditor4;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;


class MainSeo {
    protected bool $setDes = true;
    protected bool $setEditor = false;
    protected bool $setSeo = true;
    protected bool $setSeoRequired = false;

    public static function make(): static {
        return new static();
    }

//    public function setDes(bool $setDes): static {
//        $this->setDes = $setDes;
//        return $this;
//    }
//
//    public function setEditor(bool $setEditor): static {
//        $this->setEditor = $setEditor;
//        return $this;
//    }
//
//    public function setSeo(bool $setSeo): static {
//        $this->setSeo = $setSeo;
//        return $this;
//    }

    public function setSeoRequired(bool $setSeoRequired): static {
        $this->setSeoRequired = $setSeoRequired;
        return $this;
    }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function getColumns($tab): array {
        $columns = [];

        $columns[] = TextInput::make($tab->makeName('g_title'))
            ->label(__('default/lang.columns.g_title'))
            ->extraAttributes(fn() => rtlIfArabic($tab->getLocale()))
            ->required($this->setSeoRequired);

        $columns[] = Textarea::make($tab->makeName('g_des'))
            ->label(__('default/lang.columns.g_des'))
            ->rows(6)
            ->extraAttributes(fn() => rtlIfArabic($tab->getLocale()))
            ->required($this->setSeoRequired);

        return $columns;
    }

}
