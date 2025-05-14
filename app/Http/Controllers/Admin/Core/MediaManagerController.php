<?php

namespace App\Http\Controllers\Admin\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class MediaManagerController extends Controller {
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function destroy(Request $request, string $model, string $relation, int $id) {
        $media = $this->getMediaModel($model, $relation)::findOrFail($id);
        $media->delete();
        return response()->json(['success' => true]);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function bulkDelete(Request $request, string $model, string $relation) {
        $mediaModel = $this->getMediaModel($model, $relation);
        $media = $mediaModel::whereIn('id', $request->ids)->get();
        foreach ($media as $photo) {
            $photo->delete();
        }
        return response()->json(['success' => true]);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function toggleStatus(Request $request, string $model, string $relation, int $id) {
        $media = $this->getMediaModel($model, $relation)::findOrFail($id);
        $media->is_active = !$media->is_active;
        $media->save();

        return response()->json(['success' => true, 'active' => $media->is_active]);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function reorder(Request $request, string $model, string $relation) {
        $mediaModel = $this->getMediaModel($model, $relation);

        foreach ($request->items as $item) {
            $mediaModel::where('id', $item['id'])->update(['position' => $item['position']]);
        }

        return response()->json(['success' => true]);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected function getMediaModel(string $model, string $relation) {
        // خريطة الموديلات المسموح بها
        $modelMap = [
            'product' => \App\Models\Admin\Menu\Product::class,
            'RealEstateBlog' => \App\Models\RealEstate\BlogPost::class,
            'user_guide' => \App\Models\UserGuide\UserGuidePage::class,
            // ضيف أي موديل تاني هنا حسب الحاجة
        ];

        $relationMap = [
            'photos',
            'gallery',
        ];

        if (!array_key_exists($model, $modelMap)) {
            abort(403, "Unauthorized model: $model");
        }

        if (!in_array($relation, $relationMap)) {
            abort(403, "Unauthorized relation: $relation");
        }

        $modelClass = $modelMap[$model];

        $instance = new $modelClass;

        if (!method_exists($instance, $relation)) {
            abort(404, "Relation $relation not found on model $modelClass");
        }

        return $instance->{$relation}()->getRelated();
    }
}
