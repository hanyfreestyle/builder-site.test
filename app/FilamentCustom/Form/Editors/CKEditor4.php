<?php

namespace App\FilamentCustom\Form\Editors;

use Filament\Forms\Components\Contracts\HasValidationRules;
use Filament\Forms\Components\Field;

class CKEditor4 extends Field implements HasValidationRules {
    protected string $view = 'components.custom.ckeditor4';
    public int $editorHeight = 400;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function setEditorHeight(int $height): static {
        $this->editorHeight = $height;
        return $this;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function setUp(): void {
        parent::setUp();

        $this->afterStateHydrated(function (CKEditor4 $component, $state) {
            $component->state($state ?? '');
        });

        $this->viewData([
            'editorHeight' => &$this->editorHeight,
        ]);

    }

}
