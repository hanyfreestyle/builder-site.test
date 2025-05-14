<?php

namespace App\View\Components\Web\Js;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ToggleTheme extends Component {


    public $isactive;
    public $option_1;
    public $option_2;


    public function __construct(

        $isactive = true,
        $option_1 = null,
        $option_2 = null,

    ) {

        $this->isactive = $isactive;
        $this->option_1 = $option_1;
        $this->option_2 = $option_2;

    }

    public function render(): View|Closure|string {
        return view('components.web.js.toggle-theme');
    }
}
