<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeacherController;
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

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::get('/task/{taskVariant}/image', [AssignmentController::class, 'getImage']);


Route::group(['middleware' => 'auth'], function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/refresh', [AuthController::class, 'refresh']);

    Route::get('/assignment', [AssignmentController::class, 'getAssignments']);
    Route::post('/assignment/generate', [AssignmentController::class, 'generateAssignment']);

    Route::get('/assignment/{id}', [AssignmentController::class, 'getAssignment']);
    Route::post('/assignment/{id}/submit', [AssignmentController::class, 'submitAssignment']);
    Route::get('/assignment/{id}/{taskNumber}', [AssignmentController::class, 'getTaskVariantByNumber']);
    Route::post('/assignment/{id}/{taskNumber}', [AssignmentController::class, 'addSolution']);

    Route::get('/task/{taskVariantId}', [AssignmentController::class, 'getTaskVariant']);

    Route::post('/teacher/parse', [TeacherController::class, 'parse']);



});
