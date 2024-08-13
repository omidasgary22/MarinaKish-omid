<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Reservation;
use App\Models\Sans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            'product_id' => 'required|exists:products,id',
        ]);
    
        $sans_id = $request->input('sans_id');
        $reservation_date = $request->input('reservation_date');
        $product_id = $request->input('product_id');
    
        $user = Auth::user();
        $sans = Sans::findOrFail($sans_id);
        $product = Product::findOrFail($product_id);
    
        // بررسی محدودیت سنی سانس
        if ($sans->age_limit && $user->age < $sans->age_limit) {
            return response()->json(['message' => 'سن شما کمتر از محدودیت سنی برای این سانس است.'], 403);
        }
    
        // بررسی محدودیت سنی محصول
        if ($product->age_limited && $user->age < $product->age_limited) {
            return response()->json(['message' => 'سن شما کمتر از محدودیت سنی برای این محصول است.'], 403);
        }
    
        if (!$this->checkAvailability($sans_id, $reservation_date)) {
            return response()->json(['message' => 'این سانس در این تاریخ قبلا رزرو شده است'], 400);
        }
    
        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'sans_id' => $sans_id,
            'product_id' => $product_id,
            'reservation_date' => $reservation_date,
        ]);
    
        return response()->json(['message' => 'رزرو با موفقیت انجام شد.', 'reservation' => $reservation], 201);
    }
}
