<?php

namespace App\FilamentCustom\Form\Alert;

use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;


class AlertMessage extends Placeholder {

    protected string $type = 'danger';  # danger ,warning ,info , success
    protected string $field = '__form';
    protected string $bag = 'default';

    protected function setUp(): void {
        parent::setUp();

        $this->content(function () {
            return new HtmlString(view('components.admin.html.form-message', [
                'type' => $this->type,
                'field' => $this->field,
                'bag' => $this->bag,
            ])->render());
        });
    }

    public function type(string $type): static {
        $this->type = $type;

        return $this;
    }

    public function field(string $field): static {
        $this->field = $field;

        return $this;
    }

    public function bag(string $bag): static {
        $this->bag = $bag;

        return $this;
    }
}
