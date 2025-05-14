<?php

namespace App\Traits\Admin\Helper;


trait SmartResourceTrait {
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getNavigationSort(): int {
        return static::$navigationSort ?? static::getNavigationSortNumber();
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getTableRecordUrl($record): ?string {
        return static::getUrl('edit', ['record' => $record->getKey()]);
    }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function filterPermissions(array $skipKeys = [], array $keepKeys = [], array $addKeys = []): array {
        // 1. تحميل الصلاحيات من الكونفيج
        $defaultPermissions = config('filament-shield.permission_prefixes.resource', []);

        // 2. علاقات الحذف بناءً على مفاتيح skipKeys
        $relations = [
            'delete' => ['force_delete', 'force_delete_any'],
            'restore' => ['restore', 'restore_any'],
            'slug' => ['update_slug'],
            'view' => ['view'],
            'view_any' => ['view_any'],
        ];

        // 3. صلاحيات دايمًا بتتشال، إلا لو ذكرت في keepKeys
        $alwaysRemove = [
            'replicate' => ['replicate'],
            'sort' => ['reorder'],
            'publish' => ['publish'],
            'cat' => ['view_any_category'],
        ];

        // 4. اجمع صلاحيات الحذف من skipKeys
        $toRemoveByKey = collect($skipKeys)
            ->flatMap(fn($key) => $relations[$key] ?? [$key])
            ->toArray();

        // 5. احذف الافتراضيات إلا لو المفتاح موجود في keepKeys
        $toRemoveDefaults = collect($alwaysRemove)
            ->except($keepKeys)
            ->flatten()
            ->toArray();

        // 6. الدمج النهائي للصلاحيات اللي هتتحذف
        $toRemove = array_unique(array_merge($toRemoveByKey, $toRemoveDefaults));

        // 7. فلترة الصلاحيات
        $filtered = array_values(array_filter(
            $defaultPermissions,
            fn($permission) => !in_array($permission, $toRemove)
        ));

//        dd($addKeys);
        // 8. دمج الإضافات حسب المكان
        if (!empty($addKeys['keys'])) {
            $insertion = $addKeys['keys'];
            $position = $addKeys['placeIn'] ?? 'after';

            $filtered = match ($position) {
                'before' => array_merge($insertion, $filtered),
                'after' => array_merge($filtered, $insertion),
                default => $filtered,
            };
        }

        return $filtered;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function resolveDynamicLabel(string $label): ?string {
//        $routeCheck = request()->routeIs('filament.admin.resources.shield.roles.edit');
        $pathCheck = str(request()->path())->contains('shield/roles');
//        $panelCheck = Filament::getCurrentPanel()?->getId() === 'admin';

        if ($pathCheck) {
            $current = class_basename(static::class);

            return match (true) {
                $current === 'DefPhotoResource' => 'ادارة اعدادات الموقع',
                $current === 'RoleResource' => 'صلاحيات النظام',
                $current === 'BlogPostResource' => 'ادارة المقالات',
                default => $label,
            };
        }

        return $label;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getNavigationSortNumber(): int {
        $map = client_config('filament-navigation-map', true);
        $counter = 0;
        foreach ($map as $group => $resources) {
            foreach ($resources as $resourceClass) {
                if ($resourceClass === static::class) {
                    return $counter;
                }
                $counter++;
            }
        }
        return 9901;
    }
}
