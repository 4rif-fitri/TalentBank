<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\internshipController;
use App\Http\Controllers\ProfileController;

// auth routes
Route::middleware('guest')->group(function () {
    // page routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('loginPage');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('registerPage');

    // crud routes
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
});

Route::middleware('auth')->get('/', [internshipController::class, "index"])->name('home');

Route::middleware('auth')->prefix('profile')->group(function () {
    // page routes

    // crud routes
    Route::get('/', [ProfileController::class, 'getProfileDataByUserIdJson'])->name('profile.getProfileDataByUserIdJson');
    Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
});

