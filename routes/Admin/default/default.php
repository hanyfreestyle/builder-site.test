<?php

use App\Http\Controllers\Admin\Core\MediaManagerController;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use Illuminate\Support\Str;


Route::prefix('admin/media/{model}/{relation}')->group(function () {
    Route::post('bulk-delete', [MediaManagerController::class, 'bulkDelete'])->name('filament.media.bulk-delete');
    Route::delete('{id}', [MediaManagerController::class, 'destroy'])->name('filament.media.delete');
    Route::post('{id}/toggle-status', [MediaManagerController::class, 'toggleStatus'])->name('filament.media.toggle-status');
    Route::post('reorder', [MediaManagerController::class, 'reorder'])->name('filament.media.reorder');
});

Route::get('/admin/user-guide-viewer/{slug?}', [MediaManagerController::class, 'show'])->name('filament.admin.pages.user-guide');

Route::post('/ckeditor/upload', function (Request $request) {
    $file = $request->file('upload');
    $filename = time() . '_' . Str::slug($file->getClientOriginalName());
    $path = $file->storeAs('uploads', $filename, 'public');

    return response()->json([
        'uploaded' => 1,
        'fileName' => $filename,
        'url' => asset("storage/$path")
    ]);
})->name('ckeditor.upload');
