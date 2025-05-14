<?php
use Illuminate\Support\Facades\Storage;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('isLocalSuperAdmin')) {
    function isLocalSuperAdmin(): bool {
        $user = auth()->user();
        return config('app.env') === 'local' && $user->hasRole('super_admin');
    }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getImageDirForPdf')) {
    function getImageDirForPdf($row): string {
        if (config('app.env') === 'local' and $row){
            $img = public_path('images/'.$row);
        }else{
            $img = Storage::disk('root_folder')->url($row);
        }
        return $img;
    }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('cashDay')) {
    function cashDay($days = 2) {
        $lifeTime = $days * (86400);
        return $lifeTime;
    }
}

