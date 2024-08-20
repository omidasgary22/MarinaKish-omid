<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmrgencyContactrequest;
use App\Models\EmrgencyContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmergencyContactController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->can('emrgency.index')) {
            $contact = EmrgencyContact::where('user_id', Auth::id())->get();
            return response()->json($contact);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }

    public function store(EmrgencyContactrequest $request)
    {
        if ($request->user()->can('emrgency.store')) {
            $contact = EmrgencyContact::create($request->toArray());
            return response()->json($contact, 201);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }

    public function update(EmrgencyContactrequest $request, $id)
    {
        if ($request->user()->can('emrgency.update')) {
            $contact = EmrgencyContact::findOrFail($id);
            $contact->update($request->only(['name', 'phone']));
            return response()->json(['message' => 'شماره اضطراری با موفقیت به روز رسانی شد', $contact]);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->can('emrgency.destroy')) {
            $contact = EmrgencyContact::findOrFail($id);
            $contact->delete();
            return response()->json(['message' => 'تماس اضطراری با موفقیت حذف شد',]);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }
}
