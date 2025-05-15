<?php


return [

    "TemplateResource" => [
        \App\Filament\Admin\Resources\SiteBuilder\TemplateResource::class,
    ],

    "manageData" => [
        \App\Filament\Admin\Resources\RealEstate\LocationResource::class,
        \App\Filament\Admin\Resources\RealEstate\AmenityResource::class,
        \App\Filament\Admin\Resources\RealEstate\DataProjectTypeResource::class,
        \App\Filament\Admin\Resources\RealEstate\DataProjectStatusResource::class,
        \App\Filament\Admin\Resources\RealEstate\DataUnitTypeResource::class,
        \App\Filament\Admin\Resources\RealEstate\DataUnitViewResource::class,
        \App\Filament\Admin\Resources\Data\DataCountryResource::class,
    ],

    "webSettings" => [
        \App\Filament\Admin\Pages\WebSetting\SiteSettings::class,
        \App\Filament\Admin\Pages\WebSetting\ModelsSettings::class,
        \App\Filament\Admin\Resources\WebSetting\DefPhotoResource::class,
        \App\Filament\Admin\Resources\WebSetting\MetaTagResource::class,
        \App\Filament\Admin\Resources\WebSetting\UploadFilterResource::class,
        \App\Filament\Admin\Resources\WebSetting\WebPrivacyResource::class,
    ],

    "roles" => [
        \App\Filament\Admin\Resources\UserResource::class,
    ],

    "adminTools" => [
        \App\Filament\Admin\Resources\DevelopersTools\FilesListResource::class,
        \App\Filament\Admin\Resources\DevelopersTools\FilesListGroupResource::class,
        \App\Filament\Admin\Pages\DevelopersTools\BackUpFile::class,
        \App\Filament\Admin\Pages\DevelopersTools\ExportDatabase::class,
        \App\Filament\Admin\Pages\DevelopersTools\ListDatabaseTables::class,
    ],

];
