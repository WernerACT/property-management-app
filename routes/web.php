<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/user/login');
})->name('home');

Route::get('/clear-cache', function() {
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    return 'Done';
})->name('clear-cache');

Route::get('/cache', function() {
    Artisan::call('filament:optimize');
    Artisan::call('optimize');
    return 'Done cache';
})->name('cache');

Route::get('/foo', function () {
    Artisan::call('storage:link');
    return 'Done Link';
});

