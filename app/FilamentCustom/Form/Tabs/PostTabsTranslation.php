<?php

namespace App\FilamentCustom\Form\Tabs;


use App\FilamentCustom\Form\Buttons\DeleteEnglishTranslation;
use App\FilamentCustom\Form\Translation\MainInput;
use App\Helpers\FilamentAstrotomic\Forms\Components\TranslatableTabs;
use App\Helpers\FilamentAstrotomic\TranslatableTab;
use App\Traits\Admin\Helper\SmartSetFunctionTrait;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Get;

class PostTabsTranslation {
    use SmartSetFunctionTrait;

    public static function make(): static {
        return new static();
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function getColumns(string $locale): array {
        $labelLang = ' (' . strtolower($locale) . ') ';
        $nameLabel = $this->setNameLabel . $labelLang;
        $descLabel = $this->setDesLabel . $labelLang;

        $components = [];
        $components[] = Group::make()
            ->schema([
                TranslatableTabs::make('translations')
                    ->availableLocales([$locale])
                    ->localeTabSchema(fn(TranslatableTab $tab) => [
                        ...MainInput::make()
                            ->setSeoRequired(false)
                            ->setEditor($this->setEditor)
                            ->setSeo($this->setSeo)
                            ->setDes($this->setDes)
                            ->setNameLabel($nameLabel)
                            ->setDesLabel($descLabel)
                            ->setMarkdown($this->setMarkdown)
                            ->setRichEditor($this->setRichEditor)
                            ->setDataRequired($this->setDataRequired)
                            ->getColumns($tab),
                    ]),
            ])
            ->columnSpan(3);

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
        if ($locale === 'en') {
            $components[] = DeleteEnglishTranslation::make()->getColumns();
        }

        $tab = Tab::make(__('default/lang.construct.locale_' . $locale))
            ->icon('heroicon-s-language')
            ->schema($components)
            ->columns(3);

        if ($locale === 'en') {
            $tab->visible(fn(Get $get) => $get('has_en'));
        }

        return [$tab];
    }
}
