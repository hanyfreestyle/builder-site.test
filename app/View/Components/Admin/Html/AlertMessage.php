<?php

namespace App\View\Components\Admin\Html;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class AlertMessage extends Component {

    public $type;
    public $bg;
    public $align;
    public $mass;
    public $margin;

    public function __construct(
        $type = null,
        $bg = "p",
        $align = 'c',
        $mass = 'Text',
        $margin = ' mt-2 mb-2 ',

    ) {
        $this->type = $type;
        $this->bg = getBgColor($bg);
        $this->align = getAlign($align);
        $this->mass = $mass;
        $this->margin = $margin;


        if ($type) {
            switch ($type) {
                case 'nodata':
                    $this->bg = getBgColor('d');
                    $this->mass = __('admin/alertMass.no_data');
                    break;
                case 'delete':
                    break;
            }
        }
    }

    public function render(): View|Closure|string {
        return view('components.admin.html.alert-message');
    }
}
