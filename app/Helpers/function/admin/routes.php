<?php

use Illuminate\Support\Facades\File;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('LoadRoutesFolder')) {
    function LoadRoutesFolder(string $folderName) {
        $folderPath = base_path($folderName);
        if (File::isDirectory($folderPath)) {
            foreach (File::allFiles($folderPath) as $file) {
                if ($file->getExtension() === 'php') {
                    require $file->getRealPath();
                }
            }
        }
    }
}
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('buildLocationTree')) {
    function buildLocationTree($items, $parentId = null) {
        return $items->filter(function ($item) use ($parentId) {
            return $item->parent_id === $parentId;
        })->map(function ($item) use ($items) {
            $item->children = buildLocationTree($items, $item->id);
            return $item;
        });
    }
}
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('flattenLocationTreeForSelect')) {
    function flattenLocationTreeForSelect($tree, $prefix = '') {
        $options = collect();

        foreach ($tree as $item) {
            $name = $prefix . optional($item->translation)->name;
            $options->put($item->id, $name);

            if ($item->children->isNotEmpty()) {
                $options = $options->merge(flattenLocationTreeForSelect($item->children, $name . ' => '));
            }
        }
        return $options;
    }
}









