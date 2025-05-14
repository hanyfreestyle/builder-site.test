<?php

namespace App\FilamentCustom\Form\Translation;

use App\FilamentCustom\Form\Editors\CKEditor4;
use App\Traits\Admin\Helper\SmartSetFunctionTrait;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;


class MainInput {
    use SmartSetFunctionTrait;

    public static function make(): static {
        return new static();
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function getColumns($tab): array {
        $columns = [];

        $columns[] = TextInput::make($tab->makeName('name'))
            ->label($this->setNameLabel)
            ->extraAttributes(fn() => rtlIfArabic($tab->getLocale()))
            ->required(true);

        if ($this->setDes) {
            if ($this->setEditor) {
                $columns[] = CKEditor4::make($tab->makeName('des'))
                    ->label($this->setDesLabel)
                    ->hiddenLabel()
                    ->required($this->setDataRequired)
                    ->reactive()
                    ->extraAttributes([
                        'locale' => $tab->getLocale(),
                    ]);
            } else {
                if ($this->setMarkdown) {
                    $columns[] = MarkdownEditor::make($tab->makeName('des'))
                        ->label($this->setDesLabel)
                        ->minHeight('25.25rem')
                        ->extraAttributes(fn() => rtlIfArabic($tab->getLocale()))
                        ->required($this->setDataRequired);

                }elseif ($this->setRichEditor){
                    $columns[] = RichEditor::make($tab->makeName('des'))
                        ->label($this->setDesLabel)
                        ->toolbarButtons([
                            'attachFiles',
                            'blockquote',
                            'bold',
                            'bulletList',
                            'codeBlock',
                            'h2',
                            'h3',
                            'italic',
                            'link',
                            'orderedList',
                            'redo',
                            'strike',
                            'underline',
                            'undo',
                        ]);
                } else {
                    $columns[] = Textarea::make($tab->makeName('des'))
                        ->label($this->setDesLabel)
                        ->rows(6)
                        ->extraAttributes(fn() => rtlIfArabic($tab->getLocale()))
                        ->required($this->setDataRequired);
                }
            }
        }

        if ($this->setSeo) {
            $columns[] = TextInput::make($tab->makeName('g_title'))
                ->label(__('default/lang.columns.g_title'))
                ->extraAttributes(fn() => rtlIfArabic($tab->getLocale()))
                ->required($this->setSeoRequired);

            $columns[] = Textarea::make($tab->makeName('g_des'))
                ->label(__('default/lang.columns.g_des'))
                ->rows(6)
                ->extraAttributes(fn() => rtlIfArabic($tab->getLocale()))
                ->required($this->setSeoRequired);
        }
        return $columns;
    }

}
