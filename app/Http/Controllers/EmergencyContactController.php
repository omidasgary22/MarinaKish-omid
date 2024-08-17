<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmrgencyContactrequest;
use App\Models\EmrgencyContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmergencyContactController extends Controller
{
    public function index()
    {
        $contact = EmrgencyContact::where('user_id', Auth::id())->get();
        return response()->json($contact);
    }

    public function store(EmrgencyContactrequest $request)
    {
        $contact = EmrgencyContact::create($request->toArray());
        return response()->json($contact, 201);
    }

    public function update(EmrgencyContactrequest $request, $id)
    {
        $contact = EmrgencyContact::findOrFail($id);
        $contact->update($request->only(['name', 'phone']));
        return response()->json($contact);
    }

    public function destroy($request, $id)
    {
        $contact = EmrgencyContact::findOrFail($id);
        $contact->delete();
        return response()->json(['message' => 'تماس اضطراری با موفقیت حذف شد']);
    }
}
