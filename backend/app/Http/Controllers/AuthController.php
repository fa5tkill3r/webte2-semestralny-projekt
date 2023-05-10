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
                'message' => 'invalidCredentials',
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
        $credentials = $request->only(['email', 'password', 'firstName', 'lastName']);

        User::create([
            'first_name' => $credentials['firstName'],
            'last_name' => $credentials['lastName'],
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

    public function refresh(Request $request): JsonResponse
    {
        $token = auth()->refresh();

        $user = request()->user();

        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 200);
    }
}
