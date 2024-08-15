<?php

namespace App\Http\Controllers;

use App\Models\Passenger;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\Sans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function checkAvailability($sans_id, $reservation_date)
    {
        return Reservation::where('sans_id', $sans_id)
            ->where('reservation_date', $reservation_date)
            ->doesntExist();
    }
    

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'sans_id' => 'required|exists:sans,id',
            'reservation_date' => 'required|date',
            'product_id' => 'required|exists:products,id',
            'passengers' => 'array',
            'passengers.*.id' => 'exists:passengers,id',
        ]);

        $sans_id = $validatedData['sans_id'];
        $reservation_date = $validatedData['reservation_date'];
        $product_id = $validatedData['product_id'];
        $passengerIds = $request->input('passengers', []);
    

        $user = Auth::user();
        $sans = Sans::findOrFail($sans_id);
        $product = Product::findOrFail($product_id);


        // بررسی محدودیت سنی محصول
        if ($product->age_limited && $user->age < $product->age_limited) {
            return response()->json(['message' => 'سن شما کمتر از محدودیت سنی برای این تفریح است.'], 403);
        }

        $totalAmount = $product->price;

        // بررسی وجود مسافران و محدودیت سنی آنها
        if (is_array($passengerIds) || is_object($passengerIds)) {
            foreach ($passengerIds as $passengerData) {
                $passenger = Passenger::findOrFail($passengerData);


                // بررسی محدودیت سنی سانس برای مسافران
                if ($sans->age_limit && $passenger->age < $sans->age_limit) {
                    return response()->json(['message' => "سن مسافر {$passenger->Name_and_surname} کمتر از محدودیت سنی برای این سانس است."], 403);
                }

                // بررسی محدودیت سنی محصول برای مسافران
                if ($product->age_limited && $passenger->age < $product->age_limited) {
                    return response()->json(['message' => "سن مسافر {$passenger->Name_and_surname} کمتر از محدودیت سنی برای این تفریح است."], 403);
                }

                // افزودن مبلغ همراهان به مبلغ اصلی
                $totalAmount += $product->price;
            }
        }


        if (!$this->checkAvailability($sans_id, $reservation_date)) {
            return response()->json(['message' => 'این سانس در این تاریخ قبلا رزرو شده است'], 400);
        }

        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'sans_id' => $sans_id,
            'product_id' => $product_id,
            'reservation_date' => $reservation_date,
            'total_amount' => $totalAmount,
        ]);

        return response()->json(['message' => 'رزرو با موفقیت انجام شد.', 'reservation' => $reservation, 'total_amount' => $totalAmount,], 201);
    }
}
