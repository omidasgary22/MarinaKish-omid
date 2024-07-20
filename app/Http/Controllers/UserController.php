<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
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

    public function update(UpdateUserRequest $request, string $id)
    {
        $User = User::where('id', $id)->update($request->toArray());
        return response()->json(['کاربر با موفقیت به روزرسانی شد', 'user' => $User]);
    }
}
