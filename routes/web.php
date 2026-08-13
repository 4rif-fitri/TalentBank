<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\FacultyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\internshipController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\SemesterController;

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

Route::middleware('auth')->prefix('organizations')->group(function () {
    // page routes

    // crud routes
    Route::get('/', [OrganizationController::class, 'getAllOrganizationsJson'])->name('organization.getAllOrganizationsJson');
    Route::get('/types', [OrganizationController::class, 'getAllOrganizationTypesJson'])->name('organization.getAllOrganizationTypesJson');
    Route::get('/industry-categories', [OrganizationController::class, 'getAllIndustryCategoriesJson'])->name('organization.getAllIndustryCategoriesJson');
    Route::get('/industry-sectors', [OrganizationController::class, 'getAllIndustrySectorsJson'])->name('organization.getAllIndustrySectorsJson');
    Route::post('/store', [OrganizationController::class, 'store'])->name('organization.store');
    Route::put('/update', [OrganizationController::class, 'update'])->name('organization.update');
});

Route::middleware('auth')->prefix('faculties')->group(function () {
    // page routes

    // crud routes
    Route::get('/', [FacultyController::class, 'getFacultiesByOrgIdJson'])->name('faculty.getFacultiesByOrgIdJson');
    Route::get('/{id}', [FacultyController::class, 'getFacultyByIdJson'])->name('faculty.getFacultyByIdJson');
    Route::post('/store', [FacultyController::class, 'store'])->name('faculty.store');
    Route::put('/update/{id}', [FacultyController::class, 'update'])->name('faculty.update');
});

Route::middleware('auth')->prefix('programmes')->group(function () {
    // crud routes
    Route::get('/getProgrammesByUserIdJson/{userId}', [ProgrammeController::class, 'getProgrammesByUserIdJson'])->name('programme.getProgrammesByUserIdJson');
});

Route::middleware('auth')->prefix('semesters')->group(function () {
    // crud routes
    Route::post('/uploadResults/{id}', [SemesterController::class, 'uploadResults'])->name('semester.uploadResults');
});