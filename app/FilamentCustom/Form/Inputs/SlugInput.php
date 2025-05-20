<?php

namespace App\FilamentCustom\Form\Inputs;


use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class SlugInput extends TextInput {
    protected ?string $locale = 'en';
    protected ?string $permission = "no-edit";
    protected ?string $columnSpanCol = "full";

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function make(string $name): static {
        $instance = parent::make($name);
        $instance->label(__('default/lang.columns.slug'))
            ->unique(ignoreRecord: true)
            ->afterStateUpdated(function ($state, callable $set) use ($name) {
                $slug = Url_Slug($state);
                $set($name, $slug);
            })
            ->beforeStateDehydrated(function ($state) {
                return Url_Slug($state);
            })

            ->required();
        return $instance;
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
    public function setColumnSpan(string $columnSpan): static {
        $this->columnSpanCol = $columnSpan;
        return $this;
    }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function setLocale(string $locale): static {
        $this->locale = $locale;
        return $this;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function setPermission(string $permission): static {
        $this->permission = $permission;
        return $this;
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

