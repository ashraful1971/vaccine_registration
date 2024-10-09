<?php

use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('register.index');
});

Route::get('/register', [RegistrationController::class, 'index'])->name('register.index');
Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');

Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::post('/search', [SearchController::class, 'check'])->name('search.check');
