<?php

namespace App\View\Components\Web\Font;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LoadGoogleFont extends Component {

    public $fontsToLoad;
    public $isactive;
    public $option_1;
    public $option_2;


    public function __construct(
        # ['Alexandria', 'Lemonada', 'Marhey', 'Poppins', 'Roboto', 'Zain']
        $fontsToLoad = array(),
        $isactive = true,
        $option_1 = null,
        $option_2 = null,

    ) {
        $this->fontsToLoad = $fontsToLoad;
        $this->isactive = $isactive;
        $this->option_1 = $option_1;
        $this->option_2 = $option_2;

    }

    public function render(): View|Closure|string {
        return view('components.web.font.load-google-font');
    }
}
