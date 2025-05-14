<?php

namespace App\FilamentCustom\Form\Select;

use Filament\Forms\Components\Select;

class SelectCategoryTree extends Select {
    protected string $modelClass;


    protected function setUp(): void {
        parent::setUp();
        $this
            ->preload()
            ->searchable()
            ->options(fn() => $this->getCategoryOptions());

    }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function fromModel(string $class): static {
        $this->modelClass = $class;

        return $this;
    }

    protected function getCategoryOptions(): array {
        if (!isset($this->modelClass)) {
            return [];
        }

        /** @var Model $model */
        $model = new $this->modelClass;

        // Get record if exists
        $record = $this->getContainer()?->getRecord();

        // إذا كان تعديل، استبعد الـ record وأولاده
        if ($record) {
            $excludedIds = $this->modelClass::find($record->id)
                    ?->descendantsAndSelf()
                    ?->pluck('id')
                    ?->toArray() ?? [];

            $items = $this->modelClass::with('translation')
                ->whereNotIn('id', $excludedIds)
                ->get();
        } else {
            $items = $this->modelClass::with('translation')->get();
        }

        $tree = $this->buildTree($items);
        $options = $this->flattenTree($tree);

        // ضيف أول اختيار "مجموعة رئيسية"
        return ['' => 'مجموعة رئيسية'] + $options;
    }

    protected function buildTree($items, $parentId = null) {
        return $items->filter(fn($item) => $item->parent_id === $parentId)->map(function ($item) use ($items) {
            $item->children = $this->buildTree($items, $item->id);
            return $item;
        });
    }

    protected function flattenTree($tree, $prefix = '') {
        $options = [];

        foreach ($tree as $item) {
            $name = $prefix . optional($item->translation)->name;
            $options[$item->id] = $name;

            if ($item->children->isNotEmpty()) {
                $options += $this->flattenTree($item->children, $name . ' - ');
            }
        }

        return $options;
    }

}
