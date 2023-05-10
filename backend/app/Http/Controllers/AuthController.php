<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        return response()->json([
            'token' => $token,
            'user' => auth()->user()
        ], 200);
    }

    public function register(Request $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password', 'first_name', 'last_name']);

        User::create([
            'first_name' => $credentials['first_name'],
            'last_name' => $credentials['last_name'],
            'email' => $credentials['email'],
            'password' => bcrypt($credentials['password']),
        ]);

        return response()->json([
            'message' => 'Successfully registered. Please login.',
        ], 200);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }

    public function refresh(): JsonResponse
    {
        $token = auth()->refresh();

        return response()->json([
            'token' => $token
        ], 200);
    }
}
