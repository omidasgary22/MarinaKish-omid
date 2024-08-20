<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiscountCodeRequest;
use App\Models\DiscountCode;
use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->can('discount.index')) {
            $discountCode = DiscountCode::all();
            return response()->json($discountCode);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }

    public function store(DiscountCodeRequest $request)
    {
        if ($request->user()->can('discount.store')) {
            $discountCode = DiscountCode::create($request->toArray());
            return response()->json($discountCode, 201);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }

    // public function show(Request $request, $id)
    // {

    //     $discountCode = DiscountCode::findOrFail($id);
    //     return response()->json($discountCode);
    // }

    public function update(DiscountCodeRequest $request, $id)
    {
        if ($request->user()->can('discount.update')) {
            $discountCode = DiscountCode::findOrFail($id);
            $discountCode->update($request->toArray());
            return response()->json($discountCode);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->can('discount.destroy')) {
            $discountCode = DiscountCode::findOrFail($id);
            $discountCode->code .= '_deleted_' . now()->timestamp;
            $discountCode->save();
            $discountCode->delete();
            return response()->json(['message' => 'کد تخفیف با موفقیت حذف شد']);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }
}
