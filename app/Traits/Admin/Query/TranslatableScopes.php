<?php

namespace App\Traits\Admin\Query;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait TranslatableScopes {

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function scopeOrderByCurrentLocaleTranslation(Builder $query, string $column, string $direction = 'asc') {
        $mainTable = $this->getTable(); // $table
        $translationTable = $this->translations()->getRelated()->getTable(); // $table_translations
        $foreignKey = $this->translationForeignKey; // country_id

        return $query->select("{$mainTable}.*")
            ->leftJoin($translationTable, function ($join) use ($mainTable, $translationTable, $foreignKey) {
                $join->on("{$mainTable}.id", '=', "{$translationTable}.{$foreignKey}")
                    ->where("{$translationTable}.locale", app()->getLocale());
            })
            ->orderBy("{$translationTable}.{$column}", $direction);
    }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function scopeWhereCurrentLocaleTranslationLike(Builder $query, string $column, string $search) {
        return $query->whereHas('translations', function (Builder $query) use ($column, $search) {
            $query->where($column, 'LIKE', "%{$search}%")
                ->where('locale', app()->getLocale());
        });
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getTranslatedQuery($TableName, $TableTranslations, $ForeignKey) {
        return DB::table("$TableName")
            ->select("$TableName.id", "$TableTranslations.name as name")
            ->join("$TableTranslations", function ($join) use ($TableName, $TableTranslations, $ForeignKey) {
                $join->on("$TableTranslations.$ForeignKey", '=', "$TableName.id")
                    ->where("$TableTranslations.locale", '=', app()->getLocale());
            })
            ->orderBy("$TableName.id")
            ->pluck('name', 'id');
    }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getTranslatedQueryFilter($TableName, $TableTranslations, $ForeignKey, $filterName, $filterValue) {
        return DB::table("$TableName")
            ->select("$TableName.id", "$TableTranslations.name as name")
            ->where("$TableName.$filterName", $filterValue)
            ->join("$TableTranslations", function ($join) use ($TableName, $TableTranslations, $ForeignKey) {
                $join->on("$TableTranslations.$ForeignKey", '=', "$TableName.id")
                    ->where("$TableTranslations.locale", '=', app()->getLocale());
            })
            ->orderBy("$TableName.id")
            ->pluck('name', 'id');
    }

}
