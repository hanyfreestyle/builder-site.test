<?php
use Illuminate\Support\Facades\Storage;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('deleteEmptyFolders')) {
    function scanFolder() {
        $dir = Storage::disk("back_folder")->path('units');
        deleteEmptyFolders($dir);
    }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('deleteEmptyFolders')) {
    function deleteEmptyFolders($dir) {
        // اتأكد إن المسار فعلاً فولدر
        if (!is_dir($dir)) {
            return false;
        }
        // جيب كل الملفات والمجلدات داخل الفولدر
        $items = scandir($dir);
//        dd($items);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            // لو ده فولدر، نعمل له فحص تاني (recursively)
            if (is_dir($path)) {
                deleteEmptyFolders($path);
            }
        }
        // بعد ما نخلص الفحص، نشوف هل فاضي؟
        $remaining = array_diff(scandir($dir), ['.', '..']);
        if (empty($remaining)) {
            rmdir($dir);
        }
    }
}





