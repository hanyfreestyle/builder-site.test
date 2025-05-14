<?php
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('setActiveMenu')) {
    function setActiveMenu($slug, $row):string {
        if ($slug == $row) {
            return 'active';
        }
        return '';
    }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('echobr')) {
    function echobr($text = "") {
        if ($text == "hr") {
            $text = '<hr/>';
        }
        echo $text . "<br/>";
    }
}
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('is_valid_icon_function')) {
    function is_valid_icon_function(?string $icon): ?string {
        if (!$icon) return null;

        $icon = str_replace(' ', '-', $icon);

        $base = base_path('vendor/owenvoke/blade-fontawesome/resources/svg');

        if (str_starts_with($icon, 'fas-')) {
            $path = $base . '/solid/' . str_replace('fas-', '', $icon) . '.svg';
        } elseif (str_starts_with($icon, 'fab-')) {
            $path = $base . '/brands/' . str_replace('fab-', '', $icon) . '.svg';
        } elseif (str_starts_with($icon, 'far-')) {
            $path = $base . '/regular/' . str_replace('far-', '', $icon) . '.svg';
        } else {
            return null;
        }

        return file_exists($path) ? $icon : null;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    if (!function_exists('getBgColor')) {
        function getBgColor($val) {
            switch ($val) {
                case 'def':
                    $sendColor = "default";
                    break;
                case 'dark':
                    $sendColor = "dark";
                    break;
                case 'p':
                    $sendColor = "primary";
                    break;
                case 'se':
                    $sendColor = "secondary";
                    break;
                case 's':
                    $sendColor = "success";
                    break;
                case 'i':
                    $sendColor = "info";
                    break;
                case 'd':
                    $sendColor = "danger";
                    break;
                case 'w':
                    $sendColor = "warning";
                    break;
                case 'l':
                    $sendColor = "light";
                    break;
                default:
                    $sendColor = "dark";
            }
            return $sendColor;
        }
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    if (!function_exists('getAlign')) {
        function getAlign($val) {
            $sendStyle = "";
            if ($val == 'c') {
                $sendStyle = "center";
            } elseif ($val == 'r') {
                $sendStyle = "right";
            } elseif ($val == 'l') {
                $sendStyle = "left";
            }
            return $sendStyle;
        }
    }
}
