<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json(['users' => $users]);
    }

    public function store(StoreUserRequest $request)
    {
        $User = User::create($request->toArray());
        return response()->json(['message' => 'کاربر با موفقیت ایجاد شد', 'user' => $User], 201);
    }
}
