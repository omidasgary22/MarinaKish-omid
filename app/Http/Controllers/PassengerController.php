<?php

namespace App\Http\Controllers;

use App\Http\Requests\PassengerRequest;
use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PassengerController extends Controller
{
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

    public function update(PassengerRequest $request, $passengerId)
    {
        if ($request->user()->can('passenger.update')) {
            $passenger = Passenger::findOrFail($passengerId);

            $this->authorize('update', $passenger);

            $passenger->update($request->only(['name_and_surname', 'gender', 'age', 'national_code']));
            return response()->json(['message' => 'مسافر با موفقیت به‌روزرسانی شد', 'passenger' => $passenger]);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز  را ندارید']);
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->can('passenger.destroy')) {
            $passenger = Passenger::findOrFail($id);
            // $this->authorize('delete', $id);
            $passenger->national_code .= '_deleted_' . now()->timestamp;
            $passenger->save();
            $passenger->delete();
            return response()->json(['message' => 'مسافر با موفقیت حذف شد']);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }
}
