<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::all();
        return response()->json(['tickets' => $tickets], 200);
    }

    public function store($request)
    {
        $ticket = Ticket::create($request->all());
        return response()->json(['message' => 'تیکت با موفقیت ایجاد شد', 'ticket' => $ticket], 201);
    }

    public function show($id)
    {
        $ticket = Ticket::findOrfail($id);
        return response()->json(['ticket' => $ticket], 200);
    }
}
