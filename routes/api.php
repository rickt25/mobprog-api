<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\WalletController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/me', [AuthController::class, 'me'])->name('me');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/wallet', [WalletController::class, 'index']);
    Route::post('/wallet', [WalletController::class, 'store']);
    Route::patch('/wallet/{id}', [WalletController::class, 'update']);
    Route::delete('/wallet/{id}', [WalletController::class, 'destroy']);

    Route::get('/activity', [ActivityController::class, 'index']);
    Route::post('/activity', [ActivityController::class, 'store']);
    Route::patch('/activity/{id}', [ActivityController::class, 'update']);
    Route::delete('/activity/{id}', [ActivityController::class, 'destroy']);

    Route::get('/overview', [HomeController::class, 'overview']);
    Route::get('/today-activity', [HomeController::class, 'todayActivity']);

    Route::get('/category', [CategoryController::class, 'getAll']);
    Route::post('/category', [CategoryController::class, 'addCategory']);
});

