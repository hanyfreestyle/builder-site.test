<?php

use App\Models\WebSetting\DefPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::delete('def-photos/{record}/delete', function (DefPhoto $record) {
    $record->delete();

    return redirect()->back()->with('success', __('Deleted successfully.'));
})->name('filament.admin.custom.def-photos.destroy');

Route::post('/def-photos/sort', function (Request $request) {
    $ids = $request->input('ids');

    foreach ($ids as $index => $id) {
        DefPhoto::where('id', $id)->update(['position' => $index]);
    }

    return response()->json(['status' => 'success']);
})->name('filament.admin.custom.def-photos.sort');
