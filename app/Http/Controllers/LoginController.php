<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        $user = User::where('national_code',$validated['national_code'])->first();
        if(!$user ||!Hash::check($validated['password'],$user->password)){
            return response()->json(['message' => 'کد ملی یا رمز عیور اشتباه است.'],401);
        }


$token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'ورود موفقیت‌آمیز بود.',
            'token' => $token,
            'user' => $user
        ], 200);
    }
}
