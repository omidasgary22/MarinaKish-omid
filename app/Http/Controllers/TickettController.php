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
        $reservation = Reservation::with(['user', 'product', 'sans', 'passengers', 'discountCode'])->find($request->reservation_id);
        if (!$reservation) {
            return response()->json(['message' => 'رزرو یافت نشد'], 404);
        }

        // Generate a random ticket number
        $ticketNumber = strtoupper(Str::random(10));

        // Determine the ticket status based on reservation status
        $status = $reservation->status == 'confirmed' ? 'confirmed' : 'pending';

        // Get discount code information
        $discountCode = $reservation->discountCode;

        // Calculate discount details
        $baseAmount = $reservation->total_amount; // Use total_amount from reservation
        $discountAmount = $reservation->discount_amount; // Use the value from reservation
        $finalAmount = $reservation->final_amount; // Use the value from reservation

        // Collect ticket information
        $ticketData = [
            'ticket_number' => $ticketNumber,
            'reservation_id' => $reservation->id,
            'purchase_time' => now(),
            'status' => $status,
            'total_amount' => $baseAmount, 
            'final_amount' => $finalAmount, 
            'user_info' => [
                'first_name' => $reservation->user->first_name,
                'last_name' => $reservation->user->last_name,
                'gender' => $reservation->user->gender,
                'age' => $reservation->user->age,
                'national_code' => $reservation->user->national_code,
            ],
            'passenger_info' => $reservation->passengers->map(function ($passenger) {
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
            'total_price' => $baseAmount, // ذخیره مبلغ کل قبل از تخفیف
            'discount_percent' => $discountCode ? $discountCode->discount_percentage : 0, // درصد تخفیف
            'discount_amount' => $discountAmount ?? 0, // مبلغ تخفیف
            'final_price' => $finalAmount, // مبلغ نهایی پس از تخفیف
        ];

        // Save the ticket to the database
        $ticket = Tickett::create($ticketData);

        // Return the ticket data along with total_amount, discount_amount, and final_amount
        return response()->json([
            'ticket' => $ticket,
            'total_amount' => $baseAmount,       // بازگرداندن total_amount
            'discount_amount' => $discountAmount, // بازگرداندن discount_amount
            'final_amount' => $finalAmount        // بازگرداندن final_amount
        ]);
    }
}
