<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('id', 'desc')->paginate(10);
        return response()->json([$orders]);
    }

    public function show($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'سفارشی یافت نشد.'], 404);
        }
        return response()->json($order);
    }

    public function store(Request $request)
    {
        $order = Order::create($request->toArray());
        return response()->json($order, 201); 
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'سفارشی وجود ندارد.'], 404);
        }
        $order->update($request->toArray());
        return response()->json($order);
    }
}
