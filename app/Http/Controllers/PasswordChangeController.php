<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordChangeController extends Controller
{
    /**
     * Update the authenticated user's password.
     * Validates the current password, updates to a new password if correct, and responds accordingly.
     */
    public function update(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();

        // Check if the user has permission to change the password
        if ($request->user()->can('PasswordChange')) {
            // Verify the current password
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['message' => 'رمز عبور فعلی اشتباه است.'], 400);
            }

            // Update the user's password
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            return response()->json(['message' => 'رمز عبور با موفقیت تغییر یافت.']);
        } else {
            return response()->json(['message' => 'شما دسترسی لازم برای انجام این کار را ندارید'], 403);
        }
    }
}
