<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->can('order.index')) {
            $orders = Order::orderBy('id', 'desc')->paginate(10);
            return response()->json([$orders]);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید'], 403);
        }
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
        if ($request->user()->can('comment.store')) {
            $order = Order::create($request->toArray());
            return response()->json($order, 201);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید'], 403);
        }
    }

    public function update(Request $request, $id)
    {
        if ($request->user()->can('comment.update')) {
            $order = Order::find($id);
            if (!$order) {
                return response()->json(['message' => 'سفارشی وجود ندارد.'], 404);
            }
            $order->update($request->toArray());
            return response()->json($order);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید'], 403);
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->can('comment.destroy')) {
            $order = Order::find($id);
            if (!$order) {
                return response()->json(['message' => 'سفارشی یافت نشد.'], 404);
            }
            $order->delete();
            return response()->json(['message' => 'سفارش با موفقیت حذف شد.']);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید'], 403);
        }
    }
}
