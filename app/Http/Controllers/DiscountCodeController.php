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
}
