<?php

namespace App\FilamentCustom\Form\Editors;

use Filament\Forms\Components\Field;

class CKEditor extends Field {
    protected string $view = 'components.custom.ckeditor';

    protected function setUp(): void {
        parent::setUp();

        $this->afterStateHydrated(function (CKEditor $component, $state) {
            $component->state($state ?? '');
        });
    }

    public function getLocale(): string {
        return $this->getExtraAttributes()['locale'] ?? app()->getLocale();
    }

}
