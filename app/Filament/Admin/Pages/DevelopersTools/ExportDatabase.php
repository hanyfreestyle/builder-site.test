<?php

namespace App\Filament\Admin\Pages\DevelopersTools;

use App\Traits\Admin\Helper\SmartResourceTrait;
use App\Traits\Admin\Migrations\ExportDatabaseTrait;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;


class ExportDatabase extends Page {
    use SmartResourceTrait;
    use ExportDatabaseTrait;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';
    protected static string $view = 'filament.admin.pages.developers-tools.export-database';

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function canAccess(): bool {
        return isLocalSuperAdmin();
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function allowedTables(): array {
        return self::DefaultDatabaseList();
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function getClientTable() {

        $folderName = config('appConfig.client_name');
        $clientArr = [];

        if ($folderName == 'quiz') {
            $clientArr = [
                'app_data_class', 'app_data_class_lang', 'app_data_subject', 'app_data_subject_lang', 'app_data_section',
                'app_data_section_lang', 'app_data_term', 'app_data_term_lang', 'app_data_unit', 'app_data_unit_lang',
                'app_data_revision', 'app_data_revision_lang',
            ];
        }
        if ($folderName == 'SchoolDir') {
            $clientArr = [
                'data_gender', 'data_gender_lang', 'data_school_halkat', 'data_school_halkat_lang', 'data_school_hour', 'data_school_hour_lang',
                'data_school_type', 'data_school_type_lang', 'data_village', 'data_village_translations',
                'dir_school', 'dir_school_contact', 'dir_school_google', 'dir_school_google_response', 'dir_school_lang',
                'dir_stage', 'dir_stage_lang', 'dir_stage_pivot',
            ];
        }

        if ($folderName == 'realestate-eg') {
            $clientArr = [
                'config_upload_filter', 'config_upload_filter_sizes', 'config_meta_tag', 'config_meta_tag_lang',
            ];
        }
        if ($folderName == 'on-fire') {
            $clientArr = [

            ];
        }



        return $clientArr;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getNavigationGroup(): ?string {
        return __('developers-tools/fileList.navigation_group');
    }

    public static function getNavigationLabel(): string {
        return __('developers-tools/fileList.exportDb.NavigationLabel');
    }

    public function getTitle(): string|Htmlable {
        return __('developers-tools/fileList.exportDb.Title');
    }


}
