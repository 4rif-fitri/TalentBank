<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\FacultyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\internshipController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\JobOfferController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\ShortlistController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\SocialMediaLinkController;
use App\Http\Controllers\UserLanguageController;



/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| Here is where you can register authentication and authorization routes
| such as login, registration, password resets, and session management.
|
*/

Route::middleware('guest')->group(function () {
    // page routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('loginPage'); //+
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('registerPage'); //+

    // crud routes
    Route::post('/login', [LoginController::class, 'login'])->name('login'); //+
    Route::post('/register', [RegisterController::class, 'register'])->name('register'); //+
});

Route::middleware('auth')->post('/logout', [LoginController::class, 'logout'])->name('logout'); //+



/*
|--------------------------------------------------------------------------
| Page Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web page and view routes for your
| application. These routes are loaded by the RouteServiceProvider.
|
*/
Route::middleware('auth')->group(function () {
    Route::get('/', [internshipController::class, "index"])->name('home'); //+
    Route::get('/invitations', [internshipController::class, "invitations"])->name('invitations'); //+
    Route::get('/interviews', [internshipController::class, "interviews"])->name('interviews'); //+
    Route::get('/jobOffers', [internshipController::class, "jobOffers"])->name('jobOffers'); //+
    Route::get('/messages', [internshipController::class, "messages"])->name('messages'); //+
    Route::get('/settings', [internshipController::class, "settings"])->name('settings'); //+
    Route::get('/shortlist', [internshipController::class, "shortlists"])->name('shortlists'); //+
    Route::get('/talents', [internshipController::class, "talents"])->name('talents'); //+

    Route::prefix('profile')->group(function () {
        Route::get('/student', [internshipController::class, "profile"])->name('profile.student'); //+
        Route::get('/education', [internshipController::class, "education"])->name('profile.education'); //+
        Route::get('/experience', [internshipController::class, "experience"])->name('profile.experience'); //+
    });
});



/*
|--------------------------------------------------------------------------
| Backend API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
|
*/
Route::middleware(['auth', 'ajax', 'throttle:api'])->prefix('api')->group(function () {

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'getAllStudentUserProfiles'])->name('profile.getAllStudentUserProfiles');
        Route::get('/{id}', [ProfileController::class, 'getProfileDataByProfileId'])->name('profile.getProfileDataByProfileId');
        Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/update/about', [ProfileController::class, 'updateAboutField'])->name('update.updateAboutField');
        Route::post('/upload/profile-image', [ProfileController::class, 'uploadProfileImage'])->name('update.uploadProfileImage');
        Route::post('/upload/cover-image', [ProfileController::class, 'uploadCoverImage'])->name('update.uploadCoverImage');
        Route::post('/toggleLike', [ProfileController::class, 'toggleLike'])->name('profile.toggleLike');
    });

    Route::prefix('organizations')->group(function () {
        Route::get('/', [OrganizationController::class, 'getAllOrganizations'])->name('organization.getAllOrganizations'); //+
        Route::get('/types', [OrganizationController::class, 'getAllOrganizationTypes'])->name('organization.getAllOrganizationTypes'); //-
        Route::get('/industry-categories', [OrganizationController::class, 'getAllIndustryCategories'])->name('organization.getAllIndustryCategories'); //-
        Route::get('/industry-sectors', [OrganizationController::class, 'getAllIndustrySectors'])->name('organization.getAllIndustrySectors'); //-
        Route::post('/store', [OrganizationController::class, 'store'])->name('organization.store'); //-
        Route::middleware('checkRole:Organization Admin')->put('/update/{orgId}', [OrganizationController::class, 'update'])->name('organization.update'); //-
    });

    Route::prefix('faculties')->group(function () {
        Route::get('/org/{id}', [FacultyController::class, 'getFacultiesByOrgId'])->name('faculty.getFacultiesByOrgId'); //-
        Route::get('/{id}', [FacultyController::class, 'getFacultyById'])->name('faculty.getFacultyById'); //-
        Route::middleware('checkRole:Organization Admin')->post('/store', [FacultyController::class, 'store'])->name('faculty.store'); //-
        Route::middleware('checkRole:Organization Admin')->put('/update/{id}', [FacultyController::class, 'update'])->name('faculty.update'); //-
    });

    Route::prefix('programmes')->group(function () {
        Route::get('/getProgrammesByUserProfileId/{id}', [ProgrammeController::class, 'getProgrammesByUserProfileId'])->name('programme.getProgrammesByUserProfileId'); //!+
        Route::get('/getProgrammesByOrgId/{orgId}', [ProgrammeController::class, 'getProgrammesByOrgId'])->name('programme.getProgrammesByOrgId'); //+
    });

    Route::prefix('semesters')->group(function () {
        Route::post('/uploadResults/{id}', [SemesterController::class, 'uploadResults'])->name('semester.uploadResults'); //+
        Route::post('/store', [SemesterController::class, 'store'])->name('semester.store'); //+
        Route::put('/update/{id}', [SemesterController::class, 'update'])->name('semester.update'); //+
    });

    Route::prefix('education')->group(function () {
        Route::get('/getEducationByUserProfileId/{id}', [EducationController::class, 'getEducationByUserProfileId'])->name('education.getEducationByUserProfileId'); //+
        Route::get('/getEducationById/{id}', [EducationController::class, 'getEducationById'])->name('education.getEducationById'); //+
        Route::post('/store', [EducationController::class, 'store'])->name('education.store'); //+
        Route::put('/update/{id}', [EducationController::class, 'update'])->name('education.update'); //+
        Route::delete('/delete/{id}', [EducationController::class, 'delete'])->name('education.delete'); //+

        Route::get('/getAllFieldOfStudies', [EducationController::class, 'getAllFieldOfStudies'])->name('education.getAllFieldOfStudies'); //+
        Route::get('/getAllQualifications', [EducationController::class, 'getAllQualifications'])->name('education.getAllQualifications'); //+
    });

    Route::prefix('social-media')->group(function () {
        Route::get('/', [SocialMediaLinkController::class, 'getAllSocialMedia'])->name('social-media.getAllSocialMedia'); //+
        Route::post('/store', [SocialMediaLinkController::class, 'store'])->name('social-media.store'); //+
        Route::put('/update/{id}', [SocialMediaLinkController::class, 'update'])->name('social-media.update'); //+
        Route::delete('/delete/{id}', [SocialMediaLinkController::class, 'delete'])->name('social-media.delete'); //+
    });

    Route::prefix('languages')->group(function () {
        Route::get('/', [UserLanguageController::class, 'getAllLanguages'])->name('languages.getAllLanguages'); //+
        Route::post('/store', [UserLanguageController::class, 'store'])->name('languages.store'); //+
        Route::put('/update/{id}', [UserLanguageController::class, 'update'])->name('languages.update'); //+
        Route::delete('/delete/{id}', [UserLanguageController::class, 'delete'])->name('languages.delete'); //+
    });

    Route::prefix('skills')->group(function () {
        Route::get('/', [SkillController::class, 'getAllSkills'])->name('skills.getAllSkills');
        Route::post('/store', [SkillController::class, 'store'])->name('skills.store');
        Route::put('/update/{id}', [SkillController::class, 'update'])->name('skills.update');
        Route::delete('/delete/{id}', [SkillController::class, 'delete'])->name('skills.delete');
    });

    Route::prefix('positions')->group(function () {
        Route::get('/', [PositionController::class, 'getAllPositions'])->name('positions.getAllPositions');
        Route::get('/{id}', [PositionController::class, 'getPositionById'])->name('positions.getPositionById');
        Route::get('/org/{id}', [PositionController::class, 'getPositionsByOrgId'])->name('positions.getPositionsByOrgId');

        Route::middleware('checkRole:Organization Admin,Recruiter')->group(function () {
            Route::post('/store', [PositionController::class, 'store'])->name('positions.store');
            Route::put('/update/{id}', [PositionController::class, 'update'])->name('positions.update');
        });
    });

    Route::middleware('checkRole:Organization Admin,Recruiter')->prefix('shortlists')->group(function () {
        Route::post('/store', [ShortlistController::class, 'store'])->name('shortlists.store');
        Route::delete('/delete/{shortlistId}', [ShortlistController::class, 'delete'])->name('shortlists.delete');
    });

    Route::prefix('invitations')->group(function () {
        Route::get('/getInvitationsByReceiverId', [InvitationController::class, 'getInvitationsByReceiverId'])->name('invitations.getInvitationsByReceiverId');
        Route::get('/getInvitationById/{id}', [InvitationController::class, 'getInvitationById'])->name('invitations.getInvitationById');
        Route::put('/acceptInvitation/{id}', [InvitationController::class, 'acceptInvitation'])->name('invitations.acceptInvitation');
        Route::put('/rejectInvitation/{id}', [InvitationController::class, 'rejectInvitation'])->name('invitations.rejectInvitation');

        Route::middleware('checkRole:Organization Admin,Recruiter')->group(function () {
            Route::get('/status/{status}', [InvitationController::class, 'getInvitationsByStatusAndSenderId'])->name('invitations.getInvitationsByStatusAndSenderId');
            Route::get('/getInvitationsBySenderId', [InvitationController::class, 'getInvitationsBySenderId'])->name('invitations.getInvitationsBySenderId');
            Route::post('/store', [InvitationController::class, 'store'])->name('invitations.store');
            Route::put('/update/{id}', [InvitationController::class, 'update'])->name('invitations.update');
            Route::put('/withdrawInvitation/{id}', [InvitationController::class, 'withdrawInvitation'])->name('invitations.rejectInvitation');
        });
    });

    Route::prefix('interviews')->group(function () {
        Route::get('/getInterviewsByReceiverId', [InterviewController::class, 'getInterviewsByReceiverId'])->name('interviews.getInterviewsByReceiverId');
        Route::get('/getInterviewById/{id}', [InterviewController::class, 'getInterviewById'])->name('interviews.getInterviewById');

        Route::middleware('checkRole:Organization Admin,Recruiter')->group(function () {
            Route::get('/getInterviewsBySenderId', [InterviewController::class, 'getInterviewsBySenderId'])->name('interviews.getInterviewsBySenderId');
            Route::get('/status/{status}', [InterviewController::class, 'getInterviewsByStatus'])->name('interviews.getInterviewsByStatus');
            Route::post('/store', [InterviewController::class, 'store'])->name('interviews.store');
            Route::put('/update/{id}', [InterviewController::class, 'update'])->name('interviews.update');
            Route::put('/completeInterview/{id}', [InterviewController::class, 'completeInterview'])->name('interviews.completeInterview');
            Route::put('/cancelInterview/{id}', [InterviewController::class, 'cancelInterview'])->name('interviews.cancelInterview');
        });
    });

    Route::prefix('job-offers')->group(function () {
        Route::get('/getJobOffersByReceiverId', [JobOfferController::class, 'getJobOffersByReceiverId'])->name('jobOffers.getJobOffersByReceiverId');
        Route::get('/getJobOfferById/{id}', [JobOfferController::class, 'getJobOfferById'])->name('jobOffers.getJobOfferById');
        Route::get('/getJobOffersByStatus/{status}', [JobOfferController::class, 'getJobOffersByStatus'])->name('jobOffers.getJobOffersByStatus');

        Route::middleware('checkRole:Organization Admin,Recruiter')->group(function () {
            Route::get('/getJobOffersBySenderId', [JobOfferController::class, 'getJobOffersBySenderId'])->name('jobOffers.getJobOffersBySenderId');
            Route::post('/store', [JobOfferController::class, 'store'])->name('jobOffers.store');
            Route::put('/update/{id}', [JobOfferController::class, 'update'])->name('jobOffers.update');
            Route::put('/acceptJobOffer/{id}', [JobOfferController::class, 'acceptJobOffer'])->name('jobOffers.acceptJobOffer');
            Route::put('/rejectJobOffer/{id}', [JobOfferController::class, 'rejectJobOffer'])->name('jobOffers.rejectJobOffer');
            Route::put('/withdrawJobOffer/{id}', [JobOfferController::class, 'withdrawJobOffer'])->name('jobOffers.withdrawJobOffer');
        });
    });
});
