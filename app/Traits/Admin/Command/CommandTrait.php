<?php

namespace App\Traits\Admin\Command;

use Illuminate\Support\Facades\Schema;

trait CommandTrait {
    protected array $ignoreFields = [];
    protected bool $reWrite = false;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function getIgnoreFields(): array {
        return [
            'id', 'slug', 'icon', 'photo', 'photo_thumbnail', 'is_active', 'deleted_at', 'created_at', 'updated_at',
            'position', 'XXXXX', 'XXXXX', 'XXXXX', 'XXXXX', 'XXXXX',
        ];
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function getTableColumns($model): array {
        try {
            return Schema::getColumnListing($model->getTable());
        } catch (\Throwable $e) {
            $this->error("Unable to get table columns for: " . $model->getTable());
            return [];
        }
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function getTableColumnsList($model): array {
        return array_filter(Schema::getColumnListing($model), function ($column) {
            return !in_array($column, ['id', 'created_at', 'updated_at', 'deleted_at']);
        });
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function generateParentRelations(string $className, array $columns): string {
        if (!in_array('parent_id', $columns)) {
            return '';
        }
        return <<<EOT
    public function parent() : BelongsTo {
        return \$this->belongsTo({$className}::class, 'parent_id');
    }

    public function children() : HasMany {
        return \$this->hasMany({$className}::class, 'parent_id');
    }
    EOT;
    }

}
