<?php
return [
    'def_country_id' => env('def_country', '169'),
    'load_from_remote_db' => env('LOAD_FROM_REMOTE_DB', true),
    'client_name' => env('CLIENT_NAME', null),
    'rootFolder' => env('ROOT_FOLDER', 'root_folder'),
    'uploadFilter' => [
        'watermark_text' => false,
    ],

];
