<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CompanyAuth\CompanyAuthenticatedSessionController;
use App\Http\Controllers\CompanyAuth\CompanyEmailVerificationNotificationController;
use App\Http\Controllers\CompanyAuth\CompanyNewPasswordController;
use App\Http\Controllers\CompanyAuth\CompanyPasswordResetLinkController;
use App\Http\Controllers\CompanyAuth\CompanyRegisteredUserController;
use App\Http\Controllers\CompanyAuth\CompanyVerifyEmailController;

Route::post('/register', [RegisteredUserController::class, 'store'])
                ->middleware('guest')
                ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware('guest')
                ->name('login');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->middleware('guest')
                ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
                ->middleware('guest')
                ->name('password.update');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['auth', 'signed', 'throttle:6,1'])
                ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware(['auth', 'throttle:6,1'])
                ->name('verification.send');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');







/////////////////////////////////////////////////////////////////////////
Route::post('/company-register', [CompanyRegisteredUserController::class, 'store'])
                ->middleware('company')
                ->name('register');

Route::post('/company-login', [CompanyAuthenticatedSessionController::class, 'store'])
                ->middleware('company')
                ->name('login');

Route::post('/company-forgot-password', [CompanyPasswordResetLinkController::class, 'store'])
                ->middleware('company')
                ->name('password.email');

Route::post('/company-reset-password', [CompanyNewPasswordController::class, 'store'])
                ->middleware('company')
                ->name('password.update');

Route::get('/company-verify-email/{id}/{hash}', [CompanyVerifyEmailController::class, '__invoke'])
                ->middleware(['company', 'signed', 'throttle:6,1'])
                ->name('verification.verify');

Route::post('/company-email/verification-notification', [CompanyEmailVerificationNotificationController::class, 'store'])
                ->middleware(['company', 'throttle:6,1'])
                ->name('verification.send');

Route::post('/company-logout', [CompanyAuthenticatedSessionController::class, 'destroy'])
                ->middleware('company')
                ->name('logout');


