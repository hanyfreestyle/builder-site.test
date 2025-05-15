<?php

namespace App\Filament\Admin\Pages\DevelopersTools;

use App\Traits\Admin\Helper\SmartResourceTrait;
use App\Traits\Admin\Migrations\ExportDatabaseTrait;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\DB;

class ListDatabaseTables extends Page {
    use SmartResourceTrait;
    use ExportDatabaseTrait;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';
//    protected static string $view = 'filament.admin.pages.developers-tools.list-database-tables';
    protected static string $view = 'filament.admin.pages.developers-tools.export-database';

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function canAccess(): bool {
        return isLocalSuperAdmin();
    }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    // ✅ جلب الجداول الموجودة حاليًا في قاعدة البيانات
    public function getAvailableTables(): array {
        // جلب جميع الجداول من قاعدة البيانات
        $databaseName = config('database.connections.mysql.database');
        $allTables = DB::select("SELECT TABLE_NAME FROM information_schema.tables WHERE table_schema = ?", [$databaseName]);
        $existingTables = array_map(fn($table) => $table->TABLE_NAME, $allTables);

        $defaultDatabaseList = self::DefaultDatabaseList();
        $removeListFromAll = self::removeListFromAll();
        $removeListFromClient = client_config('remove-db', true);
        $removeTables = array_merge($defaultDatabaseList,$removeListFromAll,$removeListFromClient);
        return array_values(array_diff($existingTables, $removeTables));

         return $existingTables;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    // ✅ جلب الجداول المحددة فقط
    public function getTables(): array {
        return $this->getAvailableTables();
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getNavigationGroup(): ?string {
        return __('developers-tools/fileList.navigation_group');
    }

    public static function getNavigationLabel(): string {
        return __('developers-tools/fileList.listDb.NavigationLabel');
    }

    public function getTitle(): string|Htmlable {
        return __('developers-tools/fileList.listDb.Title');
    }
}
