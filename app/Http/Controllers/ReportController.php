<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use App\Models\Reservation;
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
            $soldTicketsCount = Tickett::whereHas('reservation', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })->count();

            // مجموع فروش برای هر محصول
            $totalSales = Tickett::whereHas('reservation', function ($query) use ($product) {
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


    public function show(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        // دریافت تاریخ شروع و پایان از درخواست کاربر
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // استخراج تعداد بلیط‌های فروش رفته به تفکیک روز
        $ticketsSold = Tickett::whereBetween('purchase_time', [$startDate, $endDate])
            ->selectRaw('DATE(purchase_time) as date, COUNT(*) as total_sold')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // استخراج بیشترین سانس‌های رزرو شده
        $mostReservedSans = Reservation::whereBetween('reservation_date', [$startDate, $endDate])
            ->selectRaw('sans_id, COUNT(*) as total_reservations')
            ->groupBy('sans_id')
            ->orderBy('total_reservations', 'desc')
            ->get();

        // محاسبه میانگین امتیازات ثبت شده
        $averageRating = Comment::whereBetween('created_at', [$startDate, $endDate])
            ->avg('star');

        // تعداد کل نظرات ثبت شده
        $totalComments = Comment::whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // // تعداد نظرات پاسخ داده شده
        // $answeredComments = Comment::whereBetween('created_at', [$startDate, $endDate])
        //     ->whereNotNull('answer')
        //     ->count();

        // // تعداد نظرات در انتظار پاسخ
        // $pendingComments = Comment::whereBetween('created_at', [$startDate, $endDate])
        //     ->whereNull('answer')
        //     ->count();

        return response()->json([
            'تعداد بلیط های فروش رفته' => $ticketsSold,
            'بیشترین سانس های رزرو شده' => $mostReservedSans,
            'امتیاز ثبت شده' => $averageRating,
            'تعداد نظرات' => $totalComments,
            // 'نظر پاسخ داده شده' => $answeredComments,
            // 'نظر در انتظار پاسخ' => $pendingComments,
        ]);
    }
}
