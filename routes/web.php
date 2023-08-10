<?php

use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['auth']], function () {
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
