<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('admin/settings', function () {
    return redirect(route('filament.admin.pages.settings.general-settings'));
})->name('default-settings');

Route::get('/command/{command}', function ($command) {
    $output = Artisan::call($command);

    return nl2br($output);
});

Route::get('/optimize', function () {
    $output = Artisan::call('optimize');

    return back();
})->name('optimize');

Route::get('/optimize/clear', function () {
    $output = Artisan::call('optimize:clear');

    return back();
})->name('optimize:clear');

