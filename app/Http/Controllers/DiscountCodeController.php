<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiscountCodeRequest;
use App\Models\DiscountCode;
use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
    public function index()
    {
        $discountCode = DiscountCode::all();
        return response()->json($discountCode);
    }

    public function store(DiscountCodeRequest $request)
    {
        $discountCode = DiscountCode::create($request->toArray());
        return response()->json($discountCode, 201);
    }

    public function show($id)
    {
        $discountCode = DiscountCode::findOrFail($id);
        return response()->json($discountCode);
    }

    public function update(DiscountCodeRequest $request, $id)
    {
        $discountCode = DiscountCode::findOrFail($id);
        $discountCode->update($request->toArray());
        return response()->json($discountCode);
    }

    public function destroy($id)
    {
        $discountCode = DiscountCode::findOrFail($id);
        $discountCode->code .= '_deleted_' . now()->timestamp;
        $discountCode->save();
        $discountCode->delete();
        return response()->json(['message' => 'کد تخفیف با موفقیت حذف شد']);
    }
}
