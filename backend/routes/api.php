<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post( '/auth/login', [AuthController::class, 'login']);
Route::post( '/auth/register', [AuthController::class, 'register']);


Route::group(['middleware' => 'auth'], function() {
    Route::post( '/auth/logout', [AuthController::class, 'logout']);
    Route::get( '/auth/refresh', [AuthController::class, 'refresh']);
});
