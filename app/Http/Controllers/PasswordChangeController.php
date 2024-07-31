<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class PasswordChangeController extends Controller
{
    public function update(ChangePasswordRequest $request): JsonResponse
    {
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return response()->json(['message' => 'رمز عبور فعلی اشتباه است.'], 400);
        }


        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json(['message' => 'رمز عیور با موفقیت تغییر یافت.']);
    }
}
