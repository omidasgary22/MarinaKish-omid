<?php

namespace App\Http\Controllers;

use App\Http\Requests\PassengerRequest;
use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PassengerController extends Controller
{
    /**
     * Display a listing of the passengers for the authenticated user.
     * Returns all passengers associated with the authenticated user if they have the required permissions.
     */
    public function index(Request $request)
    {
        if ($request->user()->can('passenger.index')) {
            $user = Auth::user();
            $passengers = $user->passengers;
            return response()->json($passengers);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }

    /**
     * Store a newly created passenger in the database.
     * Accepts a validated request and creates a new passenger entry for the authenticated user if they have the required permissions.
     */
    public function store(PassengerRequest $request)
    {
        if ($request->user()->can('passenger.store')) {
            $passengerData = $request->toArray();
            $passenger = Passenger::create(array_merge($passengerData, ['user_id' => Auth::id()]));
            return response()->json(['message' => 'مسافر با موفقیت اضافه شد', 'passenger' => $passenger]);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }

    /**
     * Update the specified passenger in the database.
     * Finds a passenger by ID and updates its details if the authenticated user has the required permissions.
     */
    public function update(PassengerRequest $request, $passengerId)
    {
        if ($request->user()->can('passenger.update')) {
            $passenger = Passenger::findOrFail($passengerId);

            $this->authorize('update', $passenger);

            $passenger->update($request->only(['name_and_surname', 'gender', 'age', 'national_code']));
            return response()->json(['message' => 'مسافر با موفقیت به‌روزرسانی شد', 'passenger' => $passenger]);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }

    /**
     * Remove the specified passenger from the database.
     * Finds a passenger by ID, marks it as deleted, and then removes it if the authenticated user has the required permissions.
     */
    public function destroy(Request $request, $id)
    {
        if ($request->user()->can('passenger.destroy')) {
            $passenger = Passenger::findOrFail($id);

            $passenger->national_code .= '_deleted_' . now()->timestamp;
            $passenger->save();
            $passenger->delete();

            return response()->json(['message' => 'مسافر با موفقیت حذف شد']);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }
}
