<?php

use App\Http\Controllers\Builder\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes for Site Builder
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the Site Builder frontend.
|
*/

// Homepage route
Route::get('/', [PageController::class, 'home'])->name('builder.home');

// Regular page route
Route::get('/{slug}', [PageController::class, 'page'])->name('builder.page');
