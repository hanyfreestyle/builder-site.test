<?php

namespace App\View\Components\Web\Footer;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CopyRight extends Component {

    public $row;
    public $isactive;
    public $option_1;



    public function __construct(
        $row = array(),
        $isactive = true,
        $option_1 = null,

    ) {
        $this->row = $row;
        $this->isactive = $isactive;
        $this->option_1 = $option_1;


    }

    public function render(): View|Closure|string {
        return view('components.web.footer.copy-right');
    }
}
