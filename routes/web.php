<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/' , [IndexController::class , 'index']);

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});

Route::get('/books', [App\Http\Controllers\IndexController::class, 'index'])->name('books.index');
Route::middleware('auth')->post('/books/{book}/borrow', [App\Http\Controllers\IndexController::class, 'borrow'])->name('books.borrow');