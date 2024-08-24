<?php

namespace App\Http\Controllers;

use App\Jobs\SendVerificationSMS;
use App\Models\User;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function sendVerificationCode(Request $request)
    {


        $request->validate([
            'phone_number' => 'required|digits:11|unique:users,phone_number',
            'national_code' => 'required|digits:10|unique:users,national_code',
        ]);

        $code = rand(10000, 99999);
        $expiresAt = Carbon::now()->addMinutes(2); // تنظیم تاریخ انقضا به مدت 2 دقیقه

        VerificationCode::create([
            'phone_number' => $request->phone_number,
            'national_code' => $request->national_code,
            'code' => $code,
            'expires_at' => $expiresAt,
        ]);

        SendVerificationSMS::dispatch($request->phone_number, $code);

        return response()->json(['message' => 'کد تایید ارسال شد'], 200);
    }


    public function verifyCode(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|digits:11',
            'national_code' => 'required|digits:10',
            'verification_code' => 'required|digits:5',
        ]);

        $verification = VerificationCode::where('phone_number', $request->phone_number)
            ->where('national_code', $request->national_code)
            ->where('code', $request->verification_code)
            ->where('expires_at', '>', Carbon::now()) // بررسی تاریخ انقضا
            ->first();

        if (!$verification) {
            return response()->json(['error' => 'کد تایید اشتباه است یا منقضی شده'], 400);
        }

        $user = User::create([
            'phone_number' => $request->phone_number,
            'national_code' => $request->national_code,
            'password' => bcrypt($request->password),
        ]);

        $verification->delete();

        return response()->json(['message' => 'ثبت‌ نام با موفقیت انجام شد'], 200);
    }
}
