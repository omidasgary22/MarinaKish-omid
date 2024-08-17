<?php

namespace App\Http\Controllers;

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
}
