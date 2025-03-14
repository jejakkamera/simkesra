<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\Page2;
use App\Http\Controllers\pages\MiscError;
// use App\Http\Controllers\authentications\LoginBasic;
// use App\Http\Controllers\authentications\RegisterBasic;
use App\Livewire\Counter;
use App\Livewire\HomePage;
use App\Livewire\Auth\Login as LoginBasic;
use App\Livewire\Auth\Register as RegisterBasic;
use App\Livewire\Auth\Verify as Verify;
use App\Livewire\Auth\ForgotPassword as ForgotPassword;
use App\Livewire\Auth\ForgotPasswordVerif as ForgotPasswordVerif;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Mail\SendVerify;
use Illuminate\Support\Facades\Mail;
use App\Livewire\Auth\UserProfile;
use App\Models\Payment;
use Symfony\Component\HttpKernel\Exception\HttpException;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Main Page Route
// Route::get('/', HomePage::class)->name('pages-home');
// Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2');
// Route::get('/counter', Counter::class);

// locale
Route::get('lang/{locale}', [LanguageController::class, 'swap']);

// pages
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

// authentication
Route::get('/', LoginBasic::class)->middleware('role.redirect');
Route::get('/login', LoginBasic::class)->middleware('role.redirect');
Route::get('/auth/login-basic', LoginBasic::class)->name('auth-login-basic')->middleware('role.redirect');
// Route::get('/auth/register-basic', RegisterBasic::class)->name('auth-register-basic');
Route::get('/auth/forgot-password', ForgotPassword::class)->name('password.request');
Route::get('/auth/forgot-password-verif/{user}/{token}', [ForgotPasswordVerif::class, 'verifyResetPassword'])->name('password.request-verif');

// Email Verification Notice
Route::get('/email/verify', Verify::class)->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request, $id) {
    $request->fulfill();
    return redirect()->route('auth-login-basic')->with('message', 'Email verified successfully! Please login.');
})->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/profile', UserProfile::class)->name('UserProfile');
Route::get('/force-error', function () {
    // Paksa terjadinya error 500
    throw new HttpException(500, 'This is a forced 500 error.');
});
