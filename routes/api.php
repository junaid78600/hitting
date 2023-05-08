<?php
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\ApiController;
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
 
Route::group([
 
    'middleware' => 'api','jwt.verify'
 
], function ($router) {
    Route::post('/register', [AuthApiController::class, 'register'])->name('register');
    Route::post('/registerUser', [AuthApiController::class, 'registerUser'])->name('registerUser');
    Route::post('/login', [AuthApiController::class, 'login'])->name('login');
    Route::post('/logout', [AuthApiController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthApiController::class, 'refresh'])->name('refresh');
    Route::post('/me', [AuthApiController::class, 'me'])->name('me');
    Route::post('/jb', [ApiController::class, 'jb'])->name('jb');
    
    Route::post('/getUserDetail',[ApiController::class,'getUserDetail']);
    
    Route::post('/updateProfile',[ApiController::class,'updateProfile']);
    
    Route::post('/saveQuickEntry',[ApiController::class,'saveQuickEntry']);

    Route::post('/getQuickEntry',[ApiController::class,'getQuickEntry']);

    Route::post('/deleteQuickEntry',[ApiController::class,'deleteQuickEntry']);

    Route::post('/getPitchList',[ApiController::class,'getPitchList']);

    Route::post('/quickEntryAB',[ApiController::class,'quickEntryAB']);

    Route::post('/saveQuickEntryAB',[ApiController::class,'saveQuickEntryAB']);
    
    Route::post('/saveVideo',[ApiController::class,'saveVideo']);

    Route::post('/deleteVideo',[ApiController::class,'deleteVideo']);
    
  
    
    
});