<?php

namespace App\Traits\Admin\Migrations;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

trait ExportDatabaseTrait {
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function DefaultDatabaseList(): array {
        return [
            'user_guide', 'user_guide_lang', 'user_guide_photo', 'user_guide_photo_lang',
            'config_upload_filter', 'config_upload_filter_sizes', 'config_meta_tag', 'config_meta_tag_lang',
            'config_def_photos', 'config_setting', 'config_setting_lang', 'config_web_privacy','config_web_privacy_lang',
            'users', 'roles', 'permissions', 'model_has_permissions', 'model_has_roles', 'role_has_permissions', 'breezy_sessions', 'sessions',
        ];
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function removeListFromAll(): array {
        return [
            'cache', 'cache_locks','failed_jobs','jobs','job_batches','migrations','password_reset_tokens',
        ];
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    // ✅ جلب الجداول المحددة فقط
    public function getTables(): array {
        return $this->getAvailableTables();
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    // ✅ جلب الجداول الموجودة حاليًا في قاعدة البيانات
    public function getAvailableTables(): array {
        // جلب جميع الجداول من قاعدة البيانات
        $databaseName = config('database.connections.mysql.database');
        $allTables = DB::select("SELECT TABLE_NAME FROM information_schema.tables WHERE table_schema = ?", [$databaseName]);

        // تحويل نتيجة الاستعلام إلى مصفوفة أسماء الجداول
        $existingTables = array_map(fn($table) => $table->TABLE_NAME, $allTables);
        $allowedTables = $this->allowedTables();
        // تصفية الجداول بحيث تبقى فقط الجداول المسموح بها والتي توجد في قاعدة البيانات
        return array_values(array_intersect($allowedTables, $existingTables));
    }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||`
    // تصدير بيانات الجدول المحدد
    public function exportSelectedTables(array $selectedTables): void {
        $folderName = config('appConfig.client_name');
        if ($folderName != null) {
            $exportPath = public_path('db/' . $folderName);
        } else {
            $exportPath = public_path('db');
        }
        // ✅ إنشاء المجلد إذا لم يكن موجودًا
        if (!File::exists($exportPath)) {
            File::makeDirectory($exportPath, 0755, true);
        }

        foreach ($selectedTables as $table) {
            $data = DB::table($table)->get();

            // ✅ إعداد نص SQL الأساسي
            $sql = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
            $sql .= "START TRANSACTION;\n";
            $sql .= "SET time_zone = \"+00:00\";\n\n";

            if ($data->isNotEmpty()) {
                // ✅ إذا كان الجدول يحتوي على بيانات، إضافة INSERT INTO
                $columns = array_keys((array)$data->first());

                $sql .= "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES\n";

                $values = [];
                foreach ($data as $row) {
                    $rowData = array_map(function ($value) {
                        return is_null($value) ? 'NULL' : "'" . addslashes($value) . "'";
                    }, (array)$row);
                    $values[] = "(" . implode(', ', $rowData) . ")";
                }

                $sql .= implode(",\n", $values) . ";\n";
            }

            // ✅ إنهاء الملف بعملية COMMIT
            $sql .= "COMMIT;\n";

            file_put_contents("$exportPath/{$table}.sql", str_replace("\r\n", "\n", $sql));
        }

        session()->flash('success', 'تم تصدير الجداول المحددة بنجاح إلى مجلد db.');
    }


}
