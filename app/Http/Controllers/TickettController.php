<?php

namespace App\Http\Controllers;

use App\Models\Passenger;
use App\Models\Reservation;
use App\Models\Tickett;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TickettController extends Controller
{
    /**
     * Retrieve all tickets.
     */
    public function index()
    {
        $tickets = Tickett::all();
        return response()->json($tickets);
    }

    /**
     * Create a new ticket for a given reservation.
     */
    public function create(Request $request)
    {
        // Find the reservation and check its existence
        $reservation = Reservation::with(['user', 'product', 'sans', 'passengers'])->find($request->reservation_id);
        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        // Generate a random ticket number
        $ticketNumber = strtoupper(Str::random(10));

        // Determine the ticket status based on reservation status
        $status = $reservation->status == 'confirmed' ? 'confirmed' : 'pending';

        // Get discount code information
        $discountCode = $reservation->discountCode;

        // Collect ticket information
        $ticketData = [
            'ticket_number' => $ticketNumber,
            'reservation_id' => $reservation->id,
            'purchase_time' => now(),
            'status' => $status,
            'user_info' => [
                'first_name' => $reservation->user->first_name,
                'last_name' => $reservation->user->last_name,
                'gender' => $reservation->user->gender,
                'age' => $reservation->user->age,
                'national_code' => $reservation->user->national_code,
            ],
            'passenger_info' => $reservation->passengers->map(function($passenger) {
                return [
                    'name_and_surname' => $passenger->Name_and_surname,
                    'gender' => $passenger->gender,
                    'age' => $passenger->age,
                    'national_code' => $passenger->national_code,
                ];
            }),
            'sans_info' => [
                'sans_date' => $reservation->reservation_date,
                'start_time' => $reservation->sans->start_time,
            ],
            'ticket_count' => $reservation->passengers->count() + 1, // Number of passengers + reserver
            'total_price' => $reservation->total_amount,
            'discount_percent' => optional($reservation->discountCode)->discount_percent ?? 0,
            'final_price' => $reservation->total_amount - ($reservation->total_amount * ($reservation->discountCode ? $reservation->discountCode->percent : 0) / 100),
        ];

        // Save the ticket to the database
        $ticket = Tickett::create($ticketData);

        return response()->json($ticket);
    }
}
