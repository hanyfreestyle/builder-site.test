<?php

namespace App\FilamentCustom\Form\Buttons;


use Astrotomic\Translatable\Contracts\Translatable;
use Filament\Forms\Components\Actions as FormActions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

class DeleteEnglishTranslation {
    protected bool $setDataRequired = true;

    public static function make(): static {
        return new static();
    }

//    public function setDataRequired(bool $setDataRequired): static {
//        $this->setDataRequired = $setDataRequired;
//        return $this;
//    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function getColumns(): Group {

//        dd(app()->getLocale() );

        return Group::make([
            FormActions::make([
                Action::make('delete_translation_en')
                    ->label(__('default/lang.but.delete_english_translation'))
                    ->color('danger')
                    ->requiresConfirmation()
                    ->hidden(function ($livewire) {
                        $record = $livewire->getRecord();
                        if (!Gate::allows('delete', $record)) {
                            return true;
                        }
                        if (!$record) {
                            return true;
                        }
                        if ($record instanceof Translatable) {
                            $hasTranslation = $record->hasTranslation('en');
                            if (!$hasTranslation) {
                                return true;
                            }
                        }
                    })
                    ->action(function ($livewire) {
                        $record = $livewire->getRecord();
                        if ($record instanceof Translatable) {
                            $record->translations()->where('locale', "en")->delete();
                        }
                        $record->has_en = false;
                        $record->save();
//                        session()->flash('notification', [
//                            'status' => 'success',
//                            'message' => __('تم حذف ترجمة اللغة الإنجليزية بنجاح.'),
//                        ]);
                        $resource = $livewire::getResource();
                        $editUrl = $resource::getUrl(name: 'edit', parameters: ['record' => $record]);
                        return Redirect::to($editUrl);
                    }),
            ])

        ])
            ->extraAttributes(['class' => 'flex justify-end'])
            ->columnSpanFull();


    }

}
