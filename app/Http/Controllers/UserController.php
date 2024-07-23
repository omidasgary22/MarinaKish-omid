<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function show($id)
    {
        $user = User::with('tickets')->findOrFail($id);
        return response()->json(['message' => 'کاربر با موفقیت بازیابی شد.'], 200);
    }



    public function update(UpdateUserRequest $request, string $id)
    {
        $User = User::where('id', $id)->update($request->toArray());
        return response()->json(['کاربر با موفقیت به روزرسانی شد', 'user' => $User]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'کاربر با موفقیت حذف شد.'], 200);
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        return response()->json(['message' => 'کاربر با موفقیت بازیابی شد.'], 200);
    }
}
