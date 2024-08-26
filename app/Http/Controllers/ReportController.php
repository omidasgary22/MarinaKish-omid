<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\Tickett;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Get all product reports including ticket sales, total sales, reviews count, and average ratings.
     * Returns a JSON response containing the reports.
     */
    public function allReports()
    {
        // Retrieve all products
        $products = Product::all();

        // Array to store each product's report
        $reports = [];

        foreach ($products as $product) {
            // Count of sold tickets for each product
            $soldTicketsCount = Tickett::whereHas('reservation', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })->count();

            // Total sales amount for each product
            $totalSales = Tickett::whereHas('reservation', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })->sum('final_amount');

            // Number of comments for each product
            $reviewsCount = $product->comments()->count();

            // Average rating for each product
            $averageRating = $product->comments()->avg('star');

            // Add the product report to the reports array
            $reports[] = [
                'تفریح' => $product->name,
                'تعداد بلیط های فروخته شده' => $soldTicketsCount,
                'میزان فروش' => $totalSales,
                'تعداد نظرات ثبت شده' => $reviewsCount,
                'امتیاز' => $averageRating,
            ];
        }

        // Return the reports as a JSON response
        return response()->json($reports);
    }

    /**
     * Show detailed report for a specific product between given dates.
     * Includes ticket sales by date, most reserved sans, average rating, and total comments.
     */
    public function show(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        // Get start and end dates from the request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Retrieve count of tickets sold grouped by date
        $ticketsSold = Tickett::whereBetween('purchase_time', [$startDate, $endDate])
            ->selectRaw('DATE(purchase_time) as date, COUNT(*) as total_sold')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Retrieve the most reserved sans within the date range
        $mostReservedSans = Reservation::whereBetween('reservation_date', [$startDate, $endDate])
            ->selectRaw('sans_id, COUNT(*) as total_reservations')
            ->groupBy('sans_id')
            ->orderBy('total_reservations', 'desc')
            ->get();

        // Calculate the average rating within the date range
        $averageRating = Comment::whereBetween('created_at', [$startDate, $endDate])
            ->avg('star');

        // Count of all comments within the date range
        $totalComments = Comment::whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return response()->json([
            'تعداد بلیط های فروش رفته' => $ticketsSold,
            'بیشترین سانس های رزرو شده' => $mostReservedSans,
            'امتیاز ثبت شده' => $averageRating,
            'تعداد نظرات' => $totalComments,
        ]);
    }
}
