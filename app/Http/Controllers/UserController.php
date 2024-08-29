<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * List all users with pagination.
     */
    public function index(Request $request)
    {
        if ($request->user()->can('user.index')) {
            if ($request->has('national_code')) {
                $national_code = $request->input('national_code');
                $users = User::where('national_code', 'like', "%$national_code%")
                    ->orderBy('id', 'desc')
                    ->paginate(10);
            } else {
                $users = User::orderBy('id', 'desc')->paginate(10);
            }
            return response()->json(['users' => $users]);
        } else {
            return response()->json(['message' => 'You do not have permission to perform this action'], 403);
        }
    }

    /**
     * Show details of a specific user.
     */
    public function show(Request $request, $id)
    {
        $user = User::with('tickets', 'comments', 'emergency_contacts')->findOrFail($id);
        return response()->json(['user' => $user]);
    }

    /**
     * Update a user's information.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        if ($request->user()->can('user.update')) {
            $user = User::where('id', $id)->update($request->toArray());
            return response()->json(['message' => 'User successfully updated', 'user' => $user]);
        } else {
            return response()->json(['message' => 'You do not have permission to perform this action'], 403);
        }
    }

    /**
     * Soft delete a user by updating their national code and removing the record.
     */
    public function destroy(Request $request, $id)
    {
        if ($request->user()->can('user.delete')) {
            $user = User::findOrFail($id);
            $user->national_code .= '_deleted_' . now()->timestamp;
            $user->save();
            $user->delete();
            return response()->json(['message' => 'User successfully deleted.'], 200);
        } else {
            return response()->json(['message' => 'You do not have permission to perform this action'], 403);
        }
    }

    /**
     * Restore a previously soft-deleted user.
     */
    public function restore(Request $request, $id)
    {
        if ($request->user()->can('user.restore')) {
            $user = User::onlyTrashed()->findOrFail($id);
            $user->restore();
            return response()->json(['message' => 'User successfully restored.'], 200);
        } else {
            return response()->json(['message' => 'You do not have permission to perform this action'], 403);
        }
    }

    /**
     * Update the profile information of the authenticated user.
     */
    public function updateProfile(Request $request)
    {
        $user = User::find(Auth::id());
        $user->update($request->toArray());
        return response()->json(['message' => 'Profile successfully updated']);
    }

    /**
     * Upload a new profile picture for the user.
     */
    public function uploadProfileFile(Request $request, $id)
    {
        if ($request->user()->can('user.uploadprofile')) {
            $user = User::findOrFail($id);

            if ($request->hasFile('profile')) {
                $user->clearMediaCollection('profile');
                $user->addMediaFromRequest('profile')->toMediaCollection('profile');
            }

            return response()->json(['message' => 'Profile picture successfully uploaded.']);
        } else {
            return response()->json(['message' => 'You do not have permission to perform this action'], 403);
        }
    }

    /**
     * Show the profile of the authenticated user.
     */
    public function showProfile()
    {
        $user = Auth::user();
        return response()->json($user);
    }
}
