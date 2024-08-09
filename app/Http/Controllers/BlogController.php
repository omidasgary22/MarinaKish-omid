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
        if ($request->user()->can('blog.store')) {
            $blog = Blog::create($request->all());
            return response()->json(['message' => 'بلاک با موفقیت ایجاد شد', 'blog' => $blog], 201);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }

    public function uploadImage(Request $request, $id)
    {
        if ($request->user()->can('blog.uploadimage')) {
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
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید']);
        }
    }

    public function show(Request $request, $id)
    {
        if ($request->user()->can('blog.show')) {
            $blog = Blog::findOrFail($id);
            return response()->json(['blog' => $blog]);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید'], 403);
        }
    }

    public function update(UpdateBlogRequest $request, $id)
    {
        if ($request->user()->can('blog.update')) {
            $blog = Blog::findOrFail($id);
            $blog->update($request->all());
            return response()->json(['message' => 'بلاگ با موفقیت به روز رسانی شد', 'blog' => $blog]);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید'], 403);
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->can('blog.destroy')) {
            $blog = Blog::findOrFail($id);
            $blog->delete();
            return response()->json(['message' => 'بلاگ با موفقیت حذف شد']);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار ندارید'], 403);
        }
    }

    public function restore(Request $request, $id)
    {
        if ($request->user()->can('blog.restore')) {
            $blog = Blog::onlyTrashed()->findOrFail($id);
            $blog->restore();
            return response()->json(['message' => 'بلاگ با موفقیت بازیابی شد',]);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار ندارید'], 403);
        }
    }

    //Total removal from the database

    public function forceDelete(Request $request, $id)
    {
        if ($request->user()->can('blog.forcedelete')) {
            $blog = Blog::onlyTrashed()->findOrFail($id);
            $blog->forceDelete();
            return response()->json(['message' => 'بلاگ با موفقیت به صورت دایمی حذف شد']);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید'], 403);
        }
    }
}
