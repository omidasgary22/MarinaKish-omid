<?php

namespace App\Http\Controllers;

use App\Models\Passenger;
use App\Models\Reservation;
use App\Models\Tickett;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TickettController extends Controller
{
    public function create(Request $request)
    {
        // پیدا کردن رزرو و بررسی وجود آن
        $reservation = Reservation::with(['user', 'product', 'sans', 'passengers'])->find($request->reservation_id);
        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        // تولید شماره بلیط رندوم
        $ticketNumber = strtoupper(Str::random(10));

        // بررسی پرداخت
        $status = $reservation->status == 'confirmed' ? 'confirmed' : 'pending';

        // جمع‌آوری اطلاعات بلیط
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
            'ticket_count' => $reservation->passengers->count() + 1, // تعداد مسافر + رزرو کننده
            'total_price' => $reservation->total_amount,
            'discount_percent' => $reservation->discountCode ? $reservation->discountCode->percent : 0,
            'final_price' => $reservation->total_amount - ($reservation->total_amount * ($reservation->discountCode ? $reservation->discountCode->percent : 0) / 100),
        ];

        // ذخیره‌سازی بلیط در دیتابیس
        $ticket = Tickett::create($ticketData);

        return response()->json($ticket);
    }
}
