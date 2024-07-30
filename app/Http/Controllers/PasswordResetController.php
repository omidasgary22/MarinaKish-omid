<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Models\User;
use App\Models\PasswordReset; 
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;


class PasswordResetController extends Controller
{
    public function sendResetToken(ForgotPasswordRequest $request)
    {
        $user = User::where('national_code', $request->national_code)
            ->where('phone_number', $request->phone_number)
            ->first();
        if (!$user) {
            return response()->json(['message' => 'کاربری با این مشخصلت یافت نشد.'], 404);
        }
        $token = Str::random(60);
        PasswordReset::updateOrCreate(
            ['national_code' => $request->national_code, 'phone_number' => $request->phone_number],
            ['token' => $token, 'created_at' => Carbon::now()]
        );




        return response()->json(['message' => 'کد تایید ارسال شد.']);
    }
}
