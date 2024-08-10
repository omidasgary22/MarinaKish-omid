<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use PHPUnit\Event\TestSuite\Loaded;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->can('product.index')) {
            $products = Product::all();
            return response()->json($products);
        } else {
            return response()->json(['message' => 'شما دسترسی لازم برای انجام این کار را ندارید'], 403);
        }
    }

    public function store(StoreProductRequest $request)
    {
        if ($request->user()->can('product.store')) {
            $product = Product::create($request->toArray());
            return response()->json(['message' => 'محصول با موفقیت ایجاد شد', 'product' => $product]);
        } else {
            return response()->json(['message' => 'شما دسترسی لازم برای انجام این کار را ندارید'], 403);
        }
    }

    public function uploadImage(Request $request, $id)
    {
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
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->validated());
        return response()->json(['message' => 'محصول با موفقیت به روز رسانی شد', 'product' => $product]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['محصول با موفقیت حذف شد ']);
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();
        return response()->json(['message' => 'محصول با موفقیت بازگردانی شد', 'product' => $product]);
    }
}
