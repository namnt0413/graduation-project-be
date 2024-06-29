<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\ApplyController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\BookmarkController;
use App\Http\Controllers\Api\CVController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\ExpController;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

use App\Http\Controllers\CompanyAuth\CompanyAuthController;
use App\Http\Controllers\CompanyAuth\CompanyForgotPasswordController;
use App\Http\Controllers\CompanyAuth\CompanyResetPasswordController;

use App\Http\Controllers\GoogleMeetController;
use App\Http\Controllers\InterviewScheduleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthController::class,'login']);
Route::post('register', [AuthController::class,'register']);

Route::post('company-login', [CompanyAuthController::class,'login']);
Route::post('company-register', [CompanyAuthController::class,'register']);

Route::group(['middleware' => 'api'], function(){
    Route::post('logout',  [AuthController::class,'logout']);
    Route::post('refresh',  [AuthController::class,'refresh']);
    Route::post('me',  [AuthController::class,'me']);
    Route::get('user', [AuthController::class,'user']);

    Route::post('forgot-password',  [ForgotPasswordController::class,'sendEmail']);
    Route::post('reset-password', [ResetPasswordController::class,'passwordResetProcess']);

    Route::post('company-logout',  [CompanyAuthController::class,'logout']);
    Route::post('company-refresh',  [CompanyAuthController::class,'refresh']);
    Route::get('company', [CompanyAuthController::class,'company']);

    Route::post('company-forgot-password',  [CompanyForgotPasswordController::class,'sendEmail']);
    Route::post('company-reset-password', [CompanyResetPasswordController::class,'passwordResetProcess']);
});

Route::group([ 'as' => ''], function () {
    // city
    Route::apiResource('cities', \App\Http\Controllers\Api\CityController::class);
    Route::name('cities.')->prefix('city')->group(function () {
        Route::get('/all', [CityController::class, 'all'])->name('all');
    });
    //category
    Route::apiResource('categories', \App\Http\Controllers\Api\CategoryController::class);
    Route::name('categories.')->prefix('category')->group(function () {
        Route::get('/all', [CategoryController::class, 'all'])->name('all');
    });
    //position
    Route::apiResource('positions', \App\Http\Controllers\Api\PositionController::class);
    Route::name('positions.')->prefix('position')->group(function () {
        Route::get('/all', [PositionController::class, 'all'])->name('all');
    });
    //Exp
    Route::apiResource('exps', \App\Http\Controllers\Api\ExpController::class);
    Route::name('exps.')->prefix('exp')->group(function () {
        Route::get('/all', [ExpController::class, 'all'])->name('all');
    });

    //Job
    Route::name('job.')->prefix('job')->group(function () {
        Route::post('/create', [JobController::class, 'create'])->name('create');
        Route::put('/edit/{id}', [JobController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [JobController::class, 'delete'])->name('delete');
        Route::get('/detail/{id}', [JobController::class, 'detail'])->name('detail');
        Route::get('/get-all-jobs', [JobController::class, 'getAllJobs']);
        Route::get('get-company-jobs/{company_id}', [JobController::class, 'getCompanyJobs']);
        Route::get('filter-jobs', [JobController::class, 'filterJobs']);
        Route::put('/toggle-status-job', [JobController::class, 'toggleStatusJob']);
        Route::delete('/deleteJobs', [JobController::class, 'deleteJobs'])->name('deleteJobs');
        Route::get('/recommend-jobs', [JobController::class, 'recommendJobs']);
    });

    //Apply
    Route::name('apply.')->prefix('apply')->group(function () {
        Route::post('/create', [ApplyController::class, 'create'])->name('create');
        Route::delete('/delete/{id}', [ApplyController::class, 'delete'])->name('delete');
        Route::get('/list-by-job/{job_id}', [ApplyController::class, 'listByJob']);
        Route::get('/list-by-company/{company_id}', [ApplyController::class, 'listByCompany']);
        Route::get('/list-by-user/{user_id}', [ApplyController::class, 'listByUser']);
    });

    //User
    Route::name('user.')->prefix('user')->group(function () {
        Route::put('/update-profile', [UserController::class, 'updateProfile']);
        Route::get('filter-candidates', [UserController::class, 'filterCandidates']);
        Route::get('/get-finding-job-user', [UserController::class, 'getFindingJobUser']);
        Route::put('/toggle-is-finding-job', [UserController::class, 'toggleIsFindingJob']);
    });

    //Company
    Route::name('company.')->prefix('company')->group(function () {
        Route::put('/update-info', [CompanyController::class, 'updateInfo']);
        Route::get('/get-all-companies', [CompanyController::class, 'getAllCompanies']);
        Route::get('/detail/{id}', [CompanyController::class, 'detail'])->name('detail');
    });

    //Bookmark
    Route::name('bookmark.')->prefix('bookmark')->group(function () {
        Route::post('/create', [BookmarkController::class, 'create']);
        Route::post('/delete', [BookmarkController::class, 'delete']);
        Route::get('/list-candidates/{company_id}', [BookmarkController::class, 'listCandidates']);
    });

    //CV
    Route::name('cv.')->prefix('cv')->group(function () {
        Route::post('/create', [CVController::class, 'create']);
        Route::get('/detail/{id}', [CVController::class, 'detail'])->name('detail');
        Route::get('/get-list-cvs/{id}', [CVController::class, 'getListCvs'])->name('getListCvs');
        Route::delete('/delete-cvs', [CVController::class, 'deleteCvs'])->name('deleteCvs');

        // update basic info
        Route::put('/update-title/{id}', [CVController::class, 'updateTitle'])->name('updateTitle');
        Route::put('/update-name/{id}', [CVController::class, 'updateName'])->name('updateName');
        Route::put('/update-position/{id}', [CVController::class, 'updatePosition'])->name('updatePosition');
        Route::put('/update-email/{id}', [CVController::class, 'updateEmail'])->name('updateEmail');
        Route::put('/update-birthday/{id}', [CVController::class, 'updateBirthday'])->name('updateBirthday');
        Route::put('/update-phone/{id}', [CVController::class, 'updatePhone'])->name('updatePhone');
        Route::put('/update-address/{id}', [CVController::class, 'updateAddress'])->name('updateAddress');
        Route::put('/update-offset/{id}', [CVController::class, 'updateOffset'])->name('updateOffset');
        Route::put('/update-theme-color/{id}', [CVController::class, 'updateThemeColor'])->name('updateThemeColor');
        Route::put('/update-template/{id}', [CVController::class, 'updateTemplate'])->name('updateTemplate');
        Route::put('/update-avatar/{id}', [CVController::class, 'updateAvatar'])->name('updateAvatar');
        Route::put('/update-showing-avatar/{id}', [CVController::class, 'updateShowingAvatar'])->name('updateShowingAvatar');
    });

    //Subject
    Route::name('subject.')->prefix('subject')->group(function () {
        Route::post('/create', [SubjectController::class, 'create']);
        Route::get('/detail/{id}', [SubjectController::class, 'detail'])->name('detail');
        Route::put('/update-title/{id}', [SubjectController::class, 'updateSubjectTitle'])->name('updateSubjectTitle');
        Route::put('/update-offset/{id}', [SubjectController::class, 'updateOffset'])->name('updateOffset');
        Route::delete('/delete/{id}', [SubjectController::class, 'delete']);
    });

    //Item
    Route::name('item.')->prefix('item')->group(function () {
        Route::post('/create', [ItemController::class, 'create']);
        Route::get('/detail/{id}', [ItemController::class, 'detail'])->name('detail');
        Route::put('/update-title/{id}', [ItemController::class, 'updateItemTitle'])->name('updateItemTitle');
        Route::put('/update-content/{id}', [ItemController::class, 'updateItemContent'])->name('updateItemContent');
        Route::delete('/delete/{id}', [ItemController::class, 'delete']);
    });

});

//Interview
Route::name('schedule-interview.')->prefix('schedule-interview')->group(function () {
    Route::post('create', [InterviewScheduleController::class, 'create'])->name('schedule-interview.create');
});

Route::get('google/redirect', [GoogleMeetController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('oauth2callback', [GoogleMeetController::class, 'handleGoogleCallback'])->name('google.callback');
Route::post('schedule-interview', [GoogleMeetController::class, 'scheduleInterview'])->name('schedule.interview');
