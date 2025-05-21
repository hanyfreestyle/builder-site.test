<?php

namespace App\FilamentCustom\Form\Inputs;


use Filament\Forms\Components\TextInput;


class SoftFinedTranslations {
    protected string $key = 'label'; // المفتاح الأساسي: label / help / ...
    protected array $dictionary = [];
    protected string|null $setLabel = null;
    protected bool $setDataRequired = true;


    public static function make(): static {
        return new static();
    }

    public function forKey(string $key): static {
        $this->key = $key;
        return $this;
    }

    public function setLabel(?string $label = null): static {
        $this->setLabel = $label ;
        return $this;
    }

    public function withDictionary(array $dictionary): static {
        $this->dictionary = $dictionary;
        return $this;
    }

    public function setDataRequired(bool $setDataRequired): static {
        $this->setDataRequired = $setDataRequired;
        return $this;
    }

    public function getColumns(): array {

        if (empty($this->dictionary)) {
            $file = base_path("lang/lookup/{$this->key}.php");

            if (file_exists($file)) {
                $this->dictionary = include $file;
            }
        }

        $columns = [];

        // الحقل العربي
        $columns[] = TextInput::make("{$this->key}.ar")
            ->label($this->setLabel . ' (Ar)')
            ->datalist(array_keys($this->dictionary))
            ->reactive()
            ->required($this->setDataRequired)
            ->extraAttributes(fn() => rtlIfArabic('ar'))
            ->afterStateUpdated(function ($state, callable $set) {
                if (isset($this->dictionary[$state])) {
                    $set("{$this->key}.en", $this->dictionary[$state]);
                }
            });

        // الحقل الإنجليزي
        $columns[] = TextInput::make("{$this->key}.en")
            ->label($this->setLabel . ' (En)')
            ->reactive()
            ->required($this->setDataRequired)
            ->extraAttributes(fn() => rtlIfArabic('en'));

        return $columns;
    }
}


