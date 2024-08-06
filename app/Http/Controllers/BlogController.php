<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::all();
        return response()->json(['blogs' => $blogs]);
    }

    public function store(StoreBlogRequest $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'cover_image' => 'required|image|mimes:jpg,jpeg|dimensions:width=247,height=163|max:720',
        //     'main_image' => 'required|image|mimes:jpg,jpeg|dimensions:width=247,height=163|max:720',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 400);
        // }

        $blog = Blog::create($request->all());
        // if ($request->hasFile('cover_image')) {
        //     $blog->addMediaFromRequest('cover_image')->toMediaCollection('cover_image');
        // }

        // if ($request->hasFile('main_image')) {
        //     $blog->addMediaFromRequest('main_image')->toMediaCollection('main_image');
        // }
        return response()->json(['message' => 'بلاک با موفقیت ایجاد شد', 'blog' => $blog], 201);
    }

    public function uploadImage(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'cover_image' => 'sometimes|image|mimes:jpg,jpeg|dimensions:width=247,height=163|max:720',
            'main_image' => 'sometimes|image|mimes:jpg,jpeg|dimensions:width=247,height=163|max:720',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $response = [];

        if ($request->hasFile('cover_image')) {
            if ($blog->getFirstMedia('cover_image')) {
                $blog->clearMediaCollection('cover_image');
            }
            $media = $blog->addMediaFromRequest('cover_image')->toMediaCollection('cover_image');
            $response['cover_image'] = [
                'name' => $media->file_name,
                'size' => $media->size,
                'mime_type' => $media->mime_type,
            ];
        }
        if ($request->hasFile('main_page')) {
            if ($blog->getFirstMedia('main_page')) {
                $blog->clearMediaCollection('main_image');
            }
            $media = $blog->addMediaFromRequest('main_image')->toMediaCollection('main_images');
            $response['main_image'] = [
                'name' => $media->file_name,
                'size' => $media->size,
                'mime_type' => $media->mime_type,
            ];
        }
        return response()->json(['message' => 'تصاویر با موفقیت آپلود شدند', 'images' => $response]); 
    }

    public function show($id)
    {
        $blog = Blog::findOrFail($id);
        return response()->json(['blog' => $blog]);
    }

    public function update(UpdateBlogRequest $request, $id)
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

    //Total removal from the database

    public function forceDelete($id)
    {
        $blog = Blog::onlyTrashed()->findOrFail($id);
        $blog->forceDelete();
        return response()->json(['message' => 'بلاگ با موفقیت به صورت دایمی حذف شد']);
    }
}