<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateReservationRequest;
use App\Models\DiscountCode;
use App\Models\Passenger;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\Sans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Create a new reservation.
     */
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

            // Check product age limit
            if ($product->age_limited && $user->age < $product->age_limited) {
                return response()->json(['message' => 'سن شما کمتر از محدودیت سنی برای این تفریح است.'], 403);
            }

            $totalAmount = $product->price;

            // Check passengers' age limits
            if (is_array($passengerIds) || is_object($passengerIds)) {
                foreach ($passengerIds as $passengerId) {
                    $passenger = Passenger::findOrFail($passengerId);

                    // Check age limit for the sans
                    if ($sans->age_limit && $passenger->age < $sans->age_limit) {
                        return response()->json(['message' => "سن مسافر {$passenger->name_and_surname} کمتر از محدودیت سنی برای این سانس است."], 403);
                    }

                    // Check age limit for the product
                    if ($product->age_limited && $passenger->age < $product->age_limited) {
                        return response()->json(['message' => "سن مسافر {$passenger->name_and_surname} کمتر از محدودیت سنی برای این تفریح است."], 403);
                    }

                    // Add amount for passengers
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

            // Attach passengers to the reservation
            if (is_array($passengerIds) || is_object($passengerIds)) {
                $reservation->passengers()->attach($passengerIds);
            }

            return response()->json(['message' => 'رزرو با موفقیت انجام شد.', 'reservation' => $reservation, 'total_amount' => $totalAmount], 201);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
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

    /**
     * Apply a discount code to a reservation.
     */
    public function applyDiscountCode(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'discount_code' => 'required|string|exists:discount_codes,code',
        ]);

        $reservation = Reservation::findOrFail($request->input('reservation_id'));
        $discountCode = DiscountCode::where('code', $request->input('discount_code'))->firstOrFail();

        // Check expiration date
        if ($discountCode->expires_at < now()) {
            return response()->json(['message' => 'کد تخفیف منقضی شده است'], 400);
        }

        // Check if the user has used the code before
        $userHasUsedCode = Reservation::where('user_id', Auth::id())
            ->where('discount_code_id', $discountCode->id)->exists();

        if ($userHasUsedCode) {
            return response()->json(['message' => 'شما قبلا از این کد تخفیف استفاده کرده اید'], 400);
        }

        // Calculate the final amount with discount
        $totalAmount = $reservation->total_amount;
        $discountAmount = ($totalAmount * $discountCode->discount_percentage) / 100;
        $finalAmount = $totalAmount - $discountAmount;

        // Update reservation with discount code
        $reservation->discount_code_id = $discountCode->id;
        $reservation->save();

        return response()->json([
            'total_amount' => $totalAmount,
            'discount_amount' => $discountAmount,
            'final_amount' => $finalAmount,
        ]);
    }
}
