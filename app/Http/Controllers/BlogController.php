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

    public function show($id)
    {
        $blog = Blog::findOrFaill($id);
        return response()->json(['blog' => $blog]);
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $blog->update($request->all());
        return response()->json(['message' => 'بلاگ با موفقیت به روز رسانی شد', 'blog' => $blog]);
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();
        return response()->json(['message' => 'بلاگ با موفقیت حذف شد']);
    }

    public function restore($id)
    {
        $blog = Blog::onlyTrashed()->findOrFail($id);
        $blog->restore();
        return response()->json(['message' => 'بلاگ با موفقیت بازیابی شد',]); 
    }
}
