<?php

namespace App\FilamentCustom\Form\Inputs;

use App\Traits\Admin\Helper\SmartSetFunctionTrait;
use Filament\Forms\Components\TextInput;


class SoftTranslatableInput {
    use SmartSetFunctionTrait;

    protected string|null $setLabel = null;
    protected string $setInputName = 'name';
    protected array $setLang = [];


    public function __construct() {
        $this->setLabel = __('default/lang.construct.name');
        $this->setLang = config('app.web_add_lang');
    }


    public static function make(): static {
        return new static();
    }

    public function setLabel(?string $label = null): static {
        $this->setLabel = $label ?? __('default/lang.columns.name');
        return $this;
    }

    public function setInputName(?string $name): static {
        $this->setInputName = $name ?? 'name';
        return $this;
    }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function getColumns(): array {
        $columns = [];
        foreach ($this->setLang as $lang) {
            $printLang = "(" . ucfirst($lang) . ")";
            $columns[] = TextInput::make($this->setInputName . '.' . $lang)
                ->label( $this->setLabel . " " . $printLang)
                ->extraAttributes(fn() => rtlIfArabic($lang))
                ->required($this->setDataRequired);
        }
        return $columns;
    }
}

