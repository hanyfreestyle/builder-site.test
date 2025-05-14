<?php

namespace App\View\Components\Web\Def;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Img extends Component {

    public bool $lazy;
    public string $type;
    public mixed $row;
    public string $rowName;
    public string $defPhoto;
    public string $defPhotoRow;
    public string $altRow;
    public string $class;
    public string $w;
    public string $h;

    public function __construct(
        $lazy = true,
        $type = 'Normal',
        $row = array(),
        $rowName = "photo_thumbnail",
        $defPhoto = 'logo',
        $defPhotoRow = 'photo_thumbnail',
        $altRow = "name",
        $class = '',
        $w = "",
        $h = "",
    ) {
        $this->lazy = $lazy;
        $this->type = $type;
        $this->row = $row;
        $this->rowName = $rowName;
        $this->defPhoto = $defPhoto;
        $this->defPhotoRow = $defPhotoRow;
        $this->altRow = $altRow;
        $this->class = $class;
        $this->w = $w;
        $this->h = $h;
    }

    public function render(): View|Closure|string {
        return view('components.web.def.img');
    }
}
