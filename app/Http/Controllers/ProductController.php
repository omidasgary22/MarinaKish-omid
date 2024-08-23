<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of all products.
     * Returns a JSON response containing all products.
     */
    public function index(Request $request)
    {
        $products = Product::all();
        return response()->json($products);
    }

    /**
     * Store a newly created product in storage.
     * Validates the user’s permission and creates a new product.
     * Also handles related operations through the SansController.
     */
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

            // Create the product
            $product = Product::create($request->toArray());
            $id = $product->id;

            // Call SansController to handle related data
            SansController::store($time, $pending, $total, $started_time, $ended_at, $id, $age_limit);
            
            // Reload the product with its related data
            $product = Product::with('sans')->find($id);

            return response()->json(['message' => 'محصول با موفقیت ایجاد شد', 'product' => $product]);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید'], 403);
        }
    }

    /**
     * Upload an image for a specified product.
     * Validates the image file and attaches it to the product’s media collection.
     */
    public function uploadImage(Request $request, $id)
    {
        if ($request->user()->can('product.uploadimage')) {
            $request->validate([
                'image' => 'required|max:10000|file|mimes:jpg,png,jpeg'
            ]);

            $product = Product::findOrFail($id);
            
            if ($request->hasFile('image')) {
                // Clear existing images and add the new image
                if ($product->getFirstMedia('images')) {
                    $product->clearMediaCollection('images');
                }

                $media = $product->addMedia($request->file('image'))->toMediaCollection('images');
                $mediaUrl = $media->getUrl(); // URL to access the image
                $mediaName = $media->name; // Optional: Media name (if set)

                return response()->json([
                    'message' => 'تصویر با موفقیت آپلود شد',
                    'media_url' => $mediaUrl,
                    'media_name' => $mediaName,
                ]);
            } else {
                return response()->json(['message' => 'هیچ تصویری برای آپلود یافت نشد'], 400);
            }
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید'], 403);
        }
    }

    /**
     * Update the specified product in storage.
     * Validates the user’s permission and updates the product’s details.
     */
    public function update(UpdateProductRequest $request, $id)
    {
        if ($request->user()->can('product.update')) {
            $product = Product::findOrFail($id);

            // Update the product with validated data
            $product->update($request->validated());

            return response()->json(['message' => 'محصول با موفقیت به روز رسانی شد', 'product' => $product]);
        } else {
            return response()->json(['message' => 'شما دسترسی لازم برای انجام این کار را ندارید'], 403);
        }
    }

    /**
     * Remove the specified product from storage.
     * Validates the user’s permission and deletes the product.
     */
    public function destroy(Request $request, $id)
    {
        if ($request->user()->can('product.delete')) {
            $product = Product::findOrFail($id);
            $product->delete();
            return response()->json(['message' => 'محصول با موفقیت حذف شد']);
        } else {
            return response()->json(['message' => 'شما دسترسی لازم برای انجام این کار را ندارید'], 403);
        }
    }

    /**
     * Restore a previously deleted product.
     * Validates the user’s permission and restores the product from the trash.
     */
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
}
