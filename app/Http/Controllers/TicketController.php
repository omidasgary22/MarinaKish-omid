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
    /**
     * Get all tickets with their responses and status.
     */
    public function index(Request $request)
    {
        if ($request->user()->can('ticket.index')) {
            $tickets = Ticket::with('responses.user')->get();
            $tickets->each(function ($ticket) {
                $ticket->response_status = $ticket->responses->isEmpty() ? 'پاسخ داده نشده' : 'پاسخ داده شده';
            });

            return response()->json($tickets);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }

    /**
     * Create a new ticket.
     */
    public function store(StoreTicketRequest $request)
    {
        if ($request->user()->can('ticket.store')) {
            $ticket = Ticket::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'body' => $request->body,
                'priority' => $request->priority,
                'status' => 'open',
            ]);

            return response()->json($ticket, 201);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }

    /**
     * Update an existing ticket.
     */
    public function update(UpdateTicketRequest $request, $id)
    {
        if ($request->user()->can('ticket.update')) {
            $ticket = Ticket::findOrFail($id);
            $ticket->update($request->only(['title', 'body', 'priority', 'status']));

            return response()->json($ticket);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }

    /**
     * Delete a ticket.
     */
    public function destroy(Request $request, $id)
    {
        if ($request->user()->can('ticket.destroy')) {
            $ticket = Ticket::findOrFail($id);
            $ticket->delete();
            return response()->json(['message' => 'تیکت با موفقیت حذف شد']);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }

    /**
     * Add a response to a ticket.
     */
    public function addResponse(AddTicketResponseRequest $request, $id)
    {
        if ($request->user()->can('ticket.response')) {
            $ticket = Ticket::findOrFail($id);

            $response = $ticket->responses()->create([
                'user_id' => Auth::id(),
                'response' => $request->response,
            ]);

            if ($ticket->status == 'open') {
                $ticket->update(['status' => 'in_progress']);
            }

            return response()->json($response, 201);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }
}
