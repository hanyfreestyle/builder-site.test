<?php

namespace App\FilamentCustom\Form\Translation;


use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class TranslatableSlugInput extends TextInput {

    protected string $table;
    protected string $column;
    protected ?string $locale = null;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function uniqueForLocale(string $table, string $column): static {
        $this->table = $table;
        $this->column = $column;

        return $this->unique(
            table: $this->table,
            column: $this->column,
            modifyRuleUsing: function ($rule) {
                $record = $this->getContainer()->getLivewire()->form->getRecord();

                if ($record && $record->exists && $this->locale) {
                    $translation = $record->translations()
                        ->where('locale', $this->locale)
                        ->first();

                    if ($translation) {
                        $rule->ignore($translation->id);
                    }
                }

                if ($this->locale) {
                    $rule->where('locale', $this->locale);
                }

                return $rule;
            }
        );
    }

    public function setLocale(string $locale): static {
        $this->locale = $locale;

        return $this->extraAttributes(fn() => [
            'dir' => $this->locale === 'ar' ? 'rtl' : 'ltr',
            'style' => 'text-align: ' . ($this->locale === 'ar' ? 'right' : 'left') . ';',
        ]);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function make(string $name): static {
        return parent::make($name)
            ->label(__('default/lang.columns.slug'))
            ->lazy()
            ->maxLength(255)
            ->afterStateUpdated(function ($state, callable $set) use ($name) {
                // نحوِّل القيمة التي أدخلها المستخدم إلى slug ونحقنها في الحقل نفسه
                $set($name, Url_Slug($state));
            })
            ->beforeStateDehydrated(function ($state, callable $set) use ($name) {
                // نقوم بتحويل القيمة المدخلة إلى slug قبل تخزينها
                $set($name, Url_Slug($state));
            })
            ->required();

    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function setUp(): void {
        parent::setUp();
        $this->extraAttributes(fn() => rtlIfArabic($this->locale));
        $this->disabled(fn($record) => $this->handlePermission($record));
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function handlePermission($record):bool{
        if (!$record) {
            return false;
        }
        if (Auth::user()->id == 1){
            return false ;
        }
        if (Gate::allows('updateSlug', $record)) {
            return false;
        }
        return true;
    }

}

