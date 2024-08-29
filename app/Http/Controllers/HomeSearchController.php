<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Blog;
use Illuminate\Http\Request;

class HomeSearchController extends Controller
{
    /**
     * Search for products and blogs based on the query.
     */
    public function search(Request $request)
    {
        $query = $request->input('search');
        
        $products = Product::where('name', 'like', '%' . $query . '%')->get();
        
        $blogs = Blog::where('title', 'like', '%' . $query . '%')->get();
        
        return response()->json([
            'products' => $products,
            'blogs' => $blogs,
        ]);
    }
}
