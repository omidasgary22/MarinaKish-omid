<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Profiler\Profile;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->can('user.index')) {
            $users = User::orderBy('id', 'desc')->paginate(10);
            return response()->json(['users' => $users]);
        } else {
            return response()->json(['message' => 'شما دسترسی لازم برای انجام این کار را ندارید'], 403);
        }
    }

    // public function store(StoreUserRequest $request)
    // {

    //     $User = User::create($request->toArray());
    //     return response()->json(['message' => 'کاربر با موفقیت ایجاد شد', 'user' => $User], 201);
    // }

    public function show(Request $request, $id)
    {
        $user = User::with('tickets', 'comments')->findOrFail($id);
        return response()->json(['user' => $user]);
    }



    public function update(UpdateUserRequest $request, string $id)
    {
        if ($request->user()->can()) {
            $User = User::where('id', $id)->update($request->toArray());
            return response()->json(['کاربر با موفقیت به روزرسانی شد', 'user' => $User]);
        } else {
            return response()->json(['message' => 'شما دسترسی لازم برای انجام این کار را ندارید'], 403);
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->can('user.delete')) {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['message' => 'کاربر با موفقیت حذف شد.'], 200);
        } else {
            return response()->json(['message' => 'شما دسترسی لازم برای انجام این کار را ندارید'], 403);
        }
    }

    public function restore(Request $request, $id)
    {
        if ($request->user()->can('user.restore')) {
            $user = User::onlyTrashed()->findOrFail($id);
            $user->restore();
            return response()->json(['message' => 'کاربر با موفقیت بازیابی شد.'], 200);
        } else {
            return response()->json(['message' => 'شما دسترسی لازم برای انجام این کار را ندارید'], 403);
        }
    }

    public function  me()
    {
        $user = auth()->user()->with("tickets")->first();

        return response()->json($user);
    }

    public function uploadProfileFile(Request $request, $id)
    {
        if ($request->user()->can('user.uploadprofile')) {
            $user = User::findOrFail($id);

            if ($request->hasFile('profile')) {
                $user->clearMediaCollection('profile');
                $user->addMediaFromRequest('profile')->toMediaCollection('profile');
            }

            return response()->json(['message' => 'پروفایل شما با موفقیت آپلود شد.']);
        } else {
            return response()->json(['message' => 'شما دسترسی لازم برای انجام این کار را ندارید'], 403);
        }
    }
}
