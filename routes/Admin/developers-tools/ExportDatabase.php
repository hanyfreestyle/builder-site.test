<?php


use App\Filament\Admin\Pages\DevelopersTools\ExportDatabase;
use Illuminate\Support\Facades\Route;

Route::post('/export-selected-tables', function () {
    $page = new ExportDatabase();
    $selectedTables = request()->input('tables', []);

    // ✅ التحقق إذا لم يتم اختيار أي جدول
    if (empty($selectedTables)) {
        return redirect()->back()->with('error', 'يرجى تحديد جدول واحد على الأقل للتصدير.');
    }
    // ✅ تصدير الجداول المحددة
    $page->exportSelectedTables($selectedTables);

    return redirect()->back()->with('success', 'تم تصدير الجداول المحددة بنجاح!');
})->name('filament.pages.database-tables-export.export');
