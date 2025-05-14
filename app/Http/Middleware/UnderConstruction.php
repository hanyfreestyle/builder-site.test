<?php

namespace App\Http\Middleware;


use App\Traits\Web\LoadWebSettings;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UnderConstruction {
    use LoadWebSettings;

    public function handle(Request $request, Closure $next): Response {
        $config = self::getWebSettingsCash();
        if ($config->web_status != 1) {
            if (!Auth::user()) {
                return redirect()->route('UnderConstruction');
            }
        }
        return $next($request);
    }
}
