<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmrgencyContactRequest;
use App\Models\EmrgencyContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmergencyContactController extends Controller
{
    /**
     * Display a listing of the emergency contacts for the authenticated user.
     * This method retrieves and returns all emergency contacts associated with the authenticated user.
     */
    public function index(Request $request)
    {
        if ($request->user()->can('emrgency.index')) {
            $contacts = EmrgencyContact::where('user_id', Auth::id())->get();
            return response()->json($contacts);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید'], 403);
        }
    }

    /**
     * Store a newly created emergency contact in storage.
     * This method creates a new emergency contact and returns it if the user has the necessary permission.
     */
    public function store(EmrgencyContactRequest $request)
    {
        if ($request->user()->can('emrgency.store')) {
            $contact = EmrgencyContact::create($request->toArray());
            return response()->json($contact, 201);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید'], 403);
        }
    }

    /**
     * Update the specified emergency contact in storage.
     * This method updates the specified emergency contact's name and phone number if the user has the necessary permission.
     */
    public function update(EmrgencyContactRequest $request, $id)
    {
        if ($request->user()->can('emrgency.update')) {
            $contact = EmrgencyContact::findOrFail($id);
            $contact->update($request->only(['name', 'phone']));
            return response()->json(['message' => 'شماره اضطراری با موفقیت به روز رسانی شد', 'contact' => $contact]);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید'], 403);
        }
    }

    /**
     * Remove the specified emergency contact from storage.
     * This method deletes the specified emergency contact if the user has the necessary permission.
     */
    public function destroy(Request $request, $id)
    {
        if ($request->user()->can('emrgency.destroy')) {
            $contact = EmrgencyContact::findOrFail($id);
            $contact->delete();
            return response()->json(['message' => 'تماس اضطراری با موفقیت حذف شد']);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید'], 403);
        }
    }
}
