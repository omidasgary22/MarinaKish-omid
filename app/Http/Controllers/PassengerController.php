<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PassengerController extends Controller
{
    public function index()
    {
        $passengers = Auth::user()->passengers;
        return response()->json($passengers);
    }
}
