<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class StudentController extends Controller
{
    public function getStudents(): JsonResponse
    {
        $students = User::where('role', 'student')->get();

        return response()->json([
            'students' => $students,
        ], 200);
    }
}