<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;


// Language Change
Route::get('/lang/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'sw'])) {
        Session::put('locale', $lang);
        Log::info("Language switched to: $lang");
    } else {
        Log::info("Invalid language attempt: $lang");
    }
    return redirect()->back();
});


// Website routes
require __DIR__.'/website.php';

// Admin routes
require __DIR__.'/admin.php';

