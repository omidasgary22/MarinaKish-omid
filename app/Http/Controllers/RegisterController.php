<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function Register(RegisterUserRequest $request)
    {
        $User = User::create($request->toArray());
        $User->assignRole('user'); 
        return response()->json(['message' => 'کاربر با موفقیت ثبت شد.', 'user' => $User], 201);
    }
}
