<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
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

Route::view('/','admin.login')->name('login');

Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    Artisan::call('route:clear');
    return 'Cache cleared successfully.';
});

Route::prefix('admin')->name('admin.')->group(function(){
       
    Route::middleware(['guest:admin'])->group(function(){
          Route::view('/','admin.login')->name('login');
          Route::post('/check',[AuthController::class,'check'])->name('check');
        
    });

    Route::middleware(['auth:admin','PreventBackHistory'])->group(function(){
        Route::view('/home','dashboard.admin.home')->name('home');
        Route::get('/home',[HomeController::class,'home'])->name('home');
        Route::get('/logout',[AuthController::class,'logout'])->name('logout');
        Route::get('/user',[UserController::class,'index'])->name('user');
        Route::resource('user', UserController::class);
        Route::resource('category', CategoryController::class);
        Route::resource('product', ProductController::class);

    });

});
