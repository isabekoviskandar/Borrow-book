<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BorrowBookController;
use Illuminate\Support\Facades\Route;

// Public routes (authentication)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/', [BorrowBookController::class, 'index'])->name('dashboard');
    Route::get('/books/borrow/{id}', [BorrowBookController::class, 'borrow'])->name('books.borrow');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});