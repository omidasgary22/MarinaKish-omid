<?php

namespace App\Http\Controllers;

use App\Http\Requests\PassengerRequest;
use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PassengerController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $passengers = $user->passengers;
        return response()->json($passengers);
    }

    public function store(PassengerRequest $request)
    {
        $passengerData = $request->toArray();
        $passenger = Passenger::create(array_merge($passengerData, ['user_id' => Auth::id()]));
        return response()->json(['message' => 'مسافر با موفقیت اضافه شد', 'passenger' => $passenger]);
    }

    public function update(PassengerRequest $request, $passengerId)
    {
        $passenger = Passenger::findOrFail($passengerId);

        $this->authorize('update', $passenger);

        $passenger->update($request->only(['name_and_surname', 'gender', 'age', 'national_code']));
        return response()->json(['message' => 'مسافر با موفقیت به‌روزرسانی شد', 'passenger' => $passenger]);
    }

    public function destroy(Passenger $passengerId)
    {
        $passenger = Passenger::findOrFail($passengerId);

        $this->authorize('delete', $passenger);

        $passenger->delete();
        return response()->json(['message' => 'مسافر با موفقیت حذف شد']);
    }
    
}
