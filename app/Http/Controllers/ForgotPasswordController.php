<?php

namespace App\Http\Controllers;

use App\Jobs\SendPasswordResetSMS;
use App\Models\ForgetVerificationCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|digits:11|exists:users,phone_number',
        ], [
            'phone_number.required' => 'شماره موبایل الزامی است',
            'phone_number.digits' => 'شماره موبایل باید 11 رقم باشد',
            'phone_number.exists' => 'شماره موبایل وارد شده در سیستم وجود ندارد',
        ]);

        $phone_number = $request->phone_number;

        ForgetVerificationCode::where('phone_number', $phone_number)->delete();

        $code = rand(10000, 99999);

        ForgetVerificationCode::create([
            'phone_number' => $phone_number,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(2),
        ]);

        // ذخیره شماره موبایل در سشن
        session(['phone_number' => $request->phone_number]);

        SendPasswordResetSMS::dispatch($request->phone_number, $code);

        return response()->json(['message' => 'کد بازیابی رمز عبور ارسال شد.'], 200);
    }

    public function verifyResetCode(Request $request)
    {
        // اعتبارسنجی کد تأیید
        $request->validate([
            'verification_code' => 'required|digits:5',
        ], [
            'verification_code.required' => 'کد تایید الزامی است',
        ]);

        // دریافت شماره موبایل از سشن
        $phone_number = Session('phone_number');


        if (!$phone_number) {
            return response()->json(['error' => 'شماره موبایل یافت نشد'], 404);
        }

        // بررسی کد تأیید
        $verification = ForgetVerificationCode::where('phone_number', $phone_number)
            ->where('code', $request->verification_code)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$verification) {
            return response()->json(['error' => 'کد اشتباه است یا منقضی شده است'], 400);
        }

        // حذف کد تأیید پس از استفاده
        $verification->delete();

        return response()->json(['message' => 'کد تایید شد. اکنون می‌توانید رمز عبور جدید را وارد کنید.'], 200);
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|string|min:8',
            'repeat_password' => 'required|string|min:8',
        ], [
            'new_password.required' => 'رمز عبور جدید الزامی است',
            'new_password.min' => 'رمز عبور نباید کمتر از 8 کاراکتر باشد',
            'repeat_password.required' => 'تکرار رمز عبور الزامی است',
            'repeat_password.min' => 'تعداد کاراکتر تکرار رمز عبور  صحیح نمیباشد',
        ]);

        if ($request->new_password != $request->repeat_password) {
            return response()->json(['message' => 'تکرار رمز عبور صحیح نمی باشد']);
        }

        $phone_number = Session('phone_number');

        if (!$phone_number) {
            return response()->json(['error' => 'شماره موبایل یافت نشد'], 404);
        }

        $user = User::where('phone_number', $phone_number)->first();

        if (!$user) {
            return response()->json(['error' => 'کاربر یافت نشد'], 404);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        session()->forget('phone_number');

        return response()->json([
            'message' => 'رمز عبور با موفقیت تغییر یافت',
            'token' => $token
        ], 200);
    }
}
