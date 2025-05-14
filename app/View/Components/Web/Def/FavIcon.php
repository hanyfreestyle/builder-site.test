<?php

namespace App\View\Components\Web\Def;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\View\Component;

class FavIcon extends Component {
    public string|null $folderName = null;
    public bool $isActive = true;

    public function __construct() {
        $this->folderName = config('appConfig.client_name');
        $favIconFolder = public_path($this->folderName . '/fav');
        if (!File::isDirectory($favIconFolder)) {
            $this->isActive = false;
        }
    }

    public function render(): View|Closure|string {
        return view('components.web.def.fav-icon');
    }
}
