<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\FacultyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\internshipController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\SocialMediaLinkController;

// auth routes
Route::middleware('guest')->group(function () {
    // page routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('loginPage'); //+
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('registerPage'); //+

    // crud routes
    Route::post('/login', [LoginController::class, 'login'])->name('login'); //+
    Route::post('/register', [RegisterController::class, 'register'])->name('register'); //+
});

Route::middleware('auth')->get('/', [internshipController::class, "index"])->name('home'); //+
Route::middleware('auth')->get('/invitations', [internshipController::class, "invitations"])->name('invitations'); //+
Route::middleware('auth')->get('/interviews', [internshipController::class, "interviews"])->name('interviews'); //+
Route::middleware('auth')->get('/jobOffers', [internshipController::class, "jobOffers"])->name('jobOffers'); //+
Route::middleware('auth')->get('/messages', [internshipController::class, "messages"])->name('messages'); //+
Route::middleware('auth')->get('/settings', [internshipController::class, "settings"])->name('settings'); //+

Route::middleware('auth')->post('/logout', [LoginController::class, 'logout'])->name('logout'); //+

Route::middleware('auth')->prefix('profile')->group(function () {
    // page routes
    Route::get('/student', [internshipController::class, "profile"])->name('profile.student'); //+
    Route::get('/education', [internshipController::class, "education"])->name('profile.education'); //+
    Route::get('/experience', [internshipController::class, "experience"])->name('profile.experience'); //+

    // crud routes
    Route::get('/{id}', [ProfileController::class, 'getProfileDataByProfileId'])->name('profile.getProfileDataByProfileId');
    Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/update/about', [ProfileController::class, 'updateAboutField'])->name('update.updateAboutField');
    Route::post('/upload/profile-image', [ProfileController::class, 'uploadProfileImage'])->name('update.uploadProfileImage');
    Route::post('/upload/cover-image', [ProfileController::class, 'uploadCoverImage'])->name('update.uploadCoverImage');
});

Route::middleware('auth')->prefix('organizations')->group(function () {
    // crud routes
    Route::get('/', [OrganizationController::class, 'getAllOrganizations'])->name('organization.getAllOrganizations'); //+
    Route::get('/types', [OrganizationController::class, 'getAllOrganizationTypes'])->name('organization.getAllOrganizationTypes'); //-
    Route::get('/industry-categories', [OrganizationController::class, 'getAllIndustryCategories'])->name('organization.getAllIndustryCategories'); //-
    Route::get('/industry-sectors', [OrganizationController::class, 'getAllIndustrySectors'])->name('organization.getAllIndustrySectors'); //-
    Route::post('/store', [OrganizationController::class, 'store'])->name('organization.store'); //-
    Route::put('/update/{orgId}', [OrganizationController::class, 'update'])->name('organization.update'); //-
});

Route::middleware('auth')->prefix('faculties')->group(function () {
    // crud routes
    Route::get('/org/{id}', [FacultyController::class, 'getFacultiesByOrgId'])->name('faculty.getFacultiesByOrgId'); //-
    Route::get('/{id}', [FacultyController::class, 'getFacultyById'])->name('faculty.getFacultyById'); //-
    Route::post('/store', [FacultyController::class, 'store'])->name('faculty.store'); //-
    Route::put('/update/{id}', [FacultyController::class, 'update'])->name('faculty.update'); //-
});

Route::middleware('auth')->prefix('programmes')->group(function () {
    // crud routes
    Route::get('/getProgrammesByUserProfileId/{id}', [ProgrammeController::class, 'getProgrammesByUserProfileId'])->name('programme.getProgrammesByUserProfileId'); //!+
    Route::get('/getProgrammesByOrgId/{orgId}', [ProgrammeController::class, 'getProgrammesByOrgId'])->name('programme.getProgrammesByOrgId'); //+
});

Route::middleware('auth')->prefix('semesters')->group(function () {
    // crud routes
    Route::post('/uploadResults/{id}', [SemesterController::class, 'uploadResults'])->name('semester.uploadResults'); //+
    Route::post('/store', [SemesterController::class, 'store'])->name('semester.store');
    Route::put('/update/{id}', [SemesterController::class, 'update'])->name('semester.update');
});

Route::middleware('auth')->prefix('education')->group(function () {
    // crud routes
    Route::get('/getEducationByUserProfileId/{id}', [EducationController::class, 'getEducationByUserProfileId'])->name('education.getEducationByUserProfileId'); //+
    Route::get('/getEducationById/{id}', [EducationController::class, 'getEducationById'])->name('education.getEducationById'); //+
    Route::post('/store', [EducationController::class, 'store'])->name('education.store'); //+
    Route::put('/update/{id}', [EducationController::class, 'update'])->name('education.update'); //+
    Route::delete('/delete/{id}', [EducationController::class, 'delete'])->name('education.delete'); //+

    Route::get('/getAllFieldOfStudies', [EducationController::class, 'getAllFieldOfStudies'])->name('education.getAllFieldOfStudies'); //+
    Route::get('/getAllQualifications', [EducationController::class, 'getAllQualifications'])->name('education.getAllQualifications'); //+
});

Route::middleware('auth')->prefix('social-media')->group(function () {
    Route::get('/', [SocialMediaLinkController::class, 'getAllSocialMedia'])->name('social-media.getAllSocialMedia'); //-
    Route::post('/store', [SocialMediaLinkController::class, 'store'])->name('social-media.store'); //-
    Route::put('/update/{id}', [SocialMediaLinkController::class, 'update'])->name('social-media.update'); //-
    Route::delete('/delete/{id}', [SocialMediaLinkController::class, 'delete'])->name('social-media.delete'); //-
});
