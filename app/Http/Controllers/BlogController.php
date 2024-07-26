<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::all();
        return response()->json(['blogs' => $blogs]);
    }

    public function store(Request $request)
    {
        $blog = Blog::create($request->all());
        return response()->json(['message' => 'بلاک با موفقیت ایجاد شد', 'blog' => $blog], 201);
    }
}
