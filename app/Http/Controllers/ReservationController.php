<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\DiscountCode;
use App\Models\Passenger;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\Sans;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * List all reservations with optional filters.
     */
    public function index(Request $request)
    {
        if ($request->user()->can('reservation.index')) {
            $query = Reservation::with(['user', 'sans', 'product', 'discountCode']);

            // Filter by reservation date
            if ($request->has('reservation_date')) {
                $query->whereDate('reservation_date', $request->input('reservation_date'));
            }

            // Filter by user's national code
            if ($request->has('national_code')) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('national_code', $request->input('national_code'));
                });
            }

            $reservations = $query->paginate(10);
            return response()->json($reservations);
        } else {
            return response()->json(['message' => 'شما دسترسی لازم را ندارید']);
        }
    }

    /**
     * Check if a specific sans is available for a given date.
     */
    public function checkAvailability($sans_id, $reservation_date)
    {
        return Reservation::where('sans_id', $sans_id)
            ->where('reservation_date', $reservation_date)
            ->doesntExist();
    }

    /**
     * Show the ticket for a specific reservation.
     */
    public function showTicket($id)
    {
        $reservation = Reservation::with(['user', 'passengers', 'product', 'sans', 'discountCode'])->findOrFail($id);
        return view('ticket', compact('reservation'));
    }


    public function store(StoreReservationRequest $request)
    {
        if ($request->user()->can('reservation.store')) {
            $validatedData = $request->validate();

            $sans_id = $validatedData['sans_id'];
            $reservation_date = $validatedData['reservation_date'];
            $product_id = $validatedData['product_id'];
            $passengerIds = $request->input('passengers', []);
            $discount_code = $validatedData['discount_code'] ?? null;

            $user = Auth::user();
            $sans = Sans::findOrFail($sans_id);
            $product = Product::findOrFail($product_id);

            // بررسی رزرو تکراری بر اساس product_id، sans_id و reservation_date
            $existingReservation = Reservation::where('product_id', $product_id)
                ->where('sans_id', $sans_id)
                ->where('reservation_date', $reservation_date)
                ->first();

            if ($existingReservation) {
                return response()->json(['message' => 'این سانس و تاریخ برای این تفریح قبلاً رزرو شده است.'], 400);
            }

            // Check product age limit
            if ($product->age_limited && $user->age < $product->age_limited) {
                return response()->json(['message' => 'سن شما کمتر از محدودیت سنی برای این تفریح است.'], 403);
            }

            $baseAmount = $product->price;
            $totalAmount = $baseAmount;

            // Check passengers' age limits and calculate total amount
            foreach ($passengerIds as $passengerId) {
                $passenger = Passenger::findOrFail($passengerId);

                if ($sans->age_limit && $passenger->age < $sans->age_limit) {
                    return response()->json(['message' => "سن مسافر {$passenger->name_and_surname} کمتر از محدودیت سنی برای این سانس است."], 403);
                }

                if ($product->age_limited && $passenger->age < $product->age_limited) {
                    return response()->json(['message' => "سن مسافر {$passenger->name_and_surname} کمتر از محدودیت سنی برای این تفریح است."], 403);
                }

                $totalAmount += $baseAmount;
            }

            // Handle discount code
            $discountAmount = 0;
            $finalAmount = $totalAmount;

            $discountCodeId = null;

            if ($discount_code) {
                $discountCode = DiscountCode::where('code', $discount_code)
                    ->where('expires_at', '>', now())
                    ->first();

                if (!$discountCode) {
                    return response()->json(['message' => 'کد تخفیف اشتباه است یا منقضی شده است'], 400);
                }

                $userDiscountCode = DB::table('user_discount_codes')
                    ->where('user_id', $user->id)
                    ->where('discount_code_id', $discountCode->id)
                    ->first();

                if ($userDiscountCode) {
                    return response()->json(['message' => 'شما قبلا از این کد تخفیف استفاده کرده‌اید'], 400);
                }

                $discountAmount = ($totalAmount * $discountCode->discount_percentage) / 100;
                $finalAmount = $totalAmount - $discountAmount;

                $discountCodeId = $discountCode->id;

                DB::table('user_discount_codes')->insert([
                    'user_id' => $user->id,
                    'discount_code_id' => $discountCode->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Create reservation
            $reservation = Reservation::create([
                'user_id' => $user->id,
                'sans_id' => $sans_id,
                'product_id' => $product_id,
                'reservation_date' => $reservation_date,
                'total_amount' => $totalAmount,
                'discount_code_id' => $discountCodeId,
                'discount_code' => $discount_code,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
                'status' => 'pending',
            ]);

            if (is_array($passengerIds) || is_object($passengerIds)) {
                $reservation->passengers()->attach($passengerIds);
            }

            // Return response with all amounts
            return response()->json([
                'message' => 'رزرو با موفقیت انجام شد.',
                'reservation' => $reservation,
                'total_amount' => $totalAmount,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
            ], 201);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید'], 403);
        }
    }


    /**
     * Update an existing reservation.
     */
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

    /**
     * Delete an existing reservation.
     */
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

    // /**
    //  * Apply a discount code to a reservation.
    //  */
    // public function applyDiscountCode(Request $request)
    // {
    //     $request->validate([
    //         'reservation_id' => 'required|exists:reservations,id',
    //         'discount_code' => 'required|string|exists:discount_codes,code',
    //     ]);

    //     $reservation = Reservation::findOrFail($request->input('reservation_id'));
    //     $discountCode = DiscountCode::where('code', $request->input('discount_code'))->firstOrFail();

    //     // Check expiration date
    //     if ($discountCode->expires_at < now()) {
    //         return response()->json(['message' => 'کد تخفیف منقضی شده است'], 400);
    //     }

    //     // Check if the user has used the code before
    //     $userHasUsedCode = Reservation::where('user_id', Auth::id())
    //         ->where('discount_code_id', $discountCode->id)->exists();

    //     if ($userHasUsedCode) {
    //         return response()->json(['message' => 'شما قبلا از این کد تخفیف استفاده کرده اید'], 400);
    //     }

    //     // Calculate the final amount with discount
    //     $totalAmount = $reservation->total_amount;
    //     $discountAmount = ($totalAmount * $discountCode->discount_percentage) / 100;
    //     $finalAmount = $totalAmount - $discountAmount;

    //     // Update reservation with discount code
    //     $reservation->discount_code_id = $discountCode->id;
    //     $reservation->save();

    //     return response()->json([
    //         'total_amount' => $totalAmount,
    //         'discount_amount' => $discountAmount,
    //         'final_amount' => $finalAmount,
    //     ]);
    // }


    
    // public function store(Request $request, Sans $sans, Product $product)
    // {
    //     $data = $request->all();
    //     $data['user_id'] = Auth()->id();
    //     $data['sans_id'] = $sans->id;
    //     $data['product_id'] = $product->id;
    //     $data['ticket_number'] = rand(10000, 99999);
    //     $data['total_amount'] = count($request->passengers) * $product->price ;
    //     if ($data['discount_code'])
    //     {
    //         //check have this code or no in database and is valid
    //         $discount_code = DiscountCode::where('code', $data['discount_code'])
    //         ->where('expires_at' > Carbon::now())
    //         ->first();
    //         if (!$discount_code)
    //         {
    //             return response()->json(['message' => 'کد تخفیف یافت نشد']);
    //         }
    //         // do you have this discount_code in pivot table?
    //        $user_code = DB::table('user_discount_codes')->where('user_id', Auth()->id())
    //         ->where('discount_code_id', $discount_code->id)->first();
    //         if (!$user_code) 
    //         {
    //             return response()->json(['message' => 'کد تخفیف متعلق به شما نیست']);
    //         }

    //         $data['total_amount'] = (count($request->passengers ) * $product->price) - (count($request->passengers ) * $product->price * $discount_code->discount_percentage /100);
    //     }

    //     $reservation = Reservation::create($data);
    //     return response()->json($reservation);

    // }
}
