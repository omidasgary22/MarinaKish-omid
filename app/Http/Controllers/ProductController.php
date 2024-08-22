<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Event\TestSuite\Loaded;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function store(StoreProductRequest $request)
    {
        if ($request->user()->can('product.store')) {
            $user = Auth::user();
            $time = $request->time;
            $pending = $request->pending;
            $total = $request->total;
            $started_time = Carbon::parse($request->started_at);
            $ended_at = Carbon::parse($request->ended_at);
            $age_limit = $request->age_limit;

            //
            $product = Product::create($request->toArray());
            $id = $product->id;
            SansController::store($time, $pending, $total, $started_time, $ended_at, $id, $age_limit);
            $product = Product::with('sans')->find($id);
            return response()->json(['message' => 'محصول با موفقیت ایجاد شد', 'product' => $product]);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }


    public function uploadImage(Request $request, $id)
    {
        if ($request->user()->can('product.uploadimage')) {
            $request->validate([
                'image' => 'required|max:10000|file|mimes:jpg,png,jpeg'
            ]);
            $product = Product::findOrFail($id);
            if ($request->hasFile('image')) {
                if ($product->getFirstMedia('images')) {
                    $product->clearMediaCollection('images');
                }
                $media = $product->addMedia($request->file('image'))->toMediaCollection('images');
                $mediaUrl = $media->getUrl(); // URL to access the image
                $mediaName = $media->name; // Optional: Media name (if set)

                return response()->json([
                    'message' => 'تصویر با موفقیت اپلود شد',
                    'media_url' => $mediaUrl,
                    'media_name' => $mediaName,
                ]);
            } else {
                return response()->json(['message' => 'هیچ تصویری برای اپلود یافت نشد'], 400);
            }
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید'], 403);
        }
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $time = $request->time;
        $pending = $request->pending;
        $total = $request->total;
        $started_at = Carbon::parse($request->started_at);
        $ended_at = Carbon::parse($request->ended_at);
        $user = Auth::user();


        if ($request->user()->can('product.update')) {
            $product = Product::findOrFail($id);
            $product->update($request->validated());
            return response()->json(['message' => 'محصول با موفقیت به روز رسانی شد', 'product' => $product]);
        } else {
            return response()->json(['message' => 'شما دسترسی لازم برای انجام این کار را ندارید'], 403);
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->can('product.delete')) {
            $product = Product::findOrFail($id);
            $product->delete();
            return response()->json(['محصول با موفقیت حذف شد ']);
        } else {
            return response()->json(['message' => 'شما دسترسی لازم یرای انجام این کار را ندارید'], 403);
        }
    }

    public function restore(Request $request, $id)
    {
        if ($request->user()->can('product.restore')) {
            $product = Product::withTrashed()->findOrFail($id);
            $product->restore();
            return response()->json(['message' => 'محصول با موفقیت بازگردانی شد', 'product' => $product]);
        } else {
            return response()->json(['message' => 'شما دسترسی لازم برای انجام این کار را ندارید'], 403);
        }
    }


    // public function getProductReports()
    // {
    //     $products = Product::with('tickett', 'comments')->get();

    //     $reports = $products->map(function ($product) {
    //         return [
    //             'product_name' => $product->name,
    //             'total_tickets_sold' => $product->total_tickets_sold,
    //             'total_sales' => $product->total_sales,
    //             'total_comments' => $product->total_comments,
    //             'average_rating' => $product->average_rating,
    //         ];
    //     });

    //     return response()->json($reports);
    // }

}
