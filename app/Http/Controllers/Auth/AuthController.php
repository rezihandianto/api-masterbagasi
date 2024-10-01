<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::create($validatedData);

        return response()->json([
            'message' => 'Registered successfully',
            'data' => $user
        ], 201);
    }
    public function login(LoginRequest $request)
    {
        try {
            $validatedData = $request->validated();

            if (!Auth::attempt($validatedData)) {
                return response()->json([
                    'message' => 'Invalid credentials'
                ], 401);
            }

            $user = User::where('email', $validatedData['email'])->first();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Logged in successfully',
                'data' => [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'user' => $user
                ]
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'Something went wrong',
                'errors' => $ex->getMessage()
            ], 500);
        }
    }
    public function user()
    {
        $user = Auth::user();
        return response()->json([
            'data' => $user
        ], 200);
    }
    public function logout()
    {
        try {
            Auth::user()->currentAccessToken()->delete();
            return response()->json([
                'message' => 'Logged out successfully'
            ], 204);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'Something went wrong',
                'errors' => $ex->getMessage()
            ], 500);
        }
    }
}
