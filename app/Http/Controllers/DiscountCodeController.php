<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiscountCodeRequest;
use App\Models\DiscountCode;
use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
    /**
     * Display a listing of all discount codes.
     * This method returns all discount codes in JSON format if the user has the necessary permission.
     */
    public function index(Request $request)
    {
        if ($request->user()->can('discount.index')) {
            $discountCodes = DiscountCode::all();
            return response()->json($discountCodes);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید'], 403);
        }
    }

    /**
     * Store a newly created discount code in storage.
     * This method creates a new discount code if the user has the necessary permission.
     */
    public function store(DiscountCodeRequest $request)
    {
        if ($request->user()->can('discount.store')) {
            $discountCode = DiscountCode::create($request->toArray());
            return response()->json($discountCode, 201);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید'], 403);
        }
    }

    /**
     * Update the specified discount code in storage.
     * This method updates the discount code if the user has the necessary permission.
     */
    public function update(DiscountCodeRequest $request, $id)
    {
        if ($request->user()->can('discount.update')) {
            $discountCode = DiscountCode::findOrFail($id);
            $discountCode->update($request->toArray());
            return response()->json($discountCode);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید'], 403);
        }
    }

    /**
     * Soft delete the specified discount code.
     * This method appends a timestamp to the discount code before soft deleting it if the user has the necessary permission.
     */
    public function destroy(Request $request, $id)
    {
        if ($request->user()->can('discount.destroy')) {
            $discountCode = DiscountCode::findOrFail($id);
            $discountCode->code .= '_deleted_' . now()->timestamp;
            $discountCode->save();
            $discountCode->delete();
            return response()->json(['message' => 'کد تخفیف با موفقیت حذف شد']);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید'], 403);
        }
    }

    public function give_discount_code(Request $request)
    {
        if ($request->user()->can('discount_give'))
        {
            $discount_code = DiscountCode::find($request->discount_code);
            $users = $request->user_ids;
            if (count($users) > $discount_code->quantity)
            {
                return response()->json(['message' => 'تعداد کد تخفیف کافی نیست']);
            }
            $discount_code->users()->attach($users);
            $discount_code->decrement('quantity',count($users));
            return response()->json($discount_code);
        } 
        else 
        {
            return response()->json(['message' => 'شما دسترسی انجام این کا را ندارید']);
        }
    }
}
