<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    /**
     * Handle the user logout process.
     * This method revokes the current access token and logs the user out.
     */
    public function logout(Request $request)
    {
        // Revoke the current access token
        $request->user()->currentAccessToken()->delete();

        // Log the user out of the web guard
        Auth::guard('web')->logout();

        // Return a success message
        return response()->json(['message' => 'با موفقیت خارج شدید']);
    }
}
