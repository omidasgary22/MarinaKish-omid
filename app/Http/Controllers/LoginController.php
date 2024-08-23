<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Handle the user login process.
     * This method authenticates the user by verifying the national code and password.
     * If successful, it generates and returns an authentication token.
     */
    public function login(LoginRequest $request)
    {
        // Validate the incoming request data
        $validated = $request->validated();

        // Retrieve the user based on the provided national code
        $user = User::where('national_code', $validated['national_code'])->first();

        // Check if the user exists and the password is correct
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'کد ملی یا رمز عبور اشتباه است.'], 401);
        }

        // Generate a new authentication token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return a success response with the generated token and user information
        return response()->json([
            'message' => 'ورود موفقیت‌آمیز بود.',
            'token' => $token,
            'user' => $user
        ], 200);
    }
}
