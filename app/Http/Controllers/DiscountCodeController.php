<?php

namespace App\Http\Controllers;

use App\Models\DiscountCode;
use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
    public function index()
    {
        $discountCode = DiscountCode::all();
        return response()->json($discountCode);
    }

    public function store($request)
    {
        $discountCode = DiscountCode::create($request->toArray());
        return response()->json($discountCode, 201);
    }

    public function show($id)
    {
        $discountCode = DiscountCode::findOrFile($id);
        return response()->json($discountCode);
    }

    public function update($request, $id)
    {
        $discountCode = DiscountCode::findOrFile($id);
        $discountCode->update($request->toArray());
        return response()->json($discountCode);
    }

    public function destroy($id)
    {
        $discountCode = DiscountCode::findOrFaile($id);
        $discountCode->delete();
        return response()->json(['message' => 'کد تخفیف با موفقیت حذف شد']);
    }
}
