<?php

use App\Http\Controllers\Builder\PageController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes for Site Builder
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the Site Builder frontend.
|
*/

// Wrap all routes in LaravelLocalization prefix and middleware
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['web', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function() {
    // Homepage route
    Route::get('/', [PageController::class, 'home'])->name('builder.home');

    // Regular page route
    Route::get('/{slug}', [PageController::class, 'page'])->name('builder.page');
});
