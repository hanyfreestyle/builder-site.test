<?php

namespace App\View\Components\Web\Html;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ConfirmMassage extends Component {

    public $row;
    public $isactive;
    public $addFormError;
    public $fullErr;


    public function __construct(
        $row = array(),
        $isactive = true,
        $addFormError = true,
        $fullErr = false,

    ) {
        $this->row = $row;
        $this->isactive = $isactive;
        $this->addFormError = $addFormError;
        $this->fullErr = $fullErr;

    }

    public function render(): View|Closure|string {
        return view('components.web.html.confirm-massage');
    }
}
