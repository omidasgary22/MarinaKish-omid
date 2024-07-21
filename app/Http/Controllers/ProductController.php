<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function store($request)
    {
        $product = Product::create($request->validated());
        return response()->json(['message' => 'محصول با موفقیت ایجاد شد', 'product' => $product]);
    }

   
}
