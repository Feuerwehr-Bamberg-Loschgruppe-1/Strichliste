<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';

use App\Http\Controllers\UserController;

Route::post('/users/{user}/book-drink/{item}', [UserController::class, 'bookDrink']);
Route::post('/users/{user}/add-funds', [UserController::class, 'addFunds']);
