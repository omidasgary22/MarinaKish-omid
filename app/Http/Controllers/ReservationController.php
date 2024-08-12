<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function checkAvailability($sans_id, $reservation_date)
    {
        $existingReservation = Reservation::where('sans_id', $sans_id)
            ->where('reservation_date', $reservation_date)
            ->first();

        return $existingReservation == null;
    }

    public function store(Request $request)
    {
        $request->validate([
            'sans_id' => 'required|exists:sans,id',
            'reservation_date' => 'required|date',
        ]);

        $sans_id = $request->input('sans_id');
        $reservation_date = $request->input('reservation_date');

        if (!$this->checkAvailability($sans_id, $reservation_date)) {
            return response()->json(['message' => 'این سانس در این تاریخ قبلا رزرو شده است'], 400);
        }

        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'sans_id' => $sans_id,
            'reservation_date' => $reservation_date,
        ]);

        return response()->json(['message' => 'رزرو با موفقیت انجام شد.', 'reservation' => $reservation], 201);
    }
}
