<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Send a password reset token to the user.
     * Validates the user by their national code and phone number, generates a reset token, and stores it.
     */
    public function sendResetToken(ForgotPasswordRequest $request)
    {
        // Check if the user has permission to request a password reset
        if ($request->user()->can('PasswordReset')) {
            // Find the user by national code and phone number
            $user = User::where('national_code', $request->national_code)
                ->where('phone_number', $request->phone_number)
                ->first();
            
            // If user is not found, return a 404 response
            if (!$user) {
                return response()->json(['message' => 'کاربری با این مشخصات یافت نشد.'], 404);
            }

            // Generate a new password reset token
            $token = Str::random(60);

            // Update or create a password reset record
            PasswordReset::updateOrCreate(
                ['national_code' => $request->national_code, 'phone_number' => $request->phone_number],
                ['token' => $token, 'created_at' => Carbon::now()]
            );

            // Return a success message
            return response()->json(['message' => 'کد تایید ارسال شد.']);
        } else {
            // Return a 403 response if the user does not have permission
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید'], 403);
        }
    }
}
