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

// Language switcher route
Route::get('/switch-language/{locale}', function ($locale) {
    $redirect = request()->input('redirect', '/');
    
    // Check if the locale is supported
    $supportedLocales = ['en', 'ar', 'fr', 'es', 'de']; // Update this with your supported locales
    if (!in_array($locale, $supportedLocales)) {
        $locale = 'en'; // Default to English if locale is not supported
    }
    
    // Set the locale
    app()->setLocale($locale);
    session()->put('locale', $locale);
    
    // Redirect back to the previous page
    return redirect($redirect);
})->name('builder.switch-language');

// Homepage route
Route::get('/', [PageController::class, 'home'])->name('builder.home');

// Regular page route
Route::get('/{slug}', [PageController::class, 'page'])->where('slug', '(?!switch-language).*')->name('builder.page');
