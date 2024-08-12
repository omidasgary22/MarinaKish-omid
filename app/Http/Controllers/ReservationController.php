<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function checkAvailability($sans_id, $reservation_date)
    {
        $existingReservation = Reservation::where('sans_id', '$sans_id')
            ->where('reseravetion_date', $reservation_date)
            ->first();

            return $existingReservation == null;
    }

    
}
