<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddTicketResponseRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('responses.user')->get();

        $tickets->each(function ($ticket) {
            $ticket->response_status = $ticket->responses->isEmpty() ? 'پاسخ داده نشده' : 'پاسخ داده شده';
        });

        return response()->json($tickets);
    }

    public function store(StoreTicketRequest $request)
    {
        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'body' => $request->body,
            'priority' => $request->priority,
            'status' => 'open',
        ]);

        return response()->json($ticket, 201);
    }

    public function update(UpdateTicketRequest $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update($request->only(['title', 'body', 'priority', 'status']));

        return response()->json($ticket);
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        return response()->json(['message' => 'تیکت با موفقیت حذف شد']);
    }

    //پاسخ به تیکت
    public function addResponse(AddTicketResponseRequest $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $response = $ticket->responses()->create([
            'user_id' => Auth::id(),
            'response' => $request->response,
        ]);

        if ($ticket->status == 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        return response()->json($response, 201);
    }
}
