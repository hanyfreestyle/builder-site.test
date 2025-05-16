<?php
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Filament\Admin\Resources\UserGuide\UserGuideController;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

LoadRoutesFolder('routes/Admin');
LoadRoutesFolder('routes/Web');

// Include Site Builder routes
require __DIR__.'/builder.php';
