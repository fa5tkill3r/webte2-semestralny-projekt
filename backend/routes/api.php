<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SetController;

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

Route::get('/generate-pdf', [PDFController::class, 'generatePDF'])->name('generate.pdf');

Route::get('/myPDF', function () {
    return view('myPDF');
});

Route::group(['middleware' => 'auth'], function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/refresh', [AuthController::class, 'refresh']);

    Route::get('/assignment', [AssignmentController::class, 'getAssignments']);
    Route::post('/assignment/generate', [AssignmentController::class, 'generateAssignment']);

    Route::get('/assignment/{id}', [AssignmentController::class, 'getAssignment']);
//    Route::post('/assignment/{id}/submit', [AssignmentController::class, 'submitAssignment']);
    Route::get('/assignment/{id}/{taskNumber}', [AssignmentController::class, 'getTaskVariantByNumber']);
    Route::post('/assignment/{assignmentId}/{taskVariantId}/submit', [AssignmentController::class, 'addSolution']);
    Route::post('/assignment/{id}/{taskId}/generate', [AssignmentController::class, 'generateTaskVariant']);
    

    Route::get('/task/{taskVariantId}', [AssignmentController::class, 'getTaskVariant']);

    Route::post('/teacher/parse', [TeacherController::class, 'parse']);
    Route::get('/students', [StudentController::class, 'getStudents']);
    Route::get('/tasks', [AssignmentController::class, 'getAllTasks']);
    Route::post('/sets', [SetController::class, 'createSet']);
});
