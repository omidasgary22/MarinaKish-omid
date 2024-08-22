<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateReservationRequest;
use App\Http\Requests\UpdateRuleRequest;
use App\Models\DiscountCode;
use App\Models\Passenger;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\Sans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{

    public function index(Request $request)
    {
        if ($request->user()->can('reservation.index')) {
            $query = Reservation::with(['user', 'sans', 'product', 'discountCode']);
            //فیلتر بر اساس تاریخ رزرو
            if ($request->has('reservation_date')) {
                $query->whereDate('reservation_date', $request->input('reservation'));
            }

            //فیلتر بر اساس کد ملی کاربر
            if ($request->has('national_code')) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('national_code', $request->input('national_code'));
                });
            }

            $reservation = $query->paginate(10);
            return response()->json($reservation);
        } else {
            return response()->json(['message' => 'شما دسترسی لازم را ندارید']);
        }
    }


    public function checkAvailability($sans_id, $reservation_date)
    {
        return Reservation::where('sans_id', $sans_id)
            ->where('reservation_date', $reservation_date)
            ->doesntExist();
    }

    public function showTicket($id)
    {
        $reservation = Reservation::with(['user', 'passengers', 'product', 'sans', 'discountCode'])->findOrFail($id);
        return view('ticket', compact('reservation'));
    }


    public function store(Request $request)
    {
        if ($request->user()->can('reservation.store')) {
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
                'ticket_number' => 'TICKET-' . str_pad(Reservation::max('id') + 1, 6, '0', STR_PAD_LEFT),
                'status' => 'pending',
            ]);


            // اضافه کردن مسافران به رزرو
            if (is_array($passengerIds) || is_object($passengerIds)) {
                $reservation->passengers()->attach($passengerIds);
            }


            return response()->json(['message' => 'رزرو با موفقیت انجام شد.', 'reservation' => $reservation, 'total_amount' => $totalAmount,], 201);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }

    public function update(UpdateReservationRequest $request, $id)
    {
        if ($request->user()->can('reservation.update')) {
            $reservation = Reservation::findOrFail($id);
            $reservation->update($request->toArray());

            return response()->json(['message' => 'رزرو با موفقیت به روز رسانی شد.', 'reservation' => $reservation]);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->can('reservation.destroy')) {
            $reservation = Reservation::findOrFail($id);
            $reservation->delete();

            return response()->json(['message' => 'رزرو با موفقیت حذف شد.']);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }

    //اعمال کد تخفیف در زمان پرداخت
    public function aaplyDiscountCode(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'discount_code' => 'required|string|exists:discount_codes,code',
        ]);

        $reservation = Reservation::findOrFail($request->input('reservation_id'));
        $discountCode = DiscountCode::where('code', $request->input('discount_code'))->firstOrFail();

        //بررسی تاریخ انتقضا
        if ($discountCode->expires_at < now()) {
            return response()->json(['message' => 'کد تخفیف منقضی شده است'], 400);
        }

        //بررسی استفاده قبلی توسط کاربر
        $userHasUsedCode = Reservation::where('user_id', Auth::id())
            ->where('discount_code_id', $discountCode->id)->exists();

        if ($userHasUsedCode) {
            return response()->json(['message' => 'شما قبلا از این کد تخفیف استفاده کرده اید'], 400);
        }

        //محاسبه مبلغ نهایی با تخفیف
        // $id = $request->reservation_id;
        $totalAmount = $reservation->total_amount;
        $discountAmount = ($totalAmount * $discountCode->discount_percentage) / 100;
        $finalAmount = $totalAmount - $discountAmount;
        //  Reservation::find($id)->update(['total_amount' => $finalAmount]);

        // به‌روزرسانی رزرو با کد تخفیف
        $reservation->discount_code_id = $discountCode->id;
        $reservation->save();


        return response()->json([
            'total_amount' => $totalAmount,
            'discount_amount' => $discountAmount,
            'final_amount' => $finalAmount,
        ]);
    }
}
