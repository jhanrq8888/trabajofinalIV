<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('template');
});
Route::get('/login', function () {
    return view('auth.login');
});
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
