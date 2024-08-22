<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tickett;
use Illuminate\Http\Request;

class ReportController extends Controller
{


    public function allReports()
    {
        // بازیابی همه محصولات
        $products = Product::all();

        // آرایه‌ای برای ذخیره اطلاعات هر محصول
        $reports = [];

        foreach ($products as $product) {
            // تعداد بلیط‌های فروش رفته برای هر محصول
            $soldTicketsCount = Tickett::whereHas('reservation', function($query) use ($product) {
                $query->where('product_id', $product->id);
            })->count();

            // مجموع فروش برای هر محصول
            $totalSales = Tickett::whereHas('reservation', function($query) use ($product) {
                $query->where('product_id', $product->id);
            })->sum('final_price');

            // تعداد نظرات ثبت شده برای هر محصول
            $reviewsCount = $product->comments()->count();

            // میانگین امتیازات برای هر محصول
            $averageRating = $product->comments()->avg('star');

            // اضافه کردن اطلاعات محصول به آرایه reports
            $reports[] = [
                ' تفریح' => $product->name,
                'تعداد بلیط های فروخته شده' => $soldTicketsCount,
                'میزان فروش' => $totalSales,
                'تعداد نظرات ثبت شده' => $reviewsCount,
                'امتیاز' => $averageRating,
            ];
        }

        // بازگرداندن مجموعه اطلاعات به عنوان پاسخ JSON
        return response()->json($reports);
    }

    public function show($productId)
    {

        $product = Product::findOrFail($productId);
        // تعداد بلیط‌های فروش رفته برای این محصول
        $soldTicketsCount = Tickett::whereHas('reservation', function($query) use ($productId) {
            $query->where('product_id', $productId);
        })->count();

        // مجموع فروش برای این محصول
        $totalSales = Tickett::whereHas('reservation', function($query) use ($productId) {
            $query->where('product_id', $productId);
        })->sum('final_price');

        // تعداد نظرات ثبت شده برای این محصول
        $reviewsCount = Product::find($productId)->comments()->count();

        // میانگین امتیازات برای این محصول
        $averageRating = Product::find($productId)->comments()->avg('star');

        // بازگرداندن این اطلاعات به ویو
        return response()->json([
            'product_name' => $product->name,
            'soldTicketsCount' => $soldTicketsCount,
            'totalSales' => $totalSales,
            'reviewsCount' => $reviewsCount,
            'averageRating' => $averageRating
        ]);
    }
}
