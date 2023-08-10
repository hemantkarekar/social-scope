<?php

use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::controller(PagesController::class)->group(function(){
    Route::get('/', 'index');
    Route::get('/login', 'index')->name('login');
    Route::get('/register', 'index');
    Route::get('/mail', 'test');
});

// Email Verification Notice
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Email Verification Handler 
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
 
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Resend Email Verification
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
 
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


Route::group(['middleware' => ['auth', 'verified']], function () {
    // Route::prefix('dashboard')->group(function(){
    //     Route::get('/', [DashboardController::class, 'index']);
    // });
    Route::group(['prefix' => '/{user}'], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
        // Route::get('/profile', [DashboardController::class, 'profile'])->name('user.profile');
        // Route::get('/help', [DashboardController::class, 'profile'])->name('user.help');
        // Route::get('/search', [DashboardController::class, 'profile'])->name('user.search');
        // Route::group(['prefix' => '/settings'], function () {
        //     Route::get('/', [DashboardController::class, 'profile'])->name('user.settings');
        //     Route::get('/password', [DashboardController::class, 'profile'])->name('user.password');
        // });
    });
});
