<?php

namespace App\View\Components\Web\Font;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LoadWebFont extends Component {

    public $row;
    public $arFont;
    public $enFont;
    public $fontawesome;
    public $materialicon;
    public $bootstrapIcons;

    public function __construct(
        $row = array(),
        $arFont = true,
        $enFont = true,
        $fontawesome = true,
        $materialicon = true,
        $bootstrapIcons = true,

    ) {
        $this->row = $row;
        $this->arFont = $arFont;
        $this->enFont = $enFont;
        $this->fontawesome = $fontawesome;
        $this->materialicon = $materialicon;
        $this->bootstrapIcons = $bootstrapIcons;
    }

    public function render(): View|Closure|string {
        return view('components.web.font.load-web-font');
    }
}
