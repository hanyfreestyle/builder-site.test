<?php

namespace App\Traits\Admin\Helper;


use Illuminate\Support\Facades\DB;

trait LoadOldData {


    public function prepareColumnMapping(
        string $oldTable,
        string $newTable,
        array  $columnMap = [],
        array  $fallbacks = []
    ): array {
        $oldColumns = DB::connection('secondary_db')->getSchemaBuilder()->getColumnListing($oldTable);
        $newColumns = DB::getSchemaBuilder()->getColumnListing($newTable);

        $finalMapping = [];
        $unmatched = [];
        $debug = [];

        foreach ($newColumns as $targetCol) {
            if (in_array($targetCol, $oldColumns)) {
                $finalMapping[$targetCol] = $targetCol;
                $debug[$targetCol] = '✅ direct match (same name)';
            } elseif (isset($columnMap[$targetCol]) && in_array($columnMap[$targetCol], $oldColumns)) {
                $finalMapping[$targetCol] = $columnMap[$targetCol];
                $debug[$targetCol] = "✅ mapped from '{$columnMap[$targetCol]}'";
            } elseif (array_key_exists($targetCol, $fallbacks)) {
                $fallbackVal = $fallbacks[$targetCol];

                if ($fallbackVal === null) {
                    $debug[$targetCol] = "⚠️ fallback is null → ignored (will use DB default)";
                    // ما بنضيفوش في finalMapping
                } else {
                    $finalMapping[$targetCol] = $fallbackVal;
                    $debug[$targetCol] = "✅ fallback used (static value)";
                }
            } else {
                $unmatched[$targetCol] = null;
                $debug[$targetCol] = "❌ not matched - missing fallback";
            }
        }

        if (count($unmatched) > 0) {
            dd([
                '🧠 Matching decisions (debug)' => $debug,
                '🚨 Unmatched columns (need manual handling)' => $unmatched,
                '🧾 Final Mapping (for insertUsing)' => $finalMapping,
            ]);
        } else {

            return $finalMapping;
        }

    }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function loadDb($mapping,$oldTable,$newTable,$test=true) {
        if($test){
            dd($mapping);
        }else{
            $columns = array_keys($mapping);
            $selects = array_values($mapping);
            DB::table($newTable)->insertUsing(
                $columns,
                DB::connection('secondary_db')->table($oldTable)->select($selects)
            );
        }
    }



//    public function prepareColumnMapping(
//        string $oldTable,
//        string $newTable,
//        array  $columnMap = [],
//        array  $fallbacks = []
//    ): array {
//        $oldColumns = DB::connection('secondary_db')->getSchemaBuilder()->getColumnListing($oldTable);
//        $newColumns = DB::getSchemaBuilder()->getColumnListing($newTable);
//
//        $finalMapping = [];
//        $unmatched = [];
//        $debug = [];
//
//        foreach ($newColumns as $targetCol) {
//            if (in_array($targetCol, $oldColumns)) {
//                $finalMapping[$targetCol] = $targetCol;
//                $debug[$targetCol] = '✅ direct match (same name)';
//            }
//
//            // ✅ تعديل هنا: استخدمنا المفتاح مباشرة
//            elseif (isset($columnMap[$targetCol]) && in_array($columnMap[$targetCol], $oldColumns)) {
//                $finalMapping[$targetCol] = $columnMap[$targetCol];
//                $debug[$targetCol] = "✅ mapped from '{$columnMap[$targetCol]}'";
//            }
//
//            else {
//                $finalMapping[$targetCol] = $fallbacks[$targetCol] ?? null;
//                $unmatched[$targetCol] = $fallbacks[$targetCol] ?? null;
//                $debug[$targetCol] = "❌ not matched - fallback used";
//            }
//        }
//
//        dd([
//            '🟢 New Table Columns (target)' => $newColumns,
//            '🔵 Old Table Columns (source)' => $oldColumns,
//            '🟡 columnMap' => $columnMap,
//            '🧠 Matching decisions (debug)' => $debug,
//            '🚨 Unmatched columns (need manual handling)' => $unmatched,
//            '🧾 Final Mapping' => $finalMapping,
//        ]);
//
//        return $finalMapping;
//    }

//    public function transferDataWithValidation(
//        string|array $oldTable,
//        string       $newTable,
//        array        $columnMap = [],
//        array        $fallbacks = [] // ✅ هنا نحط القيم الافتراضية أو مسارات بديلة
//    ) {
//        // 1. Get data
//        $oldData = is_string($oldTable)
//            ? DB::connection('secondary_db')->table($oldTable)->get()
//            : collect($oldTable);
//
//        if ($oldData->isEmpty()) {
//            dd("No data found in old source.");
//        }
//
//        $oldFirstRow = (array)$oldData->first();
//        $oldKeys = array_keys($oldFirstRow);
//        $newTableColumns = DB::getSchemaBuilder()->getColumnListing($newTable);
//
//        // 2. Apply columnMap
//        $mappedOldKeys = collect($oldKeys)->map(function ($col) use ($columnMap) {
//            return $columnMap[$col] ?? $col;
//        })->toArray();
//
//        // 3. Detect missing columns in new table
//        $missingInNewTable = array_diff($mappedOldKeys, $newTableColumns);
//
//        if (!empty($missingInNewTable)) {
//            // ✅ Build array of fallback suggestions
//            $fallbackSuggestions = [];
//            foreach ($oldKeys as $key) {
//                $mapped = $columnMap[$key] ?? $key;
//                if (in_array($mapped, $missingInNewTable)) {
//                    $fallbackSuggestions[$mapped] = null; // default null
//                }
//            }
//
//            dd("⚠️ Missing columns in new table. Please provide fallbacks:", $fallbackSuggestions);
//        }
//
//        // 4. Insert data
//        foreach ($oldData as $row) {
//            $newRow = [];
//
//            foreach ($oldKeys as $oldKey) {
//                $newKey = $columnMap[$oldKey] ?? $oldKey;
//
//                if (in_array($newKey, $newTableColumns)) {
//                    $newRow[$newKey] = $row->$oldKey ?? null;
//                } // ✅ Check if this field has fallback
//                elseif (isset($fallbacks[$newKey])) {
//                    $fallbackValue = $fallbacks[$newKey];
//
//                    if (is_string($fallbackValue) && isset($row->$fallbackValue)) {
//                        $newRow[$newKey] = $row->$fallbackValue; // use value from another column
//                    } else {
//                        $newRow[$newKey] = $fallbackValue; // use static value (null, false, etc.)
//                    }
//                }
//            }
//
//            DB::table($newTable)->insert($newRow);
//        }
//
//        echo "✅ Data transferred successfully.";
//    }

}
